<?php

namespace BV\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsPage
 *
 * @ORM\Table(name="bv_cms_page")
 * @ORM\Entity(repositoryClass="BV\FrontBundle\Entity\Repository\CmsPageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CmsPage
{
    const STATIC_PAGE_VOLLEYSCHOOL = 'PAGE_VOLLEYSCHOOL';
    const STATIC_PAGE_VOLLEYSCHOOL_ADULTS = 'PAGE_VOLLEYSCHOOL_ADULTS';
    const STATIC_PAGE_VOLLEYSCHOOL_YOUTH = 'PAGE_VOLLEYSCHOOL_YOUTH';
    const STATIC_PAGE_FREE_GAME = 'PAGE_FREE_GAME';
    const STATIC_PAGE_SCHEDULE = 'PAGE_SCHEDULE';
    const STATIC_PAGE_ADDRESSES = 'PAGE_ADDRESSES';
    const STATIC_PAGE_OPTIONAL_INSURANCE = 'PAGE_OPTIONAL_INSURANCE';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=512, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;


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
     * @return CmsPage
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return CmsPage
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return CmsPage
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CmsPage
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
     * @ORM\PrePersist()
     */
    public function onPrePersist()
    {
        $now = new \DateTime();
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Set description
     *
     * @param string $description
     * @return CmsPage
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
}
