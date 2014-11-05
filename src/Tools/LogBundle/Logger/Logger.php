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

    public function addDebug        ($type, $content) { $this->addMessage(SystemLog::DEBUG, $type, $content); }
    public function addInfo         ($type, $content) { $this->addMessage(SystemLog::INFO, $type, $content); }
    public function addNotice       ($type, $content) { $this->addMessage(SystemLog::NOTICE, $type, $content); }
    public function addWarning      ($type, $content) { $this->addMessage(SystemLog::WARNING, $type, $content); }
    public function addError        ($type, $content) { $this->addMessage(SystemLog::ERROR, $type, $content); }
    public function addCritical     ($type, $content) { $this->addMessage(SystemLog::CRITICAL, $type, $content); }
    public function addAlert        ($type, $content) { $this->addMessage(SystemLog::ALERT, $type, $content); }
    public function addEmergency    ($type, $content) { $this->addMessage(SystemLog::EMERGENCY, $type, $content); }

    /**
     * Save the message to the database
     *
     * @param $level
     * @param $type
     * @param $content
     */
    public function addMessage($level, $type, $content)
    {
        $systemLog = new SystemLog($level);
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
