<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 08.05.2018
 * Time: 22:46
 */

namespace App\Request;

use App\Lib\XMLHttpRequest;

class APIDummy extends XMLHttpRequest {
    private $CLIENT_PUBLIC_KEY;
    private $CLIENT_CODE;

    protected function validate() {
        if ($this->CLIENT_PUBLIC_KEY != getenv('CLIENT_PUBLIC_KEY')) {
            $this->addError('Invalid the client public key');
        }

        if ($this->CLIENT_CODE != getenv('CLIENT_CODE')) {
            $this->addError('Illegal client code');
        }
    }

    /**
     * @return mixed
     */
    public function getClientPublicKey() {
        return $this->CLIENT_PUBLIC_KEY;
    }

    /**
     * @param mixed $key
     */
    public function setClientPublicKey($key): void {
        $this->CLIENT_PUBLIC_KEY = $key;
    }

    /**
     * @return mixed
     */
    public function getClientCode() {
        return $this->CLIENT_CODE;
    }

    /**
     * @param mixed $code
     */
    public function setClientCode($code): void {
        $this->CLIENT_CODE = $code;
    }
}