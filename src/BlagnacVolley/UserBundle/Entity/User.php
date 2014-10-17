<?php

namespace BlagnacVolley\UserBundle\Entity;

use FOS\UserBundle\Entity\User as EntityUser;
use Doctrine\ORM\Mapping as ORM;
use BlagnacVolley\TeamBundle\Entity\Team;

/**
 * User
 *
 * @ORM\Table(name="bv_user")
 * @ORM\Entity(repositoryClass="BlagnacVolley\UserBundle\Entity\UserRepository")
 */
class User extends EntityUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=255)
     */
    protected $gender;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dob", type="datetime")
     */
    protected $dob;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=512)
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     */
    protected $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="shirt_size", type="string", length=5)
     */
    protected $shirtSize;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_required_bill", type="boolean")
     */
    protected $isRequiredBill;

    /**
     * @var integer
     *
     * @ORM\OneToOne(targetEntity="BlagnacVolley\TeamBundle\Entity\Team", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="msc_team_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $mscTeam;

    /**
     * @var integer
     *
     * @ORM\OneToOne(targetEntity="BlagnacVolley\TeamBundle\Entity\Team", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="fem_team_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $femTeam;

    /**
     * @var integer
     *
     * @ORM\OneToOne(targetEntity="BlagnacVolley\TeamBundle\Entity\Team", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="mix_team_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $mixTeam;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_looking_for_team", type="boolean")
     */
    protected $isLookingForTeam;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", length=255)
     */
    protected $level;

    /**
     * @var string
     *
     * @ORM\Column(name="geo_lat", type="string", length=255)
     */
    protected $geoLat;

    /**
     * @var float
     *
     * @ORM\Column(name="geo_lng", type="float")
     */
    protected $geoLng;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="licence_number", type="string", length=255)
     */
    protected $licenceNumber;

    /**
     * @var float
     *
     * @ORM\Column(name="fee_amount", type="float")
     */
    protected $feeAmount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_payment", type="datetime")
     */
    protected $datePayment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_shirt_delivered", type="datetime")
     */
    protected $dateShirtDelivered;

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
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set dob
     *
     * @param \DateTime $dob
     * @return User
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob
     *
     * @return \DateTime 
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return User
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set shirtSize
     *
     * @param string $shirtSize
     * @return User
     */
    public function setShirtSize($shirtSize)
    {
        $this->shirtSize = $shirtSize;

        return $this;
    }

    /**
     * Get shirtSize
     *
     * @return string 
     */
    public function getShirtSize()
    {
        return $this->shirtSize;
    }

    /**
     * Set isRequiredBill
     *
     * @param boolean $isRequiredBill
     * @return User
     */
    public function setIsRequiredBill($isRequiredBill)
    {
        $this->isRequiredBill = $isRequiredBill;

        return $this;
    }

    /**
     * Get isRequiredBill
     *
     * @return boolean 
     */
    public function getIsRequiredBill()
    {
        return $this->isRequiredBill;
    }

    /**
     * Set mscTeam
     *
     * @param Team $mscTeam
     * @return $this
     */
    public function setMscTeam($mscTeam)
    {
        $this->mscTeam = $mscTeam;

        return $this;
    }

    /**
     * Get mscTeam
     *
     * @return Team
     */
    public function getMscTeam()
    {
        return $this->mscTeam;
    }

    /**
     * Set femTeamId
     *
     * @param Team $femTeam
     * @return $this
     */
    public function setFemTeam($femTeam)
    {
        $this->femTeam = $femTeam;

        return $this;
    }

    /**
     * Get femTeamId
     *
     * @return Team
     */
    public function getFemTeam()
    {
        return $this->femTeam;
    }

    /**
     * Set mixTeamId
     *
     * @param Team $mixTeam
     * @return $this
     */
    public function setMixTeam($mixTeam)
    {
        $this->mixTeam = $mixTeam;

        return $this;
    }

    /**
     * Get mixTeamId
     *
     * @return Team
     */
    public function getMixTeam()
    {
        return $this->mixTeam;
    }

    /**
     * Set isLookingForTeam
     *
     * @param boolean $isLookingForTeam
     * @return User
     */
    public function setIsLookingForTeam($isLookingForTeam)
    {
        $this->isLookingForTeam = $isLookingForTeam;

        return $this;
    }

    /**
     * Get isLookingForTeam
     *
     * @return boolean 
     */
    public function getIsLookingForTeam()
    {
        return $this->isLookingForTeam;
    }

    /**
     * Set level
     *
     * @param string $level
     * @return User
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
     * Set geoLat
     *
     * @param string $geoLat
     * @return User
     */
    public function setGeoLat($geoLat)
    {
        $this->geoLat = $geoLat;

        return $this;
    }

    /**
     * Get geoLat
     *
     * @return string 
     */
    public function getGeoLat()
    {
        return $this->geoLat;
    }

    /**
     * Set geoLng
     *
     * @param float $geoLng
     * @return User
     */
    public function setGeoLng($geoLng)
    {
        $this->geoLng = $geoLng;

        return $this;
    }

    /**
     * Get geoLng
     *
     * @return float 
     */
    public function getGeoLng()
    {
        return $this->geoLng;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set licenceNumber
     *
     * @param string $licenceNumber
     * @return User
     */
    public function setLicenceNumber($licenceNumber)
    {
        $this->licenceNumber = $licenceNumber;

        return $this;
    }

    /**
     * Get licenceNumber
     *
     * @return string 
     */
    public function getLicenceNumber()
    {
        return $this->licenceNumber;
    }

    /**
     * Set feeAmount
     *
     * @param float $feeAmount
     * @return User
     */
    public function setFeeAmount($feeAmount)
    {
        $this->feeAmount = $feeAmount;

        return $this;
    }

    /**
     * Get feeAmount
     *
     * @return float 
     */
    public function getFeeAmount()
    {
        return $this->feeAmount;
    }

    /**
     * Set datePayment
     *
     * @param \DateTime $datePayment
     * @return User
     */
    public function setDatePayment($datePayment)
    {
        $this->datePayment = $datePayment;

        return $this;
    }

    /**
     * Get datePayment
     *
     * @return \DateTime 
     */
    public function getDatePayment()
    {
        return $this->datePayment;
    }

    /**
     * Set dateShirtDelivered
     *
     * @param \DateTime $dateShirtDelivered
     * @return User
     */
    public function setDateShirtDelivered($dateShirtDelivered)
    {
        $this->dateShirtDelivered = $dateShirtDelivered;

        return $this;
    }

    /**
     * Get dateShirtDelivered
     *
     * @return \DateTime 
     */
    public function getDateShirtDelivered()
    {
        return $this->dateShirtDelivered;
    }

    function __construct()
    {
        parent::__construct();
    }
}
