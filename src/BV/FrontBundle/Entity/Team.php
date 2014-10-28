<?php

namespace BV\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 *
 * @ORM\Table(name="bv_team")
 * @ORM\Entity(repositoryClass="BV\FrontBundle\Entity\TeamRepository")
 */
class Team
{
    const TYPE_FEM = 'Feminin';
    const TYPE_MSC = 'Masculin';
    const TYPE_MIX = 'Mixte';

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
     * @ORM\OneToOne(targetEntity="BV\FrontBundle\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="captain_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $captain;

    /**
     * @var integer
     *
     * @ORM\OneToOne(targetEntity="BV\FrontBundle\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="sub_captain_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $subCaptain;

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
     * Set captain
     *
     * @param User $captain
     * @return Team
     */
    public function setCaptain($captain)
    {
        $this->captain = $captain;

        return $this;
    }

    /**
     * Get captain
     *
     * @return User
     */
    public function getCaptain()
    {
        return $this->captain;
    }

    /**
     * Set subCaptain
     *
     * @param User $subCaptain
     * @return Team
     */
    public function setSubCaptain($subCaptain)
    {
        $this->subCaptain = $subCaptain;

        return $this;
    }

    /**
     * Get subCaptain
     *
     * @return User
     */
    public function getSubCaptain()
    {
        return $this->subCaptain;
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
     * Returns the type list
     *
     * @return array
     */
    public static function getTypeList()
    {
        return array(
            self::TYPE_FEM => self::TYPE_FEM,
            self::TYPE_MSC => self::TYPE_MSC,
            self::TYPE_MIX => self::TYPE_MIX
        );
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

    function __toString()
    {
        return $this->getName().' ('.$this->getLevel().')';
    }
}
