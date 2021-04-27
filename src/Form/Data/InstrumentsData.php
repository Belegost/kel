<?php

namespace App\Form\Data;

use App\Entity\Integrity\Analytics\Instrument;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class InstrumentsData
 * @package App\Form\Data
 */
class InstrumentsData
{
    /**
     * @var mixed
     */
    private $instruments;

    /**
     * @return mixed
     */
    public function getInstruments()
    {
        return $this->instruments;
    }

    /**
     * @param mixed $instruments
     *
     * @return $this
     */
    public function setInstruments($instruments)
    {
        $this->instruments = $instruments;

        return $this;
    }

    /**
     * @param \App\Entity\Integrity\Analytics\Account $account
     */
    public function handleAccount(\App\Entity\Integrity\Analytics\Account $account)
    {
//        var_dump($this->instruments);
//        die();

        foreach ($this->instruments as $instrument) {
            $account->addInstrument($instrument);
        }
    }
}