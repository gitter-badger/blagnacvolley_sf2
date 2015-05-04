<?php

namespace BV\AdminBundle\Controller;

use BV\FrontBundle\Entity\Events;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CalendarController extends Controller
{
    public function listAction()
    {
        $this->get('sonata.notification.backend')->createAndPublish('logger', array(
            'level' => 'debug',
            'message' => 'Hello world!'
        ));

        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();
        $types = Events::getEventsTypeAsOptions();
        array_walk($types, function(&$value, $key){
            $value['label'] = $this->get('translator')->trans($value['label']);
        });

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('list'), array(
            'action'     => 'list',
            'form'       => $formView,
            'datagrid'   => $datagrid,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
            'alert_opts_teams_json' => json_encode($this->getDoctrine()->getRepository('FrontBundle:Team')->findAllAsOptions()),
            'alert_opts_types_json' => json_encode($types),
            'img_paths_json' => json_encode(Events::getImagePaths()),
            'is_allowed_to_edit' => json_encode(true),
        ));
    }
}
