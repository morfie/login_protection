<?php

namespace Docler\UserBundle\Event\Listener;

use Docler\UserBundle\BruteforceDefense\Counter;
use Docler\UserBundle\Exception\InvalidCaptchaException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;

/**
 * failure listener
 */
class AuthenticationFailureListener {

    /**
     * @var Counter
     */
    private $counter;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param Counter      $counter
     * @param RequestStack $requestStack
     */
    public function __construct(Counter $counter, RequestStack $requestStack) {
        $this->counter = $counter;
        $this->requestStack = $requestStack;
    }

    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onFail(AuthenticationFailureEvent $event) {
        if (!$event->getAuthenticationException() instanceof InvalidCaptchaException) {
            $this->counter->increase($this->requestStack->getMasterRequest());
        }
    }
}