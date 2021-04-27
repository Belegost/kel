<?php

namespace App\Entity\Integrity;

use App\Repository\Integrity\SumSubReviewRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SumSubReviewRepository::class)
 * @ORM\Table(name="sum_sub_reviews")
 */
class SumSubReview
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="sumSubReviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $applicantId;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $inspectionId;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $corellationId;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $reviewResult;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $moderationComment;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $clientComment;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $rejectLabels = [];

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getApplicantId(): ?string
    {
        return $this->applicantId;
    }

    public function setApplicantId(string $applicantId): self
    {
        $this->applicantId = $applicantId;

        return $this;
    }

    public function getInspectionId(): ?string
    {
        return $this->inspectionId;
    }

    public function setInspectionId(string $inspectionId): self
    {
        $this->inspectionId = $inspectionId;

        return $this;
    }

    public function getCorellationId(): ?string
    {
        return $this->corellationId;
    }

    public function setCorellationId(string $corellationId): self
    {
        $this->corellationId = $corellationId;

        return $this;
    }

    public function getReviewResult(): ?string
    {
        return $this->reviewResult;
    }

    public function setReviewResult(string $reviewResult): self
    {
        $this->reviewResult = $reviewResult;

        return $this;
    }

    public function getModerationComment(): ?string
    {
        return $this->moderationComment;
    }

    public function setModerationComment(?string $moderationComment): self
    {
        $this->moderationComment = $moderationComment;

        return $this;
    }

    public function getClientComment(): ?string
    {
        return $this->clientComment;
    }

    public function setClientComment(?string $clientComment): self
    {
        $this->clientComment = $clientComment;

        return $this;
    }

    public function getRejectLabels(): ?array
    {
        return $this->rejectLabels;
    }

    public function setRejectLabels(?array $rejectLabels): self
    {
        $this->rejectLabels = $rejectLabels;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
