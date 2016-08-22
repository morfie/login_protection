<?php

namespace Docler\UserBundle\Exception;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * invalid captcha
 */
class InvalidCaptchaException extends BadCredentialsException {

    public function getMessageKey() {
        return 'Invalid captcha.';
    }
}