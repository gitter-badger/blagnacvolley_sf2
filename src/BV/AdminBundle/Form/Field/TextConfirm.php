<?php

namespace BV\AdminBundle\Form\Field;

use Symfony\Component\Form\AbstractType;

class TextConfirm extends AbstractType
{
    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'text_confirm';
    }
}