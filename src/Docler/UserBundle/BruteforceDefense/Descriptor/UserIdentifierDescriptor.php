<?php

namespace Docler\UserBundle\BruteforceDefense\Descriptor;

/**
 * user identifier
 */
interface UserIdentifierDescriptor {

    /**
     * @return string
     */
    public function getUsername(): string;

    /**
     * @return string
     */
    public function getIpv4Address(): string;
}