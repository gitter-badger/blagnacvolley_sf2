<?php

namespace BV\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Availability
 *
 * @ORM\Table(name="bv_availability")
 * @ORM\Entity(repositoryClass="BV\FrontBundle\Entity\AvailabilityRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Availability
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_available", type="boolean")
     */
    private $isAvailable;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="validated_at", type="datetime")
     */
    private $validatedAt;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="BV\FrontBundle\Entity\User", inversedBy="availability")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="BV\FrontBundle\Entity\Events", inversedBy="availability")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=false)
     */
    protected $event;

    /**
     * Set isAvailable
     *
     * @param boolean $isAvailable
     * @return Availability
     */
    public function setIsAvailable($isAvailable)
    {
        $this->isAvailable = $isAvailable;
    
        return $this;
    }

    /**
     * Get isAvailable
     *
     * @return boolean 
     */
    public function getIsAvailable()
    {
        return $this->isAvailable;
    }

    /**
     * Set validatedAt
     *
     * @param \DateTime $validatedAt
     * @return Availability
     */
    public function setValidatedAt($validatedAt)
    {
        $this->validatedAt = $validatedAt;
    
        return $this;
    }

    /**
     * Get validatedAt
     *
     * @return \DateTime 
     */
    public function getValidatedAt()
    {
        return $this->validatedAt;
    }

    /**
     * Set user
     *
     * @param \BV\FrontBundle\Entity\User $user
     * @return Availability
     */
    public function setUser(\BV\FrontBundle\Entity\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \BV\FrontBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set event
     *
     * @param \BV\FrontBundle\Entity\Events $event
     * @return Availability
     */
    public function setEvent(\BV\FrontBundle\Entity\Events $event)
    {
        $this->event = $event;
    
        return $this;
    }

    /**
     * Get event
     *
     * @return \BV\FrontBundle\Entity\Events 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->validatedAt = (new \DateTime);
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->validatedAt = (new \DateTime);
    }
}
