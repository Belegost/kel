<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 24.06.2018
 * Time: 11:17
 */

namespace App;

use Psr\Log\LoggerInterface;
use Predis\ClientInterface;

trait ServiceAwareTrait {
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ClientInterface
     */
    private $redis;

    public function setLogger(LoggerInterface $logger = null) {
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): ?LoggerInterface {
        return $this->logger;
    }

    /**
     * @return ClientInterface
     */
    public function getRedis(): ?ClientInterface {
        return $this->redis;
    }

    /**
     * @param ClientInterface $redis
     */
    public function setRedis(ClientInterface $redis = null) {
        $this->redis = $redis;
    }
}