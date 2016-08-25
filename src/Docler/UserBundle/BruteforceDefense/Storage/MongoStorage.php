<?php

namespace Docler\UserBundle\BruteforceDefense\Storage;

use Docler\UserBundle\Document\LoginFailLogEntry;
use Doctrine\ODM\MongoDB\DocumentManager;
use Solution\MongoAggregation\Pipeline\Operators\Expr;
use Solution\MongoAggregationBundle\AggregateQuery\AggregationQueryBuilder;

/**
 * mongo storage
 */
class MongoStorage implements StorageInterface {

    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @var AggregationQueryBuilder
     */
    private $aggregationQueryBuilder;

    /**
     * MongoStorage constructor.
     */
    public function __construct(DocumentManager $documentManager, AggregationQueryBuilder $aggregationQueryBuilder) {
        $this->documentManager = $documentManager;
        $this->aggregationQueryBuilder = $aggregationQueryBuilder;
    }

    /**
     * @param $username
     * @param $ipv4Address
     */
    public function add($username, $ipv4Address) {
        $logEntry = $this->createNewEntry($username, $ipv4Address);
        $this->persist($logEntry);
    }

    /**
     * @param $username
     *
     * @return int
     */
    public function countByUsername($username): int {
//        $collection = $this->documentManager->getDocumentCollection('DoclerUserBundle:LoginFailLogEntry');
//        $pipeline[] = [
//            '$match' => [ 'userName' => '/tes.*/' ],
//            '$group' => [
//                '_id' => '_id',
//                'count' => ['$sum' => 1]
//            ],
//        ];
//
//        $groups = $collection->aggregate($pipeline);
//        var_dump(iterator_to_array($groups));die;
//        return 1;
        $expr = new Expr();
        $aq = $this->aggregationQueryBuilder->getCollection('DoclerUserBundle:LoginFailLogEntry')->createAggregateQuery()
            ->group(['_id' => '_id', 'count' => $expr->sum(1)])
            ->match(['userName'=>'morfie@fsr.hu'])
        ;
        $result = $aq->getQuery()->aggregate();
        var_dump($result);die;
    }

    /**
     * @param $ipv4Address
     *
     * @return int
     */
    public function countByIpv4Address($ipv4Address): int {
        return 1;
    }

    /**
     * @param $ipv4Address
     *
     * @return int
     */
    public function countByIpv4AddressPer24($ipv4Address): int {
        // db.failed_logins.aggregate(    [ {$match: {"name": /tes.*/}},     { $group: { "_id": "_id", "count": { $sum: 1 } } }    ] );
        return 1;
    }

    /**
     * @param $ipv4Address
     *
     * @return int
     */
    public function countByIpv4AddressPer16($ipv4Address): int {
        return 1;
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
}