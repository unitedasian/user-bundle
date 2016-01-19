<?php

namespace UAM\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class UAMUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
