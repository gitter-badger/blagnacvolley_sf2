<?php

namespace BV\AdminBundle\Controller;

use BV\FrontBundle\Entity\Events;
use BV\FrontBundle\Entity\News;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class NewsAdminController extends Controller
{
    public function createAction()
    {
        // the key used to lookup the template
        $templateKey = 'edit';

        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $object = $this->admin->getNewInstance(); /* @var $object News */

        $this->admin->setSubject($object);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->admin->getForm();
        $form->setData($object);

        if ($this->getRestMethod()== 'POST') {
            $form->submit($this->get('request'));

            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                if (false === $this->admin->isGranted('CREATE', $object)) {
                    throw new AccessDeniedException();
                }

                try {
                    // ==================
                    // START - CUSTOM YLS
                    //
                    // Link to Event if filled
                    $uniqid = $this->get('request')->query->get('uniqid');
                    if ($this->get('request')->request->get($uniqid) != null)
                    {
                        $params = $this->get('request')->request->get($uniqid);
                        if (  $params['level'] != null && $params['start_date'] != null && $params['end_date'] != null && array_key_exists($params['level'], Events::getEventsType())) {
                            $level = $params['level'];
                            $startDate = new \DateTime($params['start_date']);
                            $endDate = new \DateTime($params['end_date']);
                            if ($startDate < $endDate) {
                                throw new ModelManagerException('Dates invalides');
                            }
                            else
                            {
                                $events = new Events();
                                $events->setStartDate($startDate);
                                $events->setEndDate($endDate);
                                $events->setType($level);
                                $events->setTeam(null);
                                $this->admin->create($events);

                                $object->setEventsId($events);
                            }
                        }
                    }
                    // END - CUSTOM YLS
                    // ================

                    $object = $this->admin->create($object);

                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson(array(
                            'result' => 'ok',
                            'objectId' => $this->admin->getNormalizedIdentifier($object)
                        ));
                    }

                    $this->addFlash('sonata_flash_success', $this->admin->trans('flash_create_success', array('%name%' => $this->admin->toString($object)), 'SonataAdminBundle'));

                    // redirect to edit mode
                    return $this->redirectTo($object);

                } catch (ModelManagerException $e) {
                    $isFormValid = false;
                    $this->addFlash('sonata_flash_error', $e->getMessage());
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash('sonata_flash_error', $this->admin->trans('flash_create_error', array('%name%' => $this->admin->toString($object)), 'SonataAdminBundle'));
                }
            } elseif ($this->isPreviewRequested()) {
                // pick the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate($templateKey), array(
            'action' => 'create',
            'form'   => $view,
            'object' => $object,
        ));
    }

    /**
     * Edit action
     *
     * @param int|string|null $id
     *
     * @return Response|RedirectResponse
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function editAction($id = null)
    {
        // the key used to lookup the template
        $templateKey = 'edit';

        $id = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id); /* @var $object News */

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('EDIT', $object)) {
            throw new AccessDeniedException();
        }

        $this->admin->setSubject($object);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->admin->getForm();
        $form->setData($object);

        if ($this->getRestMethod() == 'POST') {
            $form->submit($this->get('request'));

            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {

                try {
                    $object = $this->admin->update($object);

                    // ==================
                    // START - CUSTOM YLS
                    //
                    $uniqid = $this->get('request')->query->get('uniqid');
                    if ($this->get('request')->request->get($uniqid) != null)
                    {
                        $params = $this->get('request')->request->get($uniqid);
                        if (array_key_exists('level', $params) && array_key_exists('start_date', $params) && array_key_exists('end_date', $params)) {

                            // An optional event is associated to this news
                            if ( $params['level'] != null && $params['start_date'] != null && $params['end_date'] != null && array_key_exists($params['level'], Events::getEventsType())) {
                                $level = $params['level'];
                                $startDate = \DateTime::createFromFormat('d/m/Y G:i', $params['start_date']);
                                $endDate = \DateTime::createFromFormat('d/m/Y G:i', $params['end_date']);
                                if ($startDate > $endDate) {
                                    throw new ModelManagerException('Dates invalides');
                                }
                                else
                                {
                                    if ($object->getEventsId() != null)
                                    {
                                        $events = $this->getDoctrine()->getRepository('FrontBundle:Events')->find($object->getEventsId());

                                        $events->setStartDate($startDate);
                                        $events->setEndDate($endDate);
                                        $events->setType($level);
                                        $events->setTeam(null);

                                        $this->admin->update($events);
                                    }
                                    else
                                    {
                                        $events = new Events();
                                        $events->setStartDate($startDate);
                                        $events->setEndDate($endDate);
                                        $events->setType($level);
                                        $events->setTeam(null);

                                        $this->admin->create($events);

                                        $object->setEventsId($events);
                                        $this->admin->update($object);
                                    }
                                }
                            }
                        }
                        else
                        {
                            throw new ModelManagerException('Paramètres invalides');
                        }
                    }
                    // END - CUSTOM YLS
                    // ================


                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson(array(
                            'result'    => 'ok',
                            'objectId'  => $this->admin->getNormalizedIdentifier($object)
                        ));
                    }

                    $this->addFlash('sonata_flash_success', $this->admin->trans('flash_edit_success', array('%name%' => $this->admin->toString($object)), 'SonataAdminBundle'));

                    // redirect to edit mode
                    return $this->redirectTo($object);

                } catch (ModelManagerException $e) {

                    $isFormValid = false;
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash('sonata_flash_error', $this->admin->trans('flash_edit_error', array('%name%' => $this->admin->toString($object)), 'SonataAdminBundle'));
                }
            } elseif ($this->isPreviewRequested()) {
                // enable the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate($templateKey), array(
            'action' => 'edit',
            'form'   => $view,
            'object' => $object,
        ));
    }
}
