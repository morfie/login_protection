<?php

namespace Docler\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DoclerUserBundle extends Bundle {

    public function getParent() {
        return 'FOSUserBundle';
    }
}
