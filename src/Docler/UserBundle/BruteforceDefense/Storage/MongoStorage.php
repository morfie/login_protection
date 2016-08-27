<?php

namespace Docler\UserBundle\BruteforceDefense\Storage;

use Docler\UserBundle\Document\LoginFailLogEntry;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * mongo storage
 */
class MongoStorage implements StorageInterface {

    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * MongoStorage constructor.
     */
    public function __construct(ManagerRegistry $managerRegistry){
        $this->documentManager = $managerRegistry->getManager();
    }

    /**
     * {@inheritDoc}
     */
    public function add(string $username, string $ipv4Address) {
        $logEntry = $this->createNewEntry($username, $ipv4Address);
        $this->persist($logEntry);
    }

    /**
     * {@inheritDoc}
     */
    public function countByUsername(string $username): int {
        return $this->countByFieldExpression('userName', $username);
    }

    /**
     * {@inheritDoc}
     */
    public function countByIpv4Address(string $ipv4Address): int {
        return $this->countByFieldExpression('ipv4Address', $ipv4Address);
    }

    /**
     * {@inheritDoc}
     */
    public function countByIpv4AddressPer24(string $ipv4Address): int {
        return $this->countByFieldExpression('ipv4Address', $this->subnetIpRegexp($ipv4Address, 3));
    }

    /**
     * {@inheritDoc}
     */
    public function countByIpv4AddressPer16(string $ipv4Address): int {
        return $this->countByFieldExpression('ipv4Address', $this->subnetIpRegexp($ipv4Address, 2));
    }

    /**
     * @param string $username
     * @param string $ipv4Address
     *
     * @return LoginFailLogEntry
     */
    protected function createNewEntry(string $username, string $ipv4Address) {
        $logEntry = new LoginFailLogEntry();
        $logEntry->setUserName($username);
        $logEntry->setIpv4Address($ipv4Address);

        return $logEntry;
    }

    /**
     * @param LoginFailLogEntry $logEntry
     */
    protected function persist(LoginFailLogEntry $logEntry) {
        $dm = $this->documentManager;
        $dm->persist($logEntry);
        $dm->flush();
    }

    /**
     * @param string $field
     * @param mixed  $expression
     *
     * @return int
     */
    protected function countByFieldExpression(string $field, $expression): int {
        $result = $this->documentManager->createQueryBuilder(LoginFailLogEntry::class)
            ->group([], ['count' => 0])
            ->reduce('function (obj, prev) { prev.count++; }')
            ->field($field)->equals($expression)
            ->getQuery()
            ->execute();

        return (int) $result->getSingleResult()['count'];
    }

    /**
     * @param string $ipv4Address
     * @param int    $range
     *
     * @return \MongoRegex
     */
    protected function subnetIpRegexp(string $ipv4Address, int $range): \MongoRegex {
        $ipPrefix = explode('.', $ipv4Address);
        $ipPrefix = implode('.', array_slice($ipPrefix, 0, $range)) . '.';

        return new \MongoRegex(sprintf('/^%s/', $ipPrefix));
    }
}