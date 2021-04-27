<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 05.05.2018
 * Time: 22:12
 */

namespace App\Lib;


use function Symfony\Component\Debug\Tests\testHeader;

class Assets {
    private $rebalance_by;
    private $rebalance_time;
    private $modify_by;
    private $modify_time;
    private $list = [];

    public function __construct(array $list, int $managerId = null) {
        $dateTime = new \DateTime();
        $this->rebalance_time = $dateTime;
        $this->rebalance_by = $managerId;
        $this->modify_time = $dateTime;
        $this->modify_by = $managerId;

        foreach ($list as $code => $weight) {
            $this->addAsset($code, floatval($weight), $dateTime, $managerId);
        }
    }

    private function addAsset(string $code, float $weight, \DateTime $time, int $managerId = null): Assets {
        if (!isset($this->list[$code])) {
            $this->list[$code] = [
                'weight' => $weight,
                'modify_by' => $managerId,
                'modify_time' => $time
            ];
        }
        return $this;
    }

    public function updateAsset(string $code, float $weight, int $managerId = null): Assets {
        if (isset($this->list[$code]) && $this->list[$code]['weight'] !== $weight) {
            $dateTime = new \DateTime();
            $this->modify_by = isset($managerId) ? $managerId : $this->modify_by;
            $this->modify_time = $dateTime;

            $this->list[$code]['weight'] = $weight;
            $this->list[$code]['modify_by'] = isset($managerId) ? $managerId : $this->list[$code]['modify_by'];
            $this->list[$code]['modify_time'] = $dateTime;
        }
        return $this;
    }

    public function canRebalance() {
        if (!($this->getRebalanceTime() instanceof \DateTime)) {
            return false;
        }

        return $this->getRebalanceTime()->getTimestamp() !== $this->getModifyTime()->getTimestamp();
    }

    /**
     * @return mixed
     */
    public function getRebalanceBy(): ?int {
        return $this->rebalance_by;
    }

    /**
     * @return mixed
     */
    public function getRebalanceTime(): ?\DateTime {
        return $this->rebalance_time;
    }

    /**
     * @param \DateTime $rebalanceTime
     * @param int $managerId = null
     * @return Assets
     */
    public function updateRebalance(\DateTime $rebalanceTime, int $managerId = null): Assets {
        $this->rebalance_by = isset($managerId) ? $managerId : $this->rebalance_by;
        $this->rebalance_time = $rebalanceTime;
        $this->modify_time = $rebalanceTime;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModifyBy() {
        return $this->modify_by;
    }

    /**
     * @return \DateTime
     */
    public function getModifyTime(): \DateTime {
        return $this->modify_time;
    }

    public function genRabalanceList() {
        if ($this->getRebalanceTime() instanceof \DateTime) {
            foreach ($this->list as $code => $data) {
                /** @var \DateTime $update_time */
                $update_time = $data['update_time'];

                if ($update_time->getTimestamp() === $this->getRebalanceTime()->getTimestamp()) {
                    yield $code => $data['weight'];
                }
            }
        }
    }

    public function genFullList() {
        foreach ($this->list as $code => $data) {
            yield $code => $data['weight'];
        }
    }

    public function getCount() {
        return count($this->list);
    }

    public function getTotalWeight() {
        $weight = 0;
        foreach ($this->genFullList() as $w) {
            $weight += $w;
        }
        return $weight;
    }

    public function getCodeList() {
        return array_keys($this->list);
    }

    public function getWeightList() {
        $wl = [];
        foreach ($this->genFullList() as $w) {
            $wl [] = $w;
        }
        return $wl;
    }

    public function getAssetInfo(string $code): ?array {
        return isset($this->list[$code]) ? $this->list[$code] : null;
    }
}