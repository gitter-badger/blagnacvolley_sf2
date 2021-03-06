<?php

namespace BV\FrontBundle\Entity\Repository;

use BV\FrontBundle\Entity\Events;
use BV\FrontBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    const NB_ALLOWED_TRAINING_RESERVATIONS = 2;
    /**
     * Check if current user is allowed to insert $event Event
     *
     * @param User $user
     * @param Events $event
     * @return array
     */
    public function isAllowedToInsert(User $user, Events $event)
    {
        $messages = array();

        if (!$user instanceof User) {
            $messages[] = "Vous devez vous connecter pour effectuer cette action.";
        } else {
            if ($user->isSuperAdmin())
                return array();

            if (!Events::isTypeValid($event->getType())) {
                $messages[] = "Le type d'évènement saisi est invalide.";
            }

            if ($event->getType() == Events::TYPE_CLOSED || $event->getType() == Events::TYPE_VOLLEYSCHOOL_ADULT || $event->getType() == Events::TYPE_VOLLEYSCHOOL_YOUTH)
                $messages[] = "Vous ne pouvez pas créer ce type d'évènement.";

            // Current User is Captain or Subcaptain of this Team
            if  (   $event->getTeam()->getCaptain() == $user ||
                $event->getTeam()->getSubCaptain() == $user  )
            {
                // Only for Team, can only create two forecast reservations
                if ($event->getType() == Events::TYPE_TRAINING) {
                    $events = $this->getEntityManager()->getRepository('FrontBundle:Events')->findEventsByTypeAfter(Events::TYPE_TRAINING, new \DateTime());
                    if (count($events) >= self::NB_ALLOWED_TRAINING_RESERVATIONS)
                        $messages[] = "Vous ne pouvez réserver que deux créneaux d'entrainements à l'avance.";
                }

                // Check that there is not a training of this team this day
                $events = $this->getEntityManager()->getRepository('FrontBundle:Events')->findEventsByTeamForDate($event->getTeam(), $event->getStartDate(), $event->getId());
                if (count($events) > 0) {
                    $messages[] = "Vous ne pouvez réserver ce créneau, vous avez déjà un évènement de planifié ce jour là.";
                }

                // Check that there is not already 3 teams this night
                $events = $this->getEntityManager()->getRepository('FrontBundle:Events')->findEventsForDate($event->getStartDate());
                if (count($events) == 3) {
                    $messages[] = "Vous ne pouvez réserver ce créneau, il y a déjà trois équipes présentes.";
                }

                // Check that there is no vacations this day
                $events = $this->getEntityManager()->getRepository('FrontBundle:Events')->findClosedEventsIncludingDate($event->getStartDate());
                foreach ($events as $e) /* @var $e Events */
                {
                    if ($e->getType() == Events::TYPE_CLOSED)
                    {
                        $messages[] = "Vous ne pouvez réserver ce créneau, le gymnase sera fermé !";
                        break;
                    }
                }

            } else {
                $messages[] = "Seuls les capitaines et sous-capitaines peuvent créer des évènements.";
            }
        }

        return $messages;
    }

    /**
     * Same as Insert
     *
     * @param User $user
     * @param Events $event
     * @return array
     */
    public function isAllowedToUpdate(User $user, Events $event)
    {
        $messages = array();

        if (!$user instanceof User) {
            $messages[] = "Vous devez vous connecter pour effectuer cette action.";
        } else {
            if ($user->isSuperAdmin())
                return array();

            if (!Events::isTypeValid($event->getType())) {
                $messages[] = "Le type d'évènement saisi est invalide.";
            }

            if ($event->getType() == Events::TYPE_CLOSED || $event->getType() == Events::TYPE_VOLLEYSCHOOL_ADULT || $event->getType() == Events::TYPE_VOLLEYSCHOOL_YOUTH)
                $messages[] = "Vous ne pouvez pas créer ce type d'évènement.";

            // Current User is Captain or Subcaptain of this Team
            if  (   $event->getTeam()->getCaptain() == $user ||
                $event->getTeam()->getSubCaptain() == $user  )
            {
                // Only for Team, can only create two forecast reservations
                if ($event->getType() == Events::TYPE_TRAINING) {
                    $events = $this->getEntityManager()->getRepository('FrontBundle:Events')->findEventsByTypeAfter(Events::TYPE_TRAINING, new \DateTime());
                    if (count($events) >= self::NB_ALLOWED_TRAINING_RESERVATIONS)
                        $messages[] = "Vous ne pouvez réserver que deux créneaux d'entrainements à l'avance.";
                }

                // Check that there is not a training of this team this day
                $events = $this->getEntityManager()->getRepository('FrontBundle:Events')->findEventsByTeamForDate($event->getTeam(), $event->getStartDate(), $event->getId());
                if (count($events) > 0) {
                    $messages[] = "Vous ne pouvez réserver ce créneau, vous avez déjà un évènement de planifié ce jour là.";
                }

                // Check that there is not already 3 teams this night
                if (count($this->getEntityManager()->getRepository('FrontBundle:Events')->findEventsForDate($event->getStartDate())) == 3) {
                    $messages[] = "Vous ne pouvez réserver ce créneau, il y a déjà trois équipes présentes.";
                }

            } else {
                $messages[] = "Seuls les capitaines et sous-capitaines peuvent créer des évènements.";
            }
        }

        return $messages;
    }

    /**
     * Same as Insert
     *
     * @param User $user
     * @param Events $event
     * @return array
     */
    public function isAllowedToDelete(User $user, Events $event)
    {
        $messages = array();

        if (!$user instanceof User) {
            $messages[] = "Vous devez vous connecter pour effectuer cette action.";
        } else {
            if ($user->isSuperAdmin())
                return array();

            // Current User is Captain or Subcaptain of this Team
            if  ( $event->getTeam()->getCaptain() != $user && $event->getTeam()->getSubCaptain() != $user  )
            {
                $messages[] = "Seuls les capitaines et sous-capitaines peuvent créer des évènements.";
            }
        }

        return $messages;
    }

    /**
     * For now, only super admin is allowed to edit CMS Pages
     *
     * @param $user
     * @return bool
     */
    public static function isAllowedToEditCmsPages($user)
    {
        if (!$user instanceof User)
            return false;

        if ($user->isSuperAdmin())
            return true;

        return false;
    }

    public function findAllByTeam($id)
    {
        $query = $this->getEntityManager()
            ->createQuery('
            SELECT p FROM FrontBundle:User p
            WHERE p.mscTeam = :id
            OR p.mixTeam = :id
            OR p.femTeam = :id'
            )->setParameter('id', $id);

        try {
            return $query->getArrayResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @return array|null
     */
    public function countUsersByGroups()
    {
        $query = $this->getEntityManager()
            ->createQuery(' SELECT
                                SUM(p.isVolleySchoolAdult) as '.Events::TYPE_VOLLEYSCHOOL_ADULT.',
                                SUM(p.isVolleySchoolYouth) as '.Events::TYPE_VOLLEYSCHOOL_YOUTH.',
                                SUM(p.isFreeplay) as '.Events::TYPE_FREE_PLAY.'
                            FROM FrontBundle:User p');
        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function countUsersForDashboard()
    {
        $query = $this->getEntityManager()->createQuery(' SELECT u.enabled, COUNT(u.enabled) as nb FROM FrontBundle:User u GROUP BY u.enabled ');
        $totals = $query->getResult();

        $results = array(
            User::STATUS_ACTIVE_NOT_LICENSED => '0',
            User::STATUS_ACTIVE_WAITING_LICENSE => '0',
            User::STATUS_ACTIVE_WAITING_VALIDATION => '0',
            User::STATUS_ACTIVE_LICENSED => '0',
            User::STATUS_INACTIVE => '0'
        );

        $results['total'] = 0;
        foreach ($totals as $total)
        {
            $results['total'] += $total['nb'];
            if ($total['enabled'] == false)
            {
                $results[User::STATUS_INACTIVE] = $total['nb'];
            }
        }

        $query = $this->getEntityManager()->createQuery(' SELECT u.status, COUNT(u.status) as nb FROM FrontBundle:User u WHERE u.enabled = TRUE GROUP BY u.status ');
        $statuses = $query->getResult();

        foreach ($statuses as $status)
        {
            $results[$status['status']] = $status['nb'];
        }

        return $results;
    }

    /**
     * @return array|null
     */
    public function getDeskUsers()
    {
        $query = $this->getEntityManager()
            ->createQuery('     SELECT p FROM FrontBundle:User p '.
                '   WHERE p.deskRole IS NOT NULL '
            );

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findAllActive()
    {
        $query = $this->getEntityManager()
            ->createQuery(' SELECT p FROM FrontBundle:User p'.
                          ' WHERE p.status <> :status'
            )->setParameter('status', User::STATUS_INACTIVE)
        ;

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}
