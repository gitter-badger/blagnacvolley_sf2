<?php

namespace BV\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="events")
 * @ORM\MappedSuperclass
 */
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
