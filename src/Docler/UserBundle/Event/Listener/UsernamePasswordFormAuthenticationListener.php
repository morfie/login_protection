<?php

namespace Docler\UserBundle\Event\Listener;

use Docler\UserBundle\BruteforceDefense\BruteforceCounter;
use Docler\UserBundle\Exception\InvalidCaptchaException;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Security\Http\ParameterBagUtils;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * login checker
 */
class UsernamePasswordFormAuthenticationListener extends AbstractAuthenticationListener {

    /**
     * @var NULL|CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    /**
     * @var BruteforceCounter
     */
    private $bruteforceCounter;

    /**
     * UsernamePasswordFormAuthenticationListener constructor.
     *
     * @param TokenStorageInterface                  $tokenStorage
     * @param AuthenticationManagerInterface         $authenticationManager
     * @param SessionAuthenticationStrategyInterface $sessionStrategy
     * @param HttpUtils                              $httpUtils
     * @param string                                 $providerKey
     * @param AuthenticationSuccessHandlerInterface  $successHandler
     * @param AuthenticationFailureHandlerInterface  $failureHandler
     * @param array                                  $options
     * @param LoggerInterface|NULL                   $logger
     * @param EventDispatcherInterface|NULL          $dispatcher
     * @param CsrfTokenManagerInterface|NULL         $csrfTokenManager
     */
    public function __construct(
        TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager,
        SessionAuthenticationStrategyInterface $sessionStrategy, HttpUtils $httpUtils, $providerKey,
        AuthenticationSuccessHandlerInterface $successHandler, AuthenticationFailureHandlerInterface $failureHandler,
        array $options = [], LoggerInterface $logger = NULL, EventDispatcherInterface $dispatcher = NULL,
        CsrfTokenManagerInterface $csrfTokenManager = NULL
    ) {
        parent::__construct($tokenStorage, $authenticationManager, $sessionStrategy, $httpUtils, $providerKey, $successHandler, $failureHandler, array_merge([
            'username_parameter' => '_username',
            'password_parameter' => '_password',
            'csrf_parameter'     => '_csrf_token',
            'csrf_token_id'      => 'authenticate',
            'post_only'          => TRUE,
        ], $options), $logger, $dispatcher);

        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * @param BruteforceCounter $bruteforceCounter
     */
    public function setBruteforceCounter(BruteforceCounter $bruteforceCounter) {
        $this->bruteforceCounter = $bruteforceCounter;
    }

    /**
     * {@inheritdoc}
     */
    protected function requiresAuthentication(Request $request) {
        if ($this->options['post_only'] && !$request->isMethod('POST')) {
            return FALSE;
        }

        return parent::requiresAuthentication($request);
    }

    /**
     * {@inheritdoc}
     */
    protected function attemptAuthentication(Request $request) {
        if (NULL !== $this->csrfTokenManager) {
            $csrfToken = ParameterBagUtils::getRequestParameterValue($request, $this->options['csrf_parameter']);

            if (FALSE === $this->csrfTokenManager->isTokenValid(new CsrfToken($this->options['csrf_token_id'], $csrfToken))) {
                throw new InvalidCsrfTokenException('Invalid CSRF token.');
            }
        }

        if ($this->options['post_only']) {
            $username = trim(ParameterBagUtils::getParameterBagValue($request->request, $this->options['username_parameter']));
            $password = ParameterBagUtils::getParameterBagValue($request->request, $this->options['password_parameter']);
        } else {
            $username = trim(ParameterBagUtils::getRequestParameterValue($request, $this->options['username_parameter']));
            $password = ParameterBagUtils::getRequestParameterValue($request, $this->options['password_parameter']);
        }

        if (strlen($username) > Security::MAX_USERNAME_LENGTH) {
            throw new BadCredentialsException('Invalid username.');
        }

        if ($this->bruteforceCounter->isBlocked($request)) {
            $userCaptcha = ParameterBagUtils::getRequestParameterValue($request, '_captcha');
            $dummy = $request->getSession()->get('gcb__captcha');
            $sessionCaptcha = isset($dummy['phrase']) ? $dummy['phrase'] : NULL;
            if ($userCaptcha !== $sessionCaptcha) {
                throw new InvalidCaptchaException('Captcha is invalid');
            }
        }

        $request->getSession()->set(Security::LAST_USERNAME, $username);

        return $this->authenticationManager->authenticate(new UsernamePasswordToken($username, $password, $this->providerKey));
    }
}
