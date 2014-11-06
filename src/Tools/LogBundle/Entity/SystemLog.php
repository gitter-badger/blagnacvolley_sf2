<?php

namespace Tools\LogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Exception\InvalidArgumentException;

/**
 *
 * @ORM\Entity(repositoryClass="Tools\LogBundle\Entity\SystemLogRepository")
 * @ORM\Table(name="system_log")
 * @ORM\HasLifecycleCallbacks()
 */
class SystemLog
{
    const DEBUG     = 100;
    const INFO      = 200;
    const NOTICE    = 250;
    const WARNING   = 300;
    const ERROR     = 400;
    const CRITICAL  = 500;
    const ALERT     = 550;
    const EMERGENCY = 600;

    const TYPE_USER_CREATED = 100;
    const TYPE_USER_NEW_SEASON = 110;

    protected static $levels = array(
        self::DEBUG     => 'DEBUG',
        self::INFO      => 'INFO',
        self::NOTICE    => 'NOTICE',
        self::WARNING   => 'WARNING',
        self::ERROR     => 'ERROR',
        self::CRITICAL  => 'CRITICAL',
        self::ALERT     => 'ALERT',
        self::EMERGENCY => 'EMERGENCY',
    );

    protected static $types = array(
        self::TYPE_USER_CREATED     => 'TYPE_USER_CREATED',
        self::TYPE_USER_NEW_SEASON  => 'TYPE_USER_NEW_SEASON',
    );

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="integer", length=3, nullable=true)
     */
    private $level;

    /**
     * @ORM\Column(type="integer", length=3, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(name="is_read", type="boolean", options={"default": false})
     */
    private $isRead;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modified;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    public function __construct($level = self::NOTICE)
    {
        $this->level = $level;
    }

    /**
     * Set isRequiredBill
     *
     * @param boolean $isRead
     * @return SystemLog
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setModifiedValue()
    {
        $this->modified = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue()
    {
        $this->modified = new \DateTime();

        $this->created = new \DateTime();
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
     * Set content
     *
     * @param string $content
     * @return SystemLog
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set level
     *
     * @param string $level
     * @return SystemLog
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
     * Set modified
     *
     * @param \DateTime $modified
     * @return SystemLog
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return SystemLog
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @ORM\PrePersist()
     */
    public function onPrePersist()
    {
        $now = new \DateTime();
        $this->modified = $now;
        $this->created = $now;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function onPreUpdate()
    {
        $this->modified = new \DateTime();
    }

    /**
     * Set type
     *
     * @param string $type
     * @return SystemLog
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
     * Gets all supported logging levels.
     *
     * @return array Assoc array with human-readable level names => level codes.
     */
    public static function getLevels()
    {
        return array_flip(static::$levels);
    }

    /**
     * Gets the name of the logging level.
     *
     * @param int $level
     * @return string
     * @throws \Symfony\Component\Form\Exception\InvalidArgumentException
     */
    public static function getLevelName($level)
    {
        if (!isset(static::$levels[$level])) {
            throw new InvalidArgumentException('Level "'.$level.'" is not defined, use one of: '.implode(', ', array_keys(static::$levels)));
        }

        return static::$levels[$level];
    }

    /**
     * Gets all supported logging types.
     *
     * @return array Assoc array with human-readable type names => type codes.
     */
    public static function getTypes()
    {
        return array_flip(static::$types);
    }

    /**
     * Gets the name of the logging type.
     *
     * @param int $type
     * @return string
     * @throws \Symfony\Component\Form\Exception\InvalidArgumentException
     */
    public static function getTypeName($type)
    {
        if (!isset(static::$types[$type])) {
            throw new InvalidArgumentException('Level "'.$type.'" is not defined, use one of: '.implode(', ', array_keys(static::$types)));
        }

        return static::$types[$type];
    }
}
