<?php

namespace Docler\UserBundle\BruteforceDefense\Storage;


/**
 * Brute force defense storage interface
 */
interface StorageInterface {

    /**
     * @param string $username
     * @param string $ipv4Address
     */
    public function add(string $username, string $ipv4Address);

    /**
     * @param string $username
     *
     * @return int
     */
    public function countByUsername(string $username): int;

    /**
     * @param string $ipv4Address
     *
     * @return int
     */
    public function countByIpv4Address(string $ipv4Address): int;

    /**
     * @param string $ipv4Address
     *
     * @return int
     */
    public function countByIpv4AddressPer24(string $ipv4Address): int;

    /**
     * @param string $ipv4Address
     *
     * @return int
     */
    public function countByIpv4AddressPer16(string $ipv4Address): int;
}