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
        if ($user instanceof User) {
            $res = array();
            foreach ($this->getDoctrine()->getRepository('FrontBundle:Team')->findAllByUserId($user->getId()) as $team)
            {
                $res[] = array(
                    'key' => $team['id'],
                    'label' => $team['name']
                );
                $isAllowedToEdit = true;
            }
            $isAllowedToEdit = $isAllowedToEdit || $user->isSuperAdmin();
            $alertOptsTeamsJson = json_encode($res);
        } else {
            $alertOptsTeamsJson = '[]';
        }

        return $this->render('FrontBundle:Calendar:index.html.twig', array(
            'form' => $form->createView(),
            'alert_opts_teams_json' => $alertOptsTeamsJson,
            'alert_opts_types_json' => json_encode(array(
                0 => array(
                    'key' => Events::TYPE_TRAINING,
                    'label' => $this->get('translator')->trans('constants.events.type.'.Events::TYPE_TRAINING),
                ),
                1 => array(
                    'key' => Events::TYPE_MATCH,
                    'label' => $this->get('translator')->trans('constants.events.type.'.Events::TYPE_MATCH),
                ),
            )),
            'img_paths_json' => json_encode(Events::getImagePaths()),
            'is_allowed_to_edit' => json_encode($isAllowedToEdit),
        ));
    }

    public function getEventsAction(Request $request)
    {
        /* @var $user User */
        $user = $this->container->get('security.context')->getToken()->getUser();

        $events = $this->getDoctrine()->getRepository('FrontBundle:Events')->findAll();
        $res = array();
        $i=0;

        foreach($events as $event) {
            $res[$i] = array(
                "id" => $event->getSchedulerId(),
                "text" => $event->getText(),
                "start_date" => $event->getStartDate()->format('Y-m-d H:i'),
                "end_date" => $event->getEndDate()->format('Y-m-d H:i'),
                "type" => $event->getType(),
                "type_name" => $this->get('translator')->trans('constants.events.type.'.$event->getType()),
                "image" => $event->getImageFromType(),
            );

            if ($event->getTeam() instanceof Team) {
                $res[$i]["team"] = $event->getTeam()->getId();
                $res[$i]["team_name"] = $event->getTeam()->getName();
            }

            // It's a vacation, always readonly
            if ($event->getType() == Events::TYPE_CLOSED) {
                $res[$i]["readonly"] = true;
                $res[$i]["event_bar_text"] = "<div class=\"relative\"><img src=\"" . $event->getImageFromType() . "\"> <strong> Fermé : </strong> " . $event->getText() . "</div>";
            } else {
                $res[$i]["readonly"] = $event->isReadonly($user);
                $res[$i]["event_bar_text"] = "<div class=\"relative\"><img src=\"" . $event->getImageFromType() . "\"> <strong> " . $event->getTeam()->getName() . "</strong> (" . $event->getStartDate()->format('H:i') . " à " . $event->getEndDate()->format('H:i') . ")</div>";
            }

            $i++;
        }

        $response = new Response(json_encode($res));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function saveEventsAction(Request $request) {
        /* @var $user User */
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (!$request->isXmlHttpRequest() || !$user instanceof User)
        {
            $msg ="<?xml version='1.0' encoding='utf-8' ?>\n";
            $msg .="<data>\n";
            $msg .='	<action type="error" sid="'.$theId.'" tid="'.$theId.'"/>'."\n";
            $msg .='	<message><![CDATA[Action invalide.]]></message>'."\n";
            $msg .="</data>\n";

            $response = new Response($msg);
            $response->headers->set('Content-Type', 'text/xml');
            $response->headers->set('Content-Disposition', 'data.xml');
            return $response;
        }

        $theId = $request->request->get('ids');
        $status = $request->request->get($theId . "_!nativeeditor_status");

        $team = null;
        if ($request->request->get($theId . "_team") != null) {
            $team = $this->getDoctrine()->getRepository('FrontBundle:Team')->find($request->request->get($theId . "_team"));
        }

        // Ajout d'un évènement
        if ($status == "inserted") {
            $event = new Events();
            $event->setStartDate(new \DateTime($request->request->get($theId . "_start_date")));
            $event->setEndDate(new \DateTime($request->request->get($theId . "_end_date")));
            $event->setType($request->request->get($theId . "_type"));
            $event->setSchedulerId($theId);
            $event->setTeam($team);

            if ($user->isAllowedToInsert($event) && $event->isValidForInsert())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
                $em->flush();

                $msg ="<?xml version='1.0' encoding='utf-8' ?>\n";
                $msg .="<data>\n";
                $msg .='	<action type="inserted" sid="'.$theId.'" tid="'.$theId.'"/>'."\n";
                $msg .='	<message><![CDATA[Evènement sauvegardé avec succès.]]></message>'."\n";
                $msg .="</data>\n";
            }
            else
            {
                $msg ="<?xml version='1.0' encoding='utf-8' ?>\n";
                $msg .="<data>\n";
                $msg .='	<action type="error" sid="'.$theId.'" tid="'.$theId.'"/>'."\n";
                $msg .='	<message><![CDATA[Vous ne pouvez pas effectuer cette action.]]></message>'."\n";
                $msg .="</data>\n";
            }

            $response = new Response($msg);
            $response->headers->set('Content-Type', 'text/xml');
            $response->headers->set('Content-Disposition', 'data.xml');

            return $response;
        }
        elseif ($status == "updated") {
            $event = $this->getDoctrine()->getRepository('FrontBundle:Events')->findSingleBySchedulerId($theId);
            if (! $event instanceof Events) {
                $msg ="<?xml version='1.0' encoding='utf-8' ?>\n";
                $msg .="<data>\n";
                $msg .='	<action type="error" sid="'.$theId.'" tid="'.$theId.'"/>'."\n";
                $msg .='	<message><![CDATA[Evènement invalide.]]></message>'."\n";
                $msg .="</data>\n";

                $response = new Response($msg);
                $response->headers->set('Content-Type', 'text/xml');
                $response->headers->set('Content-Disposition', 'data.xml');
                return $response;
            }

            $event->setStartDate(new \DateTime($request->request->get($theId . "_start_date")));
            $event->setEndDate(new \DateTime($request->request->get($theId . "_end_date")));
            $event->setType($request->request->get($theId . "_type"));
            $event->setTeam($team);

            if ($user->isAllowedToUpdate($event) && $event->isValidForUpdate())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
                $em->flush();

                $msg ="<?xml version='1.0' encoding='utf-8' ?>\n";
                $msg .="<data>\n";
                $msg .='	<action type="updated" sid="'.$theId.'" tid="'.$theId.'"/>'."\n";
                $msg .='	<message><![CDATA[Evènement sauvegardé avec succès.]]></message>'."\n";
                $msg .="</data>\n";
            }
            else
            {
                $msg ="<?xml version='1.0' encoding='utf-8' ?>\n";
                $msg .="<data>\n";
                $msg .='	<action type="error" sid="'.$theId.'" tid="'.$theId.'"/>'."\n";
                $msg .='	<message><![CDATA[Vous ne pouvez pas effectuer cette action.]]></message>'."\n";
                $msg .="</data>\n";
            }

            $response = new Response($msg);
            $response->headers->set('Content-Type', 'text/xml');
            $response->headers->set('Content-Disposition', 'data.xml');

            return $response;
        } elseif ($status == "deleted") {
            $event = $this->getDoctrine()->getRepository('FrontBundle:Events')->findSingleBySchedulerId($theId);

            if (! $event instanceof Events) {
                $msg ="<?xml version='1.0' encoding='utf-8' ?>\n";
                $msg .="<data>\n";
                $msg .='	<action type="error" sid="'.$theId.'" tid="'.$theId.'"/>'."\n";
                $msg .='	<message><![CDATA[Evènement invalide.]]></message>'."\n";
                $msg .="</data>\n";

                $response = new Response($msg);
                $response->headers->set('Content-Type', 'text/xml');
                $response->headers->set('Content-Disposition', 'data.xml');
                return $response;
            }

            if ($user->isAllowedToUpdate($event))
            {
                $em = $this->getDoctrine()->getManager();
                $em->remove($event);
                $em->flush();

                $msg ="<?xml version='1.0' encoding='utf-8' ?>\n";
                $msg .="<data>\n";
                $msg .='	<action type="deleted" sid="'.$theId.'" tid="'.$theId.'"/>'."\n";
                $msg .='	<message><![CDATA[Evènement supprimé avec succès.]]></message>'."\n";
                $msg .="</data>\n";
            }
            else
            {
                $msg ="<?xml version='1.0' encoding='utf-8' ?>\n";
                $msg .="<data>\n";
                $msg .='	<action type="error" sid="'.$theId.'" tid="'.$theId.'"/>'."\n";
                $msg .='	<message><![CDATA[Vous ne pouvez pas effectuer cette action.]]></message>'."\n";
                $msg .="</data>\n";
            }

            $response = new Response($msg);
            $response->headers->set('Content-Type', 'text/xml');
            $response->headers->set('Content-Disposition', 'data.xml');

            return $response;
        }
        exit;
    }
}
