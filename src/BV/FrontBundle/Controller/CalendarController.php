<?php

namespace BV\FrontBundle\Controller;

use BV\FrontBundle\Entity\Team;
use BV\FrontBundle\Entity\Events;
use BV\FrontBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BV\FrontBundle\Form\Type\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CalendarController extends Controller
{
    public function indexAction(Request $request)
    {
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();
        $form = $this->createForm(new ContactType());

        $isAllowedToEdit = false;
        $alertOptsTeamsJson = array();
        $types = array();
        if ($user instanceof User) {
            $res = array();
            if ($this->get('security.context')->isGranted('ROLE_ADMIN'))
            {
                $teams = $this->getDoctrine()->getRepository('FrontBundle:Team')->findAllAsArray();
                $types = array_keys(Events::getEventsTypeForAdmin());
            }
            else
            {
                $teams = $this->getDoctrine()->getRepository('FrontBundle:Team')->findAllByUserId($user->getId());
                $types = [ Events::TYPE_TRAINING, Events::TYPE_MATCH, Events::TYPE_CUP];
            }

            foreach ($teams as $team)
            {
                $res[$team['id']] = $team['name'];
                $isAllowedToEdit = true;
            }
            $isAllowedToEdit = $isAllowedToEdit || $user->isSuperAdmin();
            $alertOptsTeamsJson = $res;
        }

        return $this->render('FrontBundle:Calendar:index.html.twig', array(
            'user_id' => ($user instanceof User ? $user->getId() : null),
            'form' => $form->createView(),
            'alert_opts_teams_json' => $alertOptsTeamsJson,
            'alert_opts_types_json' => $types,
            'img_paths_json' => json_encode(Events::getImagePaths()),
            'is_allowed_to_edit' => $isAllowedToEdit,
            'is_admin' => ($user instanceof User ? $user->isSuperAdmin() : false)
        ));
    }

    public function getEventsAction(Request $request)
    {
        $events = $this->container->get('doctrine')->getRepository('FrontBundle:Events')->findAll();
        $res = [];
        foreach ($events as $event) /* @var $event Events */
        {
            $title = '';
            $start = '';
            switch ($event->getType())
            {
                case Events::TYPE_CLOSED:
                    $title = 'Fermé';
                    $start = $event->getStartDate()->format('Y-m-d');
                break;
                case Events::TYPE_TRAINING:
                    $title = $event->getTeam()->getName();
                    $start = $event->getStartDate()->format('Y-m-d H:i');
                break;
                case Events::TYPE_MATCH:
                    $title = $event->getTeam()->getName();
                    $start = $event->getStartDate()->format('Y-m-d H:i');
                break;
                case Events::TYPE_CUP:
                    $title = $event->getTeam()->getName();
                    $start = $event->getStartDate()->format('Y-m-d H:i');
                break;
                case Events::TYPE_VOLLEYSCHOOL_ADULT:
                    $title = 'Ecole de Volley Adultes';
                    $start = $event->getStartDate()->format('Y-m-d H:i');
                break;
                case Events::TYPE_VOLLEYSCHOOL_YOUTH:
                    $title = 'Ecole de Volley Jeunes';
                    $start = $event->getStartDate()->format('Y-m-d H:i');
                break;
                case Events::TYPE_FREE_PLAY:
                    $title = 'Jeu libre';
                    $start = $event->getStartDate()->format('Y-m-d H:i');
                break;
            }
            $res[] = [
                'id'    => $event->getId(),
                'details' => $event->getText(),
                'title' => $title,
                'start' => $start,
                'end'   => $event->getEndDate()->format('Y-m-d H:i'),
                'type'  => $event->getType(),
                'className' => 'bv_event_'.$event->getType(),
                'team' => ($event->getTeam() != null ? $event->getTeam()->getId() : null),
                'captain' => ($event->getTeam() != null && $event->getTeam()->getCaptain() != null ? $event->getTeam()->getCaptain()->getId() : null),
                'subcaptain' => ($event->getTeam() != null && $event->getTeam()->getSubCaptain() != null ? $event->getTeam()->getSubCaptain()->getId() : null),
            ];
        }
        $response = new Response(json_encode($res));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function saveEventsAction(Request $request)
    {
        /* @var $user User */
        $user = $this->container->get('security.context')->getToken()->getUser();

        $teamId = $request->get('team');
        $typeId = $request->get('type');
        $startDate = $request->get('start-date');
        $endDate = $request->get('end-date');
        $details = $request->get('details');
        $action = $request->get('action');

        if ( !$user instanceof User || $teamId === null || $typeId === null || $action === null )
        {
            $this->container->get('session')->getFlashBag()->add('error', 'Paramètres invalides');
            return $this->redirect($this->generateUrl('bv_calendar_index'));
        }

        $team = $this->getDoctrine()->getRepository('FrontBundle:Team')->find($teamId);

        // Ajout d'un évènement
        if ($action == "inserted") {
            $event = new Events();

            $date = $request->get('date');
            $split = explode(':',$startDate);
            $d = new \DateTime($date);
            $d->setTime($split[0], $split[1]);
            $event->setStartDate($d);

            $date = $request->get('date');
            $split = explode(':',$endDate);
            $d = new \DateTime($date);
            $d->setTime($split[0], $split[1]);

            $event->setEndDate($d);
            $event->setType($typeId);
            $event->setTeam($team);
            $event->setText($details);
            $messages = $this->getDoctrine()->getRepository('FrontBundle:User')->isAllowedToInsert($user, $event);
            $messages = array_merge($messages, $this->getDoctrine()->getRepository('FrontBundle:Events')->isValidForInsert($event));

            if (count($messages) == 0)
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
                $em->flush();

                $this->container->get('session')->getFlashBag()->add('success', 'Evènement sauvegardé avec succès.');
            }
            else
            {
                $this->container->get('session')->getFlashBag()->add('error', implode('<br/>', $messages));
            }
        }

        // Update
        if ($action == "update") {
            $eventId = $request->get('event-id');
            $event = $this->container->get('doctrine')->getRepository('FrontBundle:Events')->find($eventId);

            $date = $request->get('date');
            $split = explode(':',$startDate);
            $d = new \DateTime($date);
            $d->setTime($split[0], $split[1]);
            $event->setStartDate($d);

            $date = $request->get('date');
            $split = explode(':',$endDate);
            $d = new \DateTime($date);
            $d->setTime($split[0], $split[1]);

            $event->setEndDate($d);
            $event->setType($typeId);
            $event->setTeam($team);
            $event->setText($details);
            $messages = $this->getDoctrine()->getRepository('FrontBundle:User')->isAllowedToUpdate($user, $event);
            $messages = array_merge($messages, $this->getDoctrine()->getRepository('FrontBundle:Events')->isValidForUpdate($event));

            if (count($messages) == 0)
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
                $em->flush();

                $this->container->get('session')->getFlashBag()->add('success', 'Evènement sauvegardé avec succès.');
            }
            else
            {
                $this->container->get('session')->getFlashBag()->add('error', implode('<br/>', $messages));
            }
        }

        return $this->redirect($this->generateUrl('bv_calendar_index'));
    }

    public function deleteEventsAction(Request $request)
    {
        /* @var $user User */
        $user = $this->container->get('security.context')->getToken()->getUser();
        $eventId = $request->get('event-id');
        $event = $this->container->get('doctrine')->getRepository('FrontBundle:Events')->find($eventId);
        $messages = $this->getDoctrine()->getRepository('FrontBundle:User')->isAllowedToDelete($user, $event);
        if (count($messages) == 0)
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();

            $this->container->get('session')->getFlashBag()->add('success', 'Evènement supprimé avec succès.');
        }
        else
        {
            $this->container->get('session')->getFlashBag()->add('error', implode('<br/>', $messages));
        }
        return $this->redirect($this->generateUrl('bv_calendar_index'));
    }
}
