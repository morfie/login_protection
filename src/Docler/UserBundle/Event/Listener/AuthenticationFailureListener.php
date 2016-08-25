<?php

namespace Docler\UserBundle\Event\Listener;

use Docler\UserBundle\BruteforceDefense\BruteforceCounter;
use Docler\UserBundle\BruteforceDefense\Descriptor\RequestUserIdentifierWrapper;
use Docler\UserBundle\Exception\InvalidCaptchaException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;

/**
 * failure listener
 */
class AuthenticationFailureListener {

    /**
     * @var BruteforceCounter
     */
    private $counter;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param BruteforceCounter $counter
     * @param RequestStack      $requestStack
     */
    public function __construct(BruteforceCounter $counter, RequestStack $requestStack) {
        $this->counter = $counter;
        $this->requestStack = $requestStack;
    }

    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onFail(AuthenticationFailureEvent $event) {
        if (!$event->getAuthenticationException() instanceof InvalidCaptchaException) {
            $this->counter->increase(RequestUserIdentifierWrapper::createByRequest($this->requestStack->getMasterRequest()));
        }
    }
}