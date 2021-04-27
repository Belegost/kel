<?php


namespace App\Traits\MRM;

use App\Service\Messenger\MRM AS MRMMessenger;
trait MessengerAwareTrait
{

    private MRMMessenger $mrmMessenger;

    /**
     * @return MRMMessenger
     */
    public function getMrmMessenger(): MRMMessenger
    {
        return $this->mrmMessenger;
    }

    /**
     * @param MRMMessenger $mrmMessenger
     */
    public function setMrmMessenger(MRMMessenger $mrmMessenger): void
    {
        $this->mrmMessenger = $mrmMessenger;
    }


}