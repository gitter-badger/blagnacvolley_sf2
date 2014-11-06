<?php

namespace Tools\LogBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class SystemLogRepository extends EntityRepository
{
    /**
     * Find the latest logs
     */
    public function findLatest()
    {
        $qb = $this->createQueryBuilder('l');

        $qb->add('orderBy', 'l.id DESC');

        $qb->setMaxResults(200);

        //Get our query
        $q = $qb->getQuery();

        //Return result
        return $q->getResult();
    }

    /**
     * Count unread logs and group them by type
     *
     * @return array|null
     */
    public function countGroupByType()
    {
        $query = $this->getEntityManager()
            ->createQuery("SELECT p.type, count(p) AS nb FROM ToolsLogBundle:SystemLog p WHERE p.isRead = false GROUP BY p.type");
        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * Count unread logs and group them by level
     *
     * @return array|null
     */
    public function countGroupByLevel()
    {
        $query = $this->getEntityManager()
            ->createQuery("SELECT p.level, p.type, count(p) AS nb FROM ToolsLogBundle:SystemLog p WHERE p.isRead = false GROUP BY p.level");
        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }


    /**
     * Count unread logs and group them by level
     *
     * @return array|null
     */
    public function findAllOrderByLevel()
    {
        $query = $this->getEntityManager()
            ->createQuery("SELECT p FROM ToolsLogBundle:SystemLog p WHERE p.isRead = false ORDER BY p.level DESC");
        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}
