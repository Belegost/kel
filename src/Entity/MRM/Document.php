<?php

namespace App\Entity\MRM;

use App\Entity\Integrity\Account;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MRM\DocumentRepository")
 * @ORM\Table(name="compliance_document")
 *
 * @UniqueEntity(fields={"type", "account"}, message="Only one file upload row on account is allowed")
 */
class Document
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var DocumentType
     *
     * @ORM\ManyToOne(targetEntity="DocumentType")
     * @ORM\JoinColumn(name="document_type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @var DocumentState
     *
     * @ORM\ManyToOne(targetEntity="DocumentState")
     * @ORM\JoinColumn(name="document_state_id", referencedColumnName="id")
     */
    private $state;

    /**
     * @var int
     *
     * @ORM\Column(name="account_id", type="integer")
     */
    private $accountId;

    /**
     * @var string
     *
     * @ORM\Column(name="upload_link", type="string", length=255)
     */
    private $uploadLink;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="upload_date", type="datetime", nullable=false)
     */
    private $uploadDate;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return DocumentType
     */
    public function getType(): DocumentType
    {
        return $this->type;
    }

    /**
     * @param DocumentType $type
     *
     * @return $this
     */
    public function setType(DocumentType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     *
     * @return $this
     */
    public function setAccountId(int $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return DocumentState
     */
    public function getState(): DocumentState
    {
        return $this->state;
    }

    /**
     * @param DocumentState $state
     *
     * @return $this
     */
    public function setState(DocumentState $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getUploadLink(): string
    {
        return $this->uploadLink;
    }

    /**
     * @param string $uploadLink
     *
     * @return $this
     */
    public function setUploadLink(string $uploadLink): self
    {
        $this->uploadLink = $uploadLink;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUploadDate(): \DateTime
    {
        return $this->uploadDate;
    }

    /**
     * @param \DateTime $uploadDate
     *
     * @return $this
     */
    public function setUploadDate(\DateTime $uploadDate): self
    {
        $this->uploadDate = $uploadDate;

        return $this;
    }
}