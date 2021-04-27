<?php

namespace App\Entity\Integrity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TokenRepository")
 * @ORM\Table(name="tokens")
 */
class Token {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $hash;

    /**
     * @ORM\Column(type="json_array", nullable=TRUE)
     */
    private ?array $data;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     */
    private ?DateTime $expired_time;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $created_time;

    /**
     * Token constructor.
     */
    public function __construct()
    {
        $this->created_time = new DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @param array|null $data
     */
    public function setData(?array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return DateTime|null
     */
    public function getExpiredTime(): ?DateTime
    {
        return $this->expired_time;
    }

    /**
     * @param DateTime|null $expired_time
     */
    public function setExpiredTime(?DateTime $expired_time): void
    {
        $this->expired_time = $expired_time;
    }

    /**
     * @return DateTime
     */
    public function getCreatedTime(): DateTime
    {
        return $this->created_time;
    }

    /**
     * @param DateTime $created_time
     */
    public function setCreatedTime(DateTime $created_time): void
    {
        $this->created_time = $created_time;
    }
}
