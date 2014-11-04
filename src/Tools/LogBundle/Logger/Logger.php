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

    public function addDebug($content) { $this->addMessage(SystemLog::DEBUG, $content); }
    public function addInfo($content) { $this->addMessage(SystemLog::INFO, $content); }
    public function addNotice($content) { $this->addMessage(SystemLog::NOTICE, $content); }
    public function addWarning($content) { $this->addMessage(SystemLog::WARNING, $content); }
    public function addError($content) { $this->addMessage(SystemLog::ERROR, $content); }
    public function addCritical($content) { $this->addMessage(SystemLog::CRITICAL, $content); }
    public function addAlert($content) { $this->addMessage(SystemLog::ALERT, $content); }
    public function addEmergency($content) { $this->addMessage(SystemLog::EMERGENCY, $content); }

    /**
     * Save the message to the database
     *
     * @param $level
     * @param $content
     */
    public function addMessage($level, $content)
    {
        $systemLog = new SystemLog($level);
        $systemLog->setContent($content);
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
