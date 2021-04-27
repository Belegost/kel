<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 26.01.2018
 * Time: 22:51
 */

namespace App\Service;


class PasswordEncoder {
    private $cost;

    public function __construct($cost = 12) {
        $this->cost = ['cost' => $cost];
    }

    public function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, $this->cost);
    }

    public function isValidPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public function generateRaw($length = 8) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }
}