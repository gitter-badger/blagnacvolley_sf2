<?php

namespace BV\FrontBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * EventsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventsRepository extends EntityRepository
{
    public function findSingleBySchedulerId($id)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT p FROM FrontBundle:Events p WHERE p.schedulerId = :id')
            ->setParameter('id', $id);
        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param Events $event
     * @param $user
     * @return bool
     */
    public function isReadonly(Events $event, $user)
    {
        if (!$user instanceof User)
            return true;

        if ($event->getTeam() == null)
            return true;

        if ($user->isSuperAdmin())
            return false;

        if ($event->getTeam()->getCaptain() instanceof User && $event->getTeam()->getCaptain()->getId() == $user->getId())
            return false;

        if ($event->getTeam()->getSubCaptain() instanceof User && $event->getTeam()->getSubCaptain()->getId() == $user->getId())
            return false;

        return true;
    }

    public function isValidForInsert(Events $event)
    {
        $messages = array();
        $now = new \DateTime();
        if ($now > $event->getStartDate() || $now > $event->getEndDate())
            $messages[] = "Vous ne pouvez pas ajouter des évènements à une date passée";

        return $messages;
    }

    public function isValidForUpdate(Events $event)
    {
        return $this->isValidForInsert($event);
    }

    public function findEventsByTypeAfter($type, \Datetime $date)
    {
        $query = $this->getEntityManager()
            ->createQuery(' SELECT p FROM FrontBundle:Events p '.
                          ' WHERE p.type = :type '.
                          ' AND p.startDate >= :date')
            ->setParameter('type', $type)
            ->setParameter('date', $date->format('Y-m-d'));
        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findEventsByTeamForDate($team, \Datetime $date, $eventId = null)
    {
        $sql =  ' SELECT p FROM FrontBundle:Events p '.
                ' WHERE p.team = :team '.
                ' AND p.startDate = :date';

        if ($eventId != null)
            $sql .= ' AND p.id <> :id';

        $query = $this->getEntityManager()
            ->createQuery($sql)
            ->setParameter('team', $team)
            ->setParameter('date', $date->format('Y-m-d H:i'));

        if ($eventId != null)
           $query->setParameter('id', $eventId);

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findEventsForDate(\Datetime $date)
    {
        $query = $this->getEntityManager()
            ->createQuery(' SELECT p FROM FrontBundle:Events p '.
                ' WHERE p.startDate = :date')
            ->setParameter('date', $date->format('Y-m-d H:i'));
        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param $type
     * @return array|null
     */
    public function findEventsByType($type)
    {
        $date = new \DateTime();
        $query = $this->getEntityManager()
            ->createQuery('SELECT p FROM FrontBundle:Events p '.
                ' WHERE p.type = :type
                  AND p.startDate > :date')
            ->setParameter('type', $type)
            ->setParameter('date', $date->format('Y-m-d H:i'))
        ;
        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * Check if type is valid for a group
     *
     * @param $type
     * @return bool
     */
    public static function isEventTypeValidForGroup($type)
    {
        return ( $type == Events::TYPE_VOLLEYSCHOOL_ADULT || $type == Events::TYPE_VOLLEYSCHOOL_YOUTH || $type == Events::TYPE_FREE_PLAY);
    }
}
