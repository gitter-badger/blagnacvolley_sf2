<?php

namespace BV\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BV\FrontBundle\Form\Type\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CalendarController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm(new ContactType());

        return $this->render('FrontBundle:Calendar:index.html.twig', array(
            'form' => $form->createView(),
            'calendarid' => 1,
        ));
    }

    public function getEventsAction(Request $request)
    {
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
            $msg .="		<team>" . $event->getTeam()->getName() . "</team>\n";
            $msg .="	</event>\n";
        }
        $msg .="</data>\n";
        $response = new Response($msg);
        $response->headers->set('Content-Type', 'text/xml');
        $response->headers->set('Content-Disposition', 'data.xml');

        return $response;
    }

    public function saveEventsAction(sfWebRequest $request) {
        $process = ProcessPeer::retrieveByPK($request->getParameter('rpId'));

        $this->setLayout(false);

        if ((!$process instanceof Process) || (!$process->isValid($this->getUser()->getId()))) {
            return;
        }

        $theId = $request->getPostParameter('ids');
        $status = $request->getPostParameter($theId . "_!nativeeditor_status");
        if ($status == "inserted") {
            $event = new Event();
            $event->setProcess($process);
            $event->setDateBegin($request->getPostParameter($theId . "_start_date"));
            $event->setDateEnd($request->getPostParameter($theId . "_end_date"));
            $event->setSchedulerId($theId);
            $event->save();

            if ($process->getStep() > 1 && !$process->getIsDirt()) {
                $process->setIsDirt(true);
                $process->save();
            }

            header("Content-Type:text/xml");
            header('Content-Disposition: attachment; filename="data.xml"');

            echo $this->_buildMessage('inserted', $theId);
        } elseif ($status == "updated") {
            $event = EventPeer::retrieveBySchedulerId($theId);
            if (! $event instanceof Event)
                return;

            $event->setDateBegin($request->getPostParameter($theId . "_start_date"));
            $event->setDateEnd($request->getPostParameter($theId . "_end_date"));
            $event->setSchedulerId($theId);
            $event->save();

            $pms = ProcessMemberAvailabilityPeer::retrieveByEventId($event->getId());
            foreach ($pms as $pm) /* @var $pm ProcessMemberAvailability */
            {
                $pm->setIsRead(false);
                $pm->setIsAvailable(false);
                $pm->save();
            }

            // Tag current process as Dirt to process again mail sending
            if (!$process->getIsDirt()) {
                $process->setIsDirt(true);
                $process->save();
            }

            header("Content-Type:text/xml");
            header('Content-Disposition: attachment; filename="data.xml"');

            echo $this->_buildMessage('updated', $theId);
        } elseif ($status == "deleted") {
            $event = EventPeer::retrieveBySchedulerId($theId);
            if (! $event instanceof Event)
                return;

            $event->delete();

            if (count(EventPeer::retrieveByProcessIds($process->getId())) == 0 && $process->getStep() != 1)
            {
                $process->resetProcess();
                $process->setStep(1);
                $process->setIsDirt(false);
                $process->save();
            }

            header("Content-Type:text/xml");
            header('Content-Disposition: attachment; filename="data.xml"');

            echo $this->_buildMessage('deleted', $theId);
        }
        exit;
    }
}