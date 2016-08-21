<?php

namespace Docler\UserBundle\BruteforceDefense;

use Symfony\Component\HttpFoundation\Request;

/**
 * brute force counter
 */
class Counter {

    /**
     * @param Request $request
     */
    public function increase(Request $request) {
        $i = 0;
        $i++;
    }
}