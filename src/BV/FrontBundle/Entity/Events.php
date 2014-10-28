<?php

namespace BV\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Events
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BV\FrontBundle\Entity\EventsRepository")
 */
class Events
{
    const TYPE_TRAINING = 'TRAINING';
    const TYPE_MATCH = 'MATCH';
    const TYPE_VOLLEYSCHOOL_ADULT = 'VOLLEYSCHOOL_ADULT';
    const TYPE_VOLLEYSCHOOL_YOUTH = 'VOLLEYSCHOOL_YOUTH';
    const TYPE_CLOSED = 'CLOSED';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime")
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="BV\FrontBundle\Entity\Team", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $team;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;

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
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Events
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    
        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Events
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    
        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Events
     */
    public function setText($text)
    {
        $this->text = $text;
    
        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Events
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
     * Set team
     *
     * @param \BV\FrontBundle\Entity\Team $team
     * @return Events
     */
    public function setTeam(\BV\FrontBundle\Entity\Team $team = null)
    {
        $this->team = $team;
    
        return $this;
    }

    /**
     * Get team
     *
     * @return \BV\FrontBundle\Entity\Team 
     */
    public function getTeam()
    {
        return $this->team;
    }

    public function getImageFromType()
    {
        return '/images/icons/'.$this->getType().'.png';
    }

    /**
     * Returns the level list
     *
     * @return array
     */
    public static function getEventsType()
    {
        return array(
            self::TYPE_TRAINING             => 'constants.events.type.'.self::TYPE_TRAINING,
            self::TYPE_MATCH                => 'constants.events.type.'.self::TYPE_MATCH,
            self::TYPE_VOLLEYSCHOOL_ADULT   => 'constants.events.type.'.self::TYPE_VOLLEYSCHOOL_ADULT,
            self::TYPE_VOLLEYSCHOOL_YOUTH   => 'constants.events.type.'.self::TYPE_VOLLEYSCHOOL_YOUTH,
            self::TYPE_CLOSED               => 'constants.events.type.'.self::TYPE_CLOSED,
        );
    }
}
