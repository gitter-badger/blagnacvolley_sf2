<?php

namespace BV\FrontBundle\Entity\Repository;

use BV\FrontBundle\Entity\Availability;
use BV\FrontBundle\Entity\Events;
use BV\FrontBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * AvailabilityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AvailabilityRepository extends EntityRepository
{
    /**
     * @param $event
     * @return array|null
     */
    public function countAvailabilities($event)
    {
        if (!$event instanceof Events)
            return array();

        $repo = $this->getEntityManager()->getRepository('FrontBundle:Availability');

        $query = $this->getEntityManager()
            ->createQuery(' SELECT p '.
                          ' FROM FrontBundle:Availability p '.
                          ' WHERE p.event = :event_id')
            ->setParameter('event_id', $event)
        ;
        try {
            $res = [
                'count' => 0,
                'available' => 0,
                'not_available' => 0,
            ];
            $results = $query->getResult();
            foreach ($results as $result) /* @var $result Availability */
            {
                $res['count']++;
                if ($result->getIsAvailable() == 1)
                    $res['available']++;
                else
                    $res['not_available']++;
            }
            return $res;
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param User $user
     * @return array|null
     */
    public function findByUser(User $user)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT p FROM FrontBundle:Availability p '.
                ' WHERE p.user = :user')
            ->setParameter('user', $user)
        ;
        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param Events $event
     * @return array|null
     */
    public function findByEvent($event)
    {
        if (!$event instanceof Events)
            return null;

        $query = $this->getEntityManager()
            ->createQuery('SELECT p FROM FrontBundle:Availability p '.
                ' WHERE p.event = :event')
            ->setParameter('event', $event)
        ;
        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }


    /**
     * @param Events $event
     * @return array|null
     */
    public function findByEventAndAvailable($event)
    {
        if (!$event instanceof Events)
            return null;

        $query = $this->getEntityManager()
            ->createQuery(' SELECT p FROM FrontBundle:Availability p '.
                          ' WHERE p.event = :event'.
                          ' AND p.isAvailable = true')
            ->setParameter('event', $event)
        ;
        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param $user
     * @param $event
     * @return array|null
     */
    public function findByUserAndEvent($user, $event)
    {
        if (!$user instanceof User || !$event instanceof Events)
            return null;

        $query = $this->getEntityManager()
            ->createQuery('SELECT p FROM FrontBundle:Availability p '.
                ' WHERE p.user = :user '.
                ' AND p.event = :event')
            ->setParameter('user', $user)
            ->setParameter('event', $event)
        ;
        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}
