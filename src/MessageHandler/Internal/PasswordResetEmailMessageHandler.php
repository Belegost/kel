<?php

namespace App\MessageHandler\Internal;

use App\Entity\Integrity\Account;
use App\Message\Internal\PasswordResetEmailMessage;
use App\MessageHandler\MessageHandlerAbstract;
use App\Service\TokenManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Exception;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Class PasswordResetEmailMessageHandler
 */
class PasswordResetEmailMessageHandler extends MessageHandlerAbstract
{
    public function __invoke(PasswordResetEmailMessage $message)
    {
        $doctrine = $this->getDoctrine();

        try {
            /**
             * @var Account $account
             */
            $account = $doctrine->getManager()->find(Account::class, $message->getAccountId());

            $templateEmail = (new TemplatedEmail())
                ->from($this->getParameter('integrity_contacts')['email'])
                ->to($account->getEmail())
                ->subject('INTEGRITY Crypto Trust | Reset Password')
                ->htmlTemplate('email/password-reset.html.twig')
                ->context(
                    [
                        'CLIENT_NAME'  => $account->getUsername(),
                        'RESET_LINK' => urldecode(
                            $this->generateUrl(
                                'route_password_change',
                                [
                                    'token' => $this->getTokenManager()->generateToken(
                                        ['PUBLIC_KEY' => $account->getPublicKey()],
                                        date_create('+4 hours')
                                    )->getTokenHash(),
                                ],
                                UrlGeneratorInterface::ABSOLUTE_URL
                            )
                        ),
                        'EXPIRE_LINK' => 4
                    ]
                );

            $this->getMailer()->send($templateEmail);

        } catch (Exception $e) {
            echo $e->getMessage();
            dd($e);
        } catch (TransportExceptionInterface $e) {
            echo $e->getMessage();
            dd($e);
        }
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param string $route
     * @param array $parameters
     * @param int $referenceType
     *
     * @return string
     */
    protected function generateUrl(
        string $route,
        array $parameters = [],
        int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    ): string {
        if (!$this->has('router')) {
            throw new ServiceNotFoundException('router');
        }

        return $this->get('router')->generate($route, $parameters, $referenceType);
    }

    /**
     * Returns Token Manager
     *
     * @return TokenManager
     */
    protected function getTokenManager(): TokenManager
    {
        if (!$this->has('app.service.token_manager')) {
            throw new ServiceNotFoundException('app.service.token_manager');
        }

        return $this->get('app.service.token_manager');
    }

    /**
     * Returns Mailer instance
     *
     * @return MailerInterface
     */
    protected function getMailer(): MailerInterface
    {
        if (!$this->has('app.mailer')) {
            throw new ServiceNotFoundException('app.mailer');
        }

        return $this->get('app.mailer');
    }
}