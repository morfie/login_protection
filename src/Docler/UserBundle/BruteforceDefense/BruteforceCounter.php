<?php

namespace Docler\UserBundle\BruteforceDefense;

use Symfony\Component\HttpFoundation\Request;

/**
 * brute force counter
 */
class BruteforceCounter {

    /**
     * @param Request $request
     */
    public function increase(Request $request) {
        // @todod request helyett interface
        $i = 0;
        $i++;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function isBlocked(Request $request) {
        return true;
    }
}