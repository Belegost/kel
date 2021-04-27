<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 13.02.2018
 * Time: 01:01
 */

namespace App\Service;

use Iso3166\Codes;
use GuzzleHttp\Client as GuzzleClient;

class Country {
    private $city;
    private $name;
    private $state;
    private $code;
    private $ip;
    private $timezone;
    private $zip;

    /**
     * Country constructor.
     */
    public function __construct() {
        $guzzle = new GuzzleClient();
        $response = $guzzle->get('http://ip-api.com/json');

        if ($response->getStatusCode() == 200) {
            $result = json_decode($response->getBody(), true);
            if (json_last_error() == JSON_ERROR_NONE) {
                $this->city = $result['city'];
                $this->name = $result['country'];
                $this->state = $result['regionName'];
                $this->code = $result['countryCode'];
                $this->ip = $result['query'];
                $this->timezone = $result['timezone'];
                $this->zip = $result['zip'];
            }
        }
    }

    /**
     * @return mixed
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getState() {
        return $this->state;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * @return mixed
     */
    public function getTimezone() {
        return $this->timezone;
    }

    /**
     * @return mixed
     */
    public function getZip() {
        return $this->zip;
    }

    public function getNameByISOCode($iso_code) {
        return Codes::country($iso_code);
    }

    public function getPhoneCodeByISOCode($iso_code) {
        return Codes::phoneCode($iso_code);
    }

    public function getCountriesList() {
        return Codes::$countries;
    }
}