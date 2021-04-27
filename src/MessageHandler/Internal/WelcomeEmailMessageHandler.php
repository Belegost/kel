<?php

namespace App\MessageHandler\Internal;

use App\Message\Internal\WelcomeEmailMessage;
use App\MessageHandler\MessageHandlerAbstract;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

use Exception;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Class WelcomeEmailMessageHandler
 */
class WelcomeEmailMessageHandler extends MessageHandlerAbstract
{
    public function __invoke(WelcomeEmailMessage $message)
    {
        try {
            $from = $this->getParameter('integrity_contacts')['email'];

            $templateEmail = (new TemplatedEmail())
                ->from($from)
                ->to($message->getEmail())
                ->subject('INTEGRITY Crypto Trust | Your Credentials')
                ->htmlTemplate('email/signup.html.twig')
                ->context(
                    [
                        'CLIENT_NAME' => $message->getClientName(),
                        'LOGIN' => $message->getLogin(),
                        'PASSWORD' => $message->getPassword(),
                        'INTEGRITY_EMAIL' => $from,
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