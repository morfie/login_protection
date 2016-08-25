<?php

namespace Docler\UserBundle\BruteforceDefense\Storage;


/**
 * Brute force defense storage interface
 */
interface StorageInterface {

    /**
     * @param $username
     * @param $ipv4Address
     */
    public function add($username, $ipv4Address);

    /**
     * @param $username
     *
     * @return int
     */
    public function countByUsername($username): int;

    /**
     * @param $ipv4Address
     *
     * @return int
     */
    public function countByIpv4Address($ipv4Address): int;

    /**
     * @param $ipv4Address
     *
     * @return int
     */
    public function countByIpv4AddressPer24($ipv4Address): int;

    /**
     * @param $ipv4Address
     *
     * @return int
     */
    public function countByIpv4AddressPer16($ipv4Address): int;
}