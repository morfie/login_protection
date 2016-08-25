<?php

namespace Docler\UserBundle\BruteforceDefense;

use Docler\UserBundle\BruteforceDefense\Descriptor\UserIdentifierDescriptor;
use Docler\UserBundle\BruteforceDefense\Storage\StorageInterface;

/**
 * brute force counter
 */
class BruteforceCounter {

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage) {
        $this->storage = $storage;
    }

    /**
     * @param UserIdentifierDescriptor $descriptor
     */
    public function increase(UserIdentifierDescriptor $descriptor) {
        $this->storage->add($descriptor->getUsername(), $descriptor->getIpv4Address());
    }

    /**
     * @param UserIdentifierDescriptor $descriptor
     *
     * @return bool
     */
    public function isBlocked(UserIdentifierDescriptor $descriptor) {

        if ( 3 <= $this->storage->countByUsername($descriptor->getUsername()) ) {
            return true;
        }
        if ( 3 <= $this->storage->countByIpv4Address($descriptor->getIpv4Address()) ) {
            return true;
        }
        if ( 500 <= $this->storage->countByIpv4AddressPer24($descriptor->getIpv4Address()) ) {
            return true;
        }
        if ( 1000 <= $this->storage->countByIpv4AddressPer16($descriptor->getIpv4Address()) ) {
            return true;
        }

        return false;
    }
}