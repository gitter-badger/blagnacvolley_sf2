<?php

namespace BV\FrontBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Team
 *
 * @ORM\Table(name="bv_team")
 * @ORM\Entity(repositoryClass="BV\FrontBundle\Entity\Repository\TeamRepository")
 */
class Team
{
    /*******************************************************************************************************************
     *
     *  Constants
     *
     */

    const TYPE_FEM = 'Feminin';
    const TYPE_MSC = 'Masculin';
    const TYPE_MIX = 'Mixte';

    /*******************************************************************************************************************
     *
     *  Attributes
     *
     */

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="BV\FrontBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="captain_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $captain;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="BV\FrontBundle\Entity\User", cascade={"persist"})
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
     * @ORM\Column(name="slot", type="string", length=255, nullable=true)
     */
    private $slot;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BV\FrontBundle\Entity\User", mappedBy="femTeam", cascade={"persist"})
     */
    private $membersFem;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BV\FrontBundle\Entity\User", mappedBy="mscTeam", cascade={"persist"})
     */
    private $membersMsc;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BV\FrontBundle\Entity\User", mappedBy="mixTeam", cascade={"persist"})
     */
    private $membersMix;

    /*******************************************************************************************************************
     *
     *  Custom functions
     *
     */

    public function __construct()
    {
        $this->membersFem = new ArrayCollection();
        $this->membersMsc = new ArrayCollection();
        $this->membersMix = new ArrayCollection();
    }

    /*******************************************************************************************************************
     *
     *  Auto-generated functions : php app/console doctrine:generate:entities FrontBundle:Team
     *
     */

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

    public function setMembersFem($membersFem)
    {
        if (count($membersFem) > 0) {
            foreach ($membersFem as $i) {
                $this->addMembersFem($i);
            }
        }

        return $this;
    }

    public function setMembersMix($membersMix)
    {
        if (count($membersMix) > 0) {
            foreach ($membersMix as $i) {
                $this->addMembersMix($i);
            }
        }

        return $this;
    }

    public function setMembersMsc($membersMsc)
    {
        if (count($membersMsc) > 0) {
            foreach ($membersMsc as $i) {
                $this->addMembersMsc($i);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMembersFem()
    {
        return $this->membersFem;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addMembersFem(User $user)
    {
        $this->membersFem->add($user);
        $user->setFemTeam($this);

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function removeMembersFem(User $user)
    {
        $this->membersFem->remove($user->getId());
        $user->setFemTeam(null);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMembersMix()
    {
        return $this->membersMix;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addMembersMix(User $user)
    {
        $this->membersMix->add($user);
        $user->setMixTeam($this);

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function removeMembersMix(User $user)
    {
        $this->membersMix->remove($user->getId());
        $user->setMixTeam(null);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMembersMsc()
    {
        return $this->membersMsc;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addMembersMsc(User $user)
    {
        $this->membersMsc->add($user);
        $user->setMscTeam($this);

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function removeMembersMsc(User $user)
    {
        $this->membersMsc->remove($user->getId());
        $user->setMscTeam(null);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName().' ('.$this->getLevel().')';
    }
}
