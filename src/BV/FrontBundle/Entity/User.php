<?php

namespace BV\FrontBundle\Entity;

use FOS\UserBundle\Entity\User as EntityUser;
use Doctrine\ORM\Mapping as ORM;
use BV\FrontBundle\Entity\Team;

/**
 * User
 *
 * Requis pour la création de compte :
 *
 * - Identifiant (Unique sur le site, qui sert à s'authentifier)
 * - Email, (mail)
 * - Mot de passe. (password)
 * - Genre, (gender)
 * - Nom, (lastname)
 * - Prénom, (firstname)
 * - Date de naissance, (dob)
 * - Adresse, (address)
 * - Tel portable, (phone)
 * - Photo, (picture)
 *
 * A Saisir par l'utilisateur par la suite :
 *
 * - Taille Maillot (shirt_size)
 * - Facture requise (is_required_bill)
 * - Equipe garçon (msc_team) avec l'équipe choisie.
 * - Equipe fille (fem_team) avec l'équipe choisie.
 * - Equipe mixte (mix_team) avec l'équipe choisie.
 * - Recherche une équipe (is_looking_for_team)
 * - Niveau (level) variant du plus faible au plus fort : (Promotion B, Promotion A, Honneur C, Honneur B, Honneur A, Excellence C, Excellence B, Excellence A)
 *
 * Calculées automatiquement à la création du compte :
 *
 * - Coordonnées GPS, calculées à partir de l'adresse (See google maps field)
 * - Statut (à passer à ACTIF_NON_LICENCIE à la création de compte) Autre statuts disponibles : INACTIF, ACTIF_LICENCIE.
 *
 * Entré par l'administrateur du club par la suite :
 *
 * - Numéro de License (licence_number)
 * - Montant Cotisation (fee_amount)
 * - Date paiement Cotisation (date_payment_fee)
 * - Date livraison maillot (date_shirt_delivered)
 * - Est capitaine (is_captain)
 * - Est suppléant (is_sub_captain)
 *
 * @ORM\Table(name="bv_user")
 * @ORM\Entity(repositoryClass="BV\FrontBundle\Entity\UserRepository")
 */
class User extends EntityUser
{
    const STATUS_ACTIVE_LICENCED = 'ACTIVE_LICENSED';
    const STATUS_ACTIVE_NOT_LICENCED = 'ACTIVE_NOT_LICENSED';
    const STATUS_INACTIVE = 'INACTIVE';

    const GENDER_MALE = 'MALE';
    const GENDER_FEMALE = 'FEMALE';

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
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    protected $lastname;

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
     * @ORM\Column(name="shirt_size", type="string", length=5, nullable=true)
     */
    protected $shirtSize;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_required_bill", type="boolean", options={"default": false})
     */
    protected $isRequiredBill = false;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="BV\FrontBundle\Entity\Team", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="msc_team_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $mscTeam;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="BV\FrontBundle\Entity\Team", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="fem_team_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $femTeam;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="BV\FrontBundle\Entity\Team", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="mix_team_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $mixTeam;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_looking_for_team", type="boolean", options={"default": false})
     */
    protected $isLookingForTeam = false;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="status", type="string", length=255, options={"default": "ACTIVE_NOT_LICENSED"})
     */
    protected $status = USER::STATUS_ACTIVE_NOT_LICENCED;

    /**
     * @var string
     *
     * @ORM\Column(name="licence_number", type="string", length=255, nullable=true)
     */
    protected $licenceNumber;

    /**
     * @var float
     *
     * @ORM\Column(name="fee_amount", type="float", nullable=true)
     */
    protected $feeAmount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_payment", type="datetime", nullable=true)
     */
    protected $datePayment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_shirt_delivered", type="datetime", nullable=true)
     */
    protected $dateShirtDelivered;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    protected $phone;

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

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }
}
