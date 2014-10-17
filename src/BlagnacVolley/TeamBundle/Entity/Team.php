<?php

namespace BlagnacVolley\TeamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 *
 * @ORM\Table(name="bv_team")
 * @ORM\Entity(repositoryClass="BlagnacVolley\TeamBundle\Entity\TeamRepository")
 */
class Team
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\OneToOne(targetEntity="BlagnacVolley\UserBundle\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="captain_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $captainId;

    /**
     * @var integer
     *
     * @ORM\OneToOne(targetEntity="BlagnacVolley\UserBundle\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="sub_captain_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $subCaptainId;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", length=255)
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="slot", type="string", length=255)
     */
    private $slot;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set captainId
     *
     * @param integer $captainId
     * @return Team
     */
    public function setCaptainId($captainId)
    {
        $this->captainId = $captainId;

        return $this;
    }

    /**
     * Get captainId
     *
     * @return integer 
     */
    public function getCaptainId()
    {
        return $this->captainId;
    }

    /**
     * Set subCaptainId
     *
     * @param integer $subCaptainId
     * @return Team
     */
    public function setSubCaptainId($subCaptainId)
    {
        $this->subCaptainId = $subCaptainId;

        return $this;
    }

    /**
     * Get subCaptainId
     *
     * @return integer 
     */
    public function getSubCaptainId()
    {
        return $this->subCaptainId;
    }

    /**
     * Set level
     *
     * @param string $level
     * @return Team
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return string 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Team
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Team
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set slot
     *
     * @param string $slot
     * @return Team
     */
    public function setSlot($slot)
    {
        $this->slot = $slot;

        return $this;
    }

    /**
     * Get slot
     *
     * @return string 
     */
    public function getSlot()
    {
        return $this->slot;
    }

    function __construct()
    {

    }
}
