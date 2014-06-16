<?php

namespace Cib\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CibUserBundle extends Bundle
{

    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
