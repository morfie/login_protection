<?php

namespace Docler\UserBundle\BruteforceDefense\Descriptor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\ParameterBagUtils;

/**
 * request wrapper
 */
class RequestUserIdentifierWrapper implements UserIdentifierDescriptor {

    /**
     * @var Request
     */
    private $request;

    /**
     * RequestUserIdentifierWrapper constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getUsername(): string {
        return trim(ParameterBagUtils::getRequestParameterValue($this->request, '_username'));
    }

    /**
     * @return string
     */
    public function getIpv4Address(): string {
        return $this->request->getClientIp();
    }

    /**
     * @param Request $request
     *
     * @return RequestUserIdentifierWrapper
     */
    public static function createByRequest(Request $request) {
        return new self($request);
    }
}