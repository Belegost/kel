<?php


namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

class FillProfileData
{

    /**
     * @Assert\NotBlank(message="Please fill a firstname.")
     * @Assert\Length(min="3")
     */
    protected ?string $firstname;

    /**
     * @Assert\NotBlank(message="Please fill a lastname.")
     * @Assert\Length(min="3")
     */
    protected ?string $lastname;

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string|null $firstname
     */
    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string|null $lastname
     */
    public function setLastname(?string $lastname): void
    {
        $this->lastname = $lastname;
    }



}
