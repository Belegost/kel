<?php

namespace App\Entity\Zoho;

class Ticket extends ZohoEntity {

    protected function build() {
        $this['Lead Source'] = 'Integrity Fund';
    }

    /**
     * @return string|null
     */
    public function getName() {
        return $this->offsetExists('Name') ? $this['Name'] : null;
    }

    /**
     * @param string $name
     *
     * @return Ticket
     */
    public function setName(string $name): Ticket {
        $this['Name'] = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail() {
        return $this->offsetExists('Email') ? $this['Email'] : null;
    }

    /**
     * @param string $email
     *
     * @return Ticket
     */
    public function setEmail(string $email): Ticket {
        $this['Email'] = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone() {
        return $this->offsetExists('Phone') ? $this['Phone'] : null;
    }

    /**
     * @param string $phone
     *
     * @return Ticket
     */
    public function setPhone(string $phone): Ticket {
        $this['Phone'] = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage() {
        return $this->offsetExists('Message') ? $this['Message'] : null;
    }

    /**
     * @param string $message
     *
     * @return Ticket
     */
    public function setMessage(string $message): Ticket {
        $this['Message'] = $message;

        return $this;
    }
}