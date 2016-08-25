<?php

namespace Docler\UserBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\HasLifecycleCallbacks
 */
class LoginFailLogEntry {

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     * @MongoDB\Index()
     */
    protected $userName;

    /**
     * @MongoDB\Field(type="string")
     * @MongoDB\Index()
     */
    protected $ipv4Address;

    /**
     * @MongoDB\Field(type="date")
     * @MongoDB\Index(expireAfterSeconds=300)
     */
    protected $createdAt;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return self
     */
    public function setUserName($userName) {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string $userName
     */
    public function getUserName() {
        return $this->userName;
    }

    /**
     * Set ipv4Address
     *
     * @param string $ipv4Address
     *
     * @return self
     */
    public function setIpv4Address($ipv4Address) {
        $this->ipv4Address = $ipv4Address;

        return $this;
    }

    /**
     * Get ipv4Address
     *
     * @return string $ipv4Address
     */
    public function getIpv4Address() {
        return $this->ipv4Address;
    }

    /**
     * Get createdAt
     *
     * @return date $createdAt
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /** @MongoDB\PrePersist */
    public function prePersist() {
        $this->createdAt = new \DateTime;
    }
}
