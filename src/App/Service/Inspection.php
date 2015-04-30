<?php

namespace App\Service;

/**
 * Class Inspection.
 */
class Inspection
{
    const IP = 'ip';
    const MAC = 'mac';
    const PORTS = 'ports';
    const ALL = 'all';

    /**
     * @param string $inspect
     * @param string $field
     *
     * @return array
     */
    public function get($inspect, $field)
    {
        $array = json_decode($inspect);
        $inspect = array_pop($array);

        switch ($field) {
            case self::ALL:
                return [
                    self::IP => $this->getIp($inspect),
                    self::MAC => $this->getMac($inspect),
                    self::PORTS => $this->getPorts($inspect),
                ];
            case self::IP:
                return [$field => $this->getIp($inspect)];
            case self::MAC:
                return [$field => $this->getMac($inspect)];
            case self::PORTS:
                return [$field => $this->getPorts($inspect)];
        }

        return [];
    }

    /**
     * @param \StdClass $inspect
     *
     * @return string
     */
    public function getIp(\StdClass $inspect)
    {
        if (!isset($inspect->NetworkSettings) || empty($inspect->NetworkSettings->IPAddress)) {
            return;
        }

        return $inspect->NetworkSettings->IPAddress;
    }

    /**
     * @param \StdClass $inspect
     *
     * @return string
     */
    public function getMac(\StdClass $inspect)
    {
        if (!isset($inspect->NetworkSettings) || empty($inspect->NetworkSettings->MacAddress)) {
            return;
        }

        return $inspect->NetworkSettings->MacAddress;
    }

    /**
     * @param \StdClass $inspect
     *
     * @return array
     */
    public function getPorts(\StdClass $inspect)
    {
        if (!isset($inspect->NetworkSettings) || !isset($inspect->NetworkSettings->Ports)) {
            return [];
        }

        $ports = [];
        foreach ($inspect->NetworkSettings->Ports as $port => $data) {
            if (null === $data) {
                $ports[] = $port;
            } else {
                $ports[] = sprintf('%s -> %s:%s', $port, $data[0]->HostIp, $data[0]->HostPort);
            }
        }

        return $ports;
    }
}
