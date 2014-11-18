<?php

namespace BV\FrontBundle\Entity;

use Composer\EventDispatcher\Event;
use FOS\UserBundle\Entity\User as EntityUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use BV\FrontBundle\Entity\Events;

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
 * - Numéro de License (license_number)
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
    const STATUS_ACTIVE_LICENSED = 'ACTIVE_LICENSED';
    const STATUS_ACTIVE_NOT_LICENSED = 'ACTIVE_NOT_LICENSED';
    const STATUS_INACTIVE = 'INACTIVE';

    const POSTE_POINTU = 'POINTU';
    const POSTE_LIBERO = 'LIBERO';
    const POSTE_RECEP4 = 'RECEP4';
    const POSTE_CENTRAL = 'CENTRAL';
    const POSTE_PASSEUR = 'PASSEUR';

    const GENDER_MALE = 'MALE';
    const GENDER_FEMALE = 'FEMALE';

    const LEVEL_EXC_A = 'EXC_A';
    const LEVEL_EXC_B = 'EXC_B';
    const LEVEL_EXC_C = 'EXC_C';
    const LEVEL_HON_A = 'HON_A';
    const LEVEL_HON_B = 'HON_B';
    const LEVEL_HON_C = 'HON_C';
    const LEVEL_PROM_A = 'PROM_A';
    const LEVEL_PROM_B = 'PROM_B';
    const LEVEL_PROM_C = 'PROM_C';

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
     * @ORM\Column(name="picture", type="string", length=512)
     */
    protected $picture;

    /**
     * @var File
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff"},
     *     maxSizeMessage = "The maxmimum allowed file size is 5MB.",
     *     mimeTypesMessage = "Only the filetypes image are allowed."
     * )
     */
    public $pictureFile;

    /**
     * @var string
     *
     * @ORM\Column(name="certif", type="string", length=512, nullable=true)
     */
    protected $certif;

    /**
     * @var File
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff", "application/pdf"},
     *     maxSizeMessage = "The maxmimum allowed file size is 5MB.",
     *     mimeTypesMessage = "Only the filetypes image are allowed."
     * )
     */
    public $certifFile;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_certif", type="datetime", nullable=true)
     */
    protected $dateCertif;

    /**
     * @var string
     *
     * @ORM\Column(name="attestation", type="string", length=512, nullable=true)
     */
    protected $attestation;

    /**
     * @var File
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff", "application/pdf"},
     *     maxSizeMessage = "The maxmimum allowed file size is 5MB.",
     *     mimeTypesMessage = "Only the filetypes image are allowed."
     * )
     */
    public $attestationFile;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_attestation", type="datetime", nullable=true)
     */
    protected $dateAttestation;

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
    protected $isRequiredBill;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="BV\FrontBundle\Entity\Team", cascade={"persist", "remove"}, inversedBy="membersMsc")
     * @ORM\JoinColumn(name="msc_team_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $mscTeam;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="BV\FrontBundle\Entity\Team", cascade={"persist", "remove"}, inversedBy="membersFem")
     * @ORM\JoinColumn(name="fem_team_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $femTeam;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="BV\FrontBundle\Entity\Team", cascade={"persist", "remove"}, inversedBy="membersMix")
     * @ORM\JoinColumn(name="mix_team_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $mixTeam;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_looking_for_team", type="boolean", options={"default": false})
     */
    protected $isLookingForTeam;

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
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="license_number", type="string", length=255, nullable=true)
     */
    protected $licenseNumber;

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
     * @ORM\Column(name="poste", type="string", length=255, nullable=true)
     */
    protected $poste;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     * @ORM\OneToMany(targetEntity="BV\FrontBundle\Entity\Availability", mappedBy="user")
     */
    protected $availability;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_volley_school_adult", type="boolean", options={"default": false})
     */
    protected $isVolleySchoolAdult;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_volley_school_youth", type="boolean", options={"default": false})
     */
    protected $isVolleySchoolYouth;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_freeplay", type="boolean", options={"default": false})
     */
    protected $isFreeplay;

    public function __construct()
    {
        parent::__construct();

        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->isRequiredBill = false;
        $this->isLookingForTeam = false;
        $this->status = static::STATUS_ACTIVE_NOT_LICENSED;
    }

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
     * Returns the gender list
     *
     * @return array
     */
    public static function getGenderList()
    {
        return array(
            self::GENDER_MALE    => 'constants.user.gender.'.self::GENDER_MALE,
            self::GENDER_FEMALE  => 'constants.user.gender.'.self::GENDER_FEMALE,
        );
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
     * Returns the level list
     *
     * @return array
     */
    public static function getLevelList()
    {
        return array(
            self::LEVEL_EXC_A => 'constants.user.level.'.self::LEVEL_EXC_A,
            self::LEVEL_EXC_B => 'constants.user.level.'.self::LEVEL_EXC_B,
            self::LEVEL_EXC_C => 'constants.user.level.'.self::LEVEL_EXC_C,
            self::LEVEL_HON_A => 'constants.user.level.'.self::LEVEL_HON_A,
            self::LEVEL_HON_B => 'constants.user.level.'.self::LEVEL_HON_B,
            self::LEVEL_HON_C => 'constants.user.level.'.self::LEVEL_HON_C,
            self::LEVEL_PROM_A => 'constants.user.level.'.self::LEVEL_PROM_A,
            self::LEVEL_PROM_B => 'constants.user.level.'.self::LEVEL_PROM_B,
            self::LEVEL_PROM_C => 'constants.user.level.'.self::LEVEL_PROM_C,
        );
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
     * Returns the status list
     *
     * @return array
     */
    public static function getStatusList()
    {
        return array(
            self::STATUS_ACTIVE_LICENSED => 'constants.user.status.'.self::STATUS_ACTIVE_LICENSED,
            self::STATUS_ACTIVE_NOT_LICENSED => 'constants.user.status.'.self::STATUS_ACTIVE_NOT_LICENSED,
            self::STATUS_INACTIVE => 'constants.user.status.'.self::STATUS_INACTIVE,
        );
    }

    /**
     * Returns the status list
     *
     * @return array
     */
    public static function getPostesList()
    {
        return array(
            self::POSTE_CENTRAL => 'constants.user.postes.'.self::POSTE_CENTRAL,
            self::POSTE_LIBERO  => 'constants.user.postes.'.self::POSTE_LIBERO,
            self::POSTE_PASSEUR => 'constants.user.postes.'.self::POSTE_PASSEUR,
            self::POSTE_POINTU  => 'constants.user.postes.'.self::POSTE_POINTU,
            self::POSTE_RECEP4  => 'constants.user.postes.'.self::POSTE_RECEP4,
        );
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
     * Set licenseNumber
     *
     * @param string $licenseNumber
     * @return User
     */
    public function setLicenseNumber($licenseNumber)
    {
        $this->licenseNumber = $licenseNumber;

        return $this;
    }

    /**
     * Get licenseNumber
     *
     * @return string
     */
    public function getLicenseNumber()
    {
        return $this->licenseNumber;
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

    // ********************************************
    // Forgotten in FOSUser

    /**
     * Returns the expiration date
     *
     * @return \DateTime|null
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Returns the credentials expiration date
     *
     * @return \DateTime
     */
    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }

    /**
     * Sets the credentials expiration date
     *
     * @param \DateTime|null $date
     * @return User
     */
    public function setCredentialsExpireAt(\DateTime $date = null)
    {
        $this->credentialsExpireAt = $date;

        return $this;
    }

    /**
     * Returns a string representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getUsername() ?: '-';
    }

    /**
     * Sets the user groups
     *
     * @param array $groups
     */
    public function setGroups($groups)
    {
        foreach ($groups as $group) {
            $this->addGroup($group);
        }
    }

    /**
     * Set certif
     *
     * @param string $certif
     * @return User
     */
    public function setCertif($certif)
    {
        $this->certif = $certif;
    
        return $this;
    }

    /**
     * Get certif
     *
     * @return string 
     */
    public function getCertif()
    {
        return $this->certif;
    }

    /**
     * Set attestation
     *
     * @param string $attestation
     * @return User
     */
    public function setAttestation($attestation)
    {
        $this->attestation = $attestation;
    
        return $this;
    }

    /**
     * Get attestation
     *
     * @return string 
     */
    public function getAttestation()
    {
        return $this->attestation;
    }

    /**
     * Set poste
     *
     * @param string $poste
     * @return User
     */
    public function setPoste($poste)
    {
        $this->poste = $poste;
    
        return $this;
    }

    /**
     * Get poste
     *
     * @return string 
     */
    public function getPoste()
    {
        return $this->poste;
    }

    /**
     * Set dateCertif
     *
     * @param \DateTime $dateCertif
     * @return User
     */
    public function setDateCertif($dateCertif)
    {
        $this->dateCertif = $dateCertif;
    
        return $this;
    }

    /**
     * Get dateCertif
     *
     * @return \DateTime 
     */
    public function getDateCertif()
    {
        return $this->dateCertif;
    }

    /**
     * Set dateAttestation
     *
     * @param \DateTime $dateAttestation
     * @return User
     */
    public function setDateAttestation($dateAttestation)
    {
        $this->dateAttestation = $dateAttestation;
    
        return $this;
    }

    /**
     * Get dateAttestation
     *
     * @return \DateTime 
     */
    public function getDateAttestation()
    {
        return $this->dateAttestation;
    }

    /**
     * Set isVolleySchoolAdult
     *
     * @param boolean $isVolleySchoolAdult
     * @return User
     */
    public function setIsVolleySchoolAdult($isVolleySchoolAdult)
    {
        $this->isVolleySchoolAdult = $isVolleySchoolAdult;
    
        return $this;
    }

    /**
     * Get isVolleySchoolAdult
     *
     * @return boolean 
     */
    public function getIsVolleySchoolAdult()
    {
        return $this->isVolleySchoolAdult;
    }

    /**
     * Set isVolleySchoolYouth
     *
     * @param boolean $isVolleySchoolYouth
     * @return User
     */
    public function setIsVolleySchoolYouth($isVolleySchoolYouth)
    {
        $this->isVolleySchoolYouth = $isVolleySchoolYouth;
    
        return $this;
    }

    /**
     * Get isVolleySchoolYouth
     *
     * @return boolean 
     */
    public function getIsVolleySchoolYouth()
    {
        return $this->isVolleySchoolYouth;
    }

    /**
     * Set isFreeplay
     *
     * @param boolean $isFreeplay
     * @return User
     */
    public function setIsFreeplay($isFreeplay)
    {
        $this->isFreeplay = $isFreeplay;
    
        return $this;
    }

    /**
     * Get isFreeplay
     *
     * @return boolean 
     */
    public function getIsFreeplay()
    {
        return $this->isFreeplay;
    }

    /**
     * @param $group
     */
    public function toggleGroup($group)
    {
        if ($group == Events::TYPE_VOLLEYSCHOOL_ADULT) {
            $this->setIsVolleySchoolAdult(!$this->getIsVolleySchoolAdult());
        }
        if ($group == Events::TYPE_VOLLEYSCHOOL_YOUTH) {
            $this->setIsVolleySchoolYouth(!$this->getIsVolleySchoolYouth());
        }
        if ($group == Events::TYPE_FREE_PLAY) {
            $this->setIsFreeplay(!$this->getIsFreeplay());
        }
    }

    public function isInGroup($group)
    {
        if ($group == Events::TYPE_VOLLEYSCHOOL_ADULT) {
            return ($this->getIsVolleySchoolAdult());
        }
        if ($group == Events::TYPE_VOLLEYSCHOOL_YOUTH) {
            return ($this->getIsVolleySchoolYouth());
        }
        if ($group == Events::TYPE_FREE_PLAY) {
            return ($this->getIsFreeplay());
        }

        return false;
    }
}
