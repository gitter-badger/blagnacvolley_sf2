<?php

namespace BV\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use BV\FrontBundle\Entity\Events;

class Vacations extends Events
{
    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return self::TYPE_CLOSED;
    }
}
