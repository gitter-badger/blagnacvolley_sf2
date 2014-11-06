<?php

namespace Tools\LogBundle\Logger;

use Tools\LogBundle\Entity\SystemLog;
use Doctrine\ORM\EntityManager;

class Logger
{
    private $doctrine = null;

    public function __construct(EntityManager $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function addNotice       ($type, $user, $content = '') { $this->addMessage(SystemLog::NOTICE, $type, $user, $content); }
    public function addWarning      ($type, $user, $content = '') { $this->addMessage(SystemLog::REQUIRE_ACTION, $type, $user, $content); }

    /**
     * Save the message to the database
     *
     * @param $level
     * @param $type
     * @param $user
     * @param $content
     */
    public function addMessage($level, $type, $user, $content)
    {
        $systemLog = new SystemLog($level);
        $systemLog->setUser($user);
        $systemLog->setContent($content);
        $systemLog->setType($type);
        $systemLog->setIsRead(false);
        $this->doctrine->persist($systemLog);
        $this->doctrine->flush();
    }

    /**
     * tag requested log as read
     *
     * @param $id
     */
    public function setAsRead($id)
    {
        $systemLog = $this->doctrine->getRepository('ToolsLogBundle:SystemLog')->find($id);
        if ($systemLog instanceof SystemLog)
        {
            $systemLog->setIsRead(true);
            $this->doctrine->persist($systemLog);
            $this->doctrine->flush();
        }
    }

    /**
     * Remove requested log from database
     *
     * @param $id
     */
    public function removeLog($id)
    {
        $systemLog = $this->doctrine->getRepository('ToolsLogBundle:SystemLog')->find($id);
        if ($systemLog instanceof SystemLog)
        {
            $systemLog->setIsRead(true);
            $this->doctrine->remove($systemLog);
        }
    }
}
