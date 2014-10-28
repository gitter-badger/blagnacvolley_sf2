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

        $alertOptsTeamsJson = "";
        if ($user instanceof User) {
            $res = array();
            foreach ($this->getDoctrine()->getRepository('FrontBundle:Team')->findAllByUserId($user->getId()) as $team)
            {
                $res[] = array(
                    'key' => $team['id'],
                    'label' => $team['name']
                );
            }
            $alertOptsTeamsJson = json_encode($res);
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
        ));
    }

    public function getEventsAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        $events = $this->getDoctrine()->getRepository('FrontBundle:Events')->findAll();

        $msg = "<?xml version='1.0' encoding='utf-8' ?>\n";
        $msg .="<data>\n";
        foreach($events as $event) {
            $msg .="	<event id=\"" . $event->getId() . "\">\n";
            $msg .="		<text>" . $event->getText() . "</text>\n";
            $msg .="		<start_date>" . $event->getStartDate()->format('Y-m-d H:i') . "</start_date>\n";
            $msg .="		<end_date>" . $event->getEndDate()->format('Y-m-d H:i') . "</end_date>\n";
            $msg .="		<type>" . $event->getType() . "</type>\n";
            $msg .="		<image>" . $event->getImageFromType() . "</image>\n";
            if ($event->getTeam() instanceof Team) {
                $msg .="		<team>" . $event->getTeam()->getName() . "</team>\n";
            }
            // It's a vacation, always readonly
            if ($event->getType() == Events::TYPE_CLOSED) {
                $msg .="		<readonly>true</readonly>\n";
            } else {
                // Only editable if associated to current User's team && user is captain or subcaptain
                if ($user instanceof User &&
                    $event->getTeam() != null && (
                        (
                            $event->getTeam()->getCaptain() instanceof User &&
                            $event->getTeam()->getCaptain()->getId() == $user->getId()
                        ) ||
                        (
                            $event->getTeam()->getSubCaptain() instanceof User &&
                            $event->getTeam()->getSubCaptain()->getId() == $user->getId()
                        )
                    )) {
                    $msg .="		<readonly>false</readonly>\n";
                } else {
                    $msg .="		<readonly>true</readonly>\n";
                }
            }
            $msg .="	</event>\n";
        }
        $msg .="</data>\n";
        $response = new Response($msg);
        $response->headers->set('Content-Type', 'text/xml');
        $response->headers->set('Content-Disposition', 'data.xml');

        return $response;
    }

    public function saveEventsAction(Request $request) {
        if (!$request->isXmlHttpRequest())
        {
            $response = new Response("");
            $response->headers->set('Content-Type', 'text/xml');
            $response->headers->set('Content-Disposition', 'data.xml');
            return $response;
        }

        $theId = $request->request->get('ids');
        $status = $request->request->get($theId . "_!nativeeditor_status");

        if ($status == "inserted") {
            $event = new Events();
            $event->setStartDate(new \DateTime($request->request->get($theId . "_start_date")));
            $event->setEndDate(new \DateTime($request->request->get($theId . "_end_date")));
            $event->setType(Events::TYPE_TRAINING);
            $event->setSchedulerId($theId);

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            $msg ="<?xml version='1.0' encoding='utf-8' ?>\n";
            $msg .="<data>\n";
            $msg .='	<action type="inserted" sid="'.$theId.'" tid="'.$theId.'"/>\n';
            $msg .="</data>\n";

            $response = new Response($msg);
            $response->headers->set('Content-Type', 'text/xml');
            $response->headers->set('Content-Disposition', 'data.xml');

            return $response;
        }
//        elseif ($status == "updated") {
//            $event = EventPeer::retrieveBySchedulerId($theId);
//            if (! $event instanceof Event)
//                return;
//
//            $event->setDateBegin($request->getPostParameter($theId . "_start_date"));
//            $event->setDateEnd($request->getPostParameter($theId . "_end_date"));
//            $event->setSchedulerId($theId);
//            $event->save();
//
//            $pms = ProcessMemberAvailabilityPeer::retrieveByEventId($event->getId());
//            foreach ($pms as $pm) /* @var $pm ProcessMemberAvailability */
//            {
//                $pm->setIsRead(false);
//                $pm->setIsAvailable(false);
//                $pm->save();
//            }
//
//            // Tag current process as Dirt to process again mail sending
//            if (!$process->getIsDirt()) {
//                $process->setIsDirt(true);
//                $process->save();
//            }
//
//            header("Content-Type:text/xml");
//            header('Content-Disposition: attachment; filename="data.xml"');
//
//            echo $this->_buildMessage('updated', $theId);
//        } elseif ($status == "deleted") {
//            $event = EventPeer::retrieveBySchedulerId($theId);
//            if (! $event instanceof Event)
//                return;
//
//            $event->delete();
//
//            if (count(EventPeer::retrieveByProcessIds($process->getId())) == 0 && $process->getStep() != 1)
//            {
//                $process->resetProcess();
//                $process->setStep(1);
//                $process->setIsDirt(false);
//                $process->save();
//            }
//
//            header("Content-Type:text/xml");
//            header('Content-Disposition: attachment; filename="data.xml"');
//
//            echo $this->_buildMessage('deleted', $theId);
//        }
        exit;
    }
}
