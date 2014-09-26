<?php

namespace BlagnacVolley\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BlagnacVolleyUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
