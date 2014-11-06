<?php

namespace BV\FrontBundle\Entity;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * TeamRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TeamRepository extends EntityRepository
{
    public function findAllByUserId($id)
    {
        $query = $this->getEntityManager()
            ->createQuery('
            SELECT p.id, p.name FROM FrontBundle:Team p
            WHERE p.captain = :id
            OR p.subCaptain = :id'
            )->setParameter('id', $id);

        try {
            return $query->getArrayResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @return array
     */
    public function findAllAsOptions()
    {
        $res = array();
        foreach ($this->findAll() as $team) {
            $res[] = array(
                'key' => $team->getId(),
                'label' => $team->getName()
            );
        }
        return $res;
    }
}
