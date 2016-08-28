<?php

namespace Docler\UserBundle\Util;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\ParameterBagUtils;

/**
 * captcha validator
 */
class CaptchaValidator {

    /**
     * @param Request $request
     *
     * @return bool
     */
    public static function isValid(Request $request): bool {
        $userCaptcha = ParameterBagUtils::getRequestParameterValue($request, '_captcha');
        $dummy = $request->getSession()->get('gcb__captcha');
        $sessionCaptcha = isset($dummy['phrase']) ? $dummy['phrase'] : NULL;
        return $userCaptcha === $sessionCaptcha;
    }
}