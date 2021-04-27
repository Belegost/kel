<?php

namespace App\Service;

use DateTime;
use App\Entity\Integrity\Token;
use App\Traits\DoctrineAwareTrait;
use App\Repository\TokenRepository;

/**
 * Class IntegrityToken
 */
class TokenManager
{
    use DoctrineAwareTrait;

    /**
     * @var Token
     */
    private ?Token $token;

    /**
     * Load token by hash
     *
     * @param string $hash
     *
     * @return $this
     */
    public function loadToken(string $hash): TokenManager
    {
        $this->token = $this->getTokenRepository()->findOneBy(["hash" => $hash]);

        return $this;
    }

    /**
     * Get token data
     *
     * @return array
     */
    public function getTokenData(): array
    {
        if ($this->isTokenExist()) {
            return $this->token->getData();
        }

        return [];
    }

    /**
     * Get token hash
     *
     * @return string
     */
    public function getTokenHash(): string
    {
        if ($this->isTokenExist()) {
            return $this->token->getHash();
        }

        return '';
    }

    /**
     * Check is token valid
     *
     * @return bool
     */
    public function isTokenValid(): bool
    {
        try {
            if ($this->isTokenExist()) {
                $expiredTime = $this->token->getExpiredTime();

                if ($expiredTime) {
                    if ($expiredTime->getTimestamp() > (new DateTime())->getTimestamp()) {
                        return true;
                    }
                    $this->resetToken();
                }
            }
        } catch (\Exception $e) {

        }

        return false;
    }

    /**
     * Generate token
     *
     * @param array|null $data
     * @param DateTime|null $expiredTime
     *
     * @return $this
     * @throws \Exception
     */
    public function generateToken(array $data = null, DateTime $expiredTime = null): TokenManager
    {
        $token = new Token();
        $token->setHash(bin2hex(random_bytes(32)));
        $token->setData($data);
        $token->setExpiredTime($expiredTime);

        $em = $this->getDoctrine()->getManager();
        $em->persist($token);
        $em->flush();

        $this->token = $token;

        return $this;
    }

    /**
     * Remove token
     *
     * @return TokenManager
     */
    public function resetToken(): TokenManager
    {
        if ($this->isTokenExist()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($this->token);
            $em->flush();

            unset($this->token);
        }

        return $this;
    }

    /**
     * Check is token exist
     *
     * @return bool
     */
    public function isTokenExist(): bool
    {
        return isset($this->token) && $this->token instanceof Token;
    }

    /**
     * Return Token Repository instance
     *
     * @return TokenRepository
     */
    protected function getTokenRepository(): TokenRepository
    {
        return $this->getDoctrine()->getRepository(Token::class);
    }
}
