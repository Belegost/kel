<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 05.02.2018
 * Time: 16:55
 */
namespace App\Service;

class Token {
    private $tkid;

    public function __construct($tkid) {
        $this->tkid = $tkid;
    }

    /**
     * @param $data
     * @param int $expires in hours
     * @return string
     */
    public function generate($data, int $expires) {
        $serialized = serialize([
            $data,
            (new \DateTime("+{$expires} minutes"))->getTimestamp(),
            $this->tkid
        ]);

        return base64_encode($serialized);
    }

    public function extractData($token) {
        $serialized = base64_decode($token);
        list($data, $expires, $tkid) = unserialize($serialized);

        if (empty($expires) || empty($tkid)) {
            return false;
        } else if ($this->tkid !== $tkid) {
            return false;
        } else if ($expires < (new \DateTime)->getTimestamp()) {
            return false;
        }

        return $data;
    }
}