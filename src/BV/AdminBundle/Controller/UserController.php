<?php

namespace BV\AdminBundle\Controller;

use BV\FrontBundle\Doctrine\UserManager;
use BV\FrontBundle\Entity\User;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends CRUDController
{
    /**
     * Create action
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function createAction()
    {
        // the key used to lookup the template
        $templateKey = 'edit';

        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $object = $this->admin->getNewInstance();

        $this->admin->setSubject($object);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->admin->getForm();

        /* @var $object User */
        $tokenGenerator = $this->container->get('fos_user.util.token_generator');
        $object->setPlainPassword(substr($tokenGenerator->generateToken(), 0, 8));
        $userManager = $this->container->get('fos_user.user_manager'); /* @var $userManager UserManager */
        $userManager->updatePassword($object);
        $object->setPlainPassword(substr($tokenGenerator->generateToken(), 0, 8));
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
                    $object = $this->admin->create($object);

                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson(array(
                            'result' => 'ok',
                            'objectId' => $this->admin->getNormalizedIdentifier($object)
                        ));
                    }

                    $this->addFlash(
                        'sonata_flash_success',
                        $this->admin->trans(
                            'flash_create_success',
                            array('%name%' => $this->escapeHtml($this->admin->toString($object))),
                            'SonataAdminBundle'
                        )
                    );

                    // redirect to edit mode
                    return $this->redirectTo($object);

                } catch (ModelManagerException $e) {
                    $this->logModelManagerException($e);

                    $isFormValid = false;
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash(
                        'sonata_flash_error',
                        $this->admin->trans(
                            'flash_create_error',
                            array('%name%' => $this->escapeHtml($this->admin->toString($object))),
                            'SonataAdminBundle'
                        )
                    );
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction(Request $request)
    {
        $id = $request->get('id');
        $user = $this->getDoctrine()->getRepository('FrontBundle:User')->find($id);
        if ($user != null)
        {
            $user->setEnabled(false);
            $user->setStatus(User::STATUS_INACTIVE);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
                $this->container->get('bv_mailer')->sendAccountDeactivated($user);
            $this->container->get('session')->getFlashBag()->add('success', 'Utilisateur '.$user->getFirstname().' '.$user->getLastname().' désactivé avec succès');
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reactivateAction(Request $request)
    {
        $id = $request->get('id');
        $user = $this->getDoctrine()->getRepository('FrontBundle:User')->find($id);

        if ($user != null)
        {
            $user->setEnabled(true);
            $user->setStatus(User::STATUS_ACTIVE_NOT_LICENSED);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->container->get('bv_mailer')->sendAccountReactivated($user);
            $this->container->get('session')->getFlashBag()->add('success', 'Utilisateur '.$user->getFirstname().' '.$user->getLastname().' réactivé avec succès');
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function validateRenewalAction(Request $request)
    {
        $id = $request->get('id');
        $user = $this->getDoctrine()->getRepository('FrontBundle:User')->find($id);
        if ($user != null)
        {
            $user->setStatus(User::STATUS_ACTIVE_WAITING_LICENSE);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->container->get('bv_mailer')->sendLicenseRenewalValidated($user);
            $this->container->get('session')->getFlashBag()->add('success', 'Utilisateur '.$user->getFirstname().' '.$user->getLastname().' correctement mis à jour.');
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function refuseRenewalAction(Request $request)
    {
        $id = $request->get('id');
        $message = $request->get('message');

        $user = $this->getDoctrine()->getRepository('FrontBundle:User')->find($id);

        if ($user == null)
        {
            $this->container->get('session')->getFlashBag()->add('error', 'Utilisateur inconnu');
        }

        if ($message == null)
        {
            $this->container->get('session')->getFlashBag()->add('error', 'Un message est requis');
        }

        if ($user != null && $message != null)
        {
            $user->setStatus(User::STATUS_ACTIVE_NOT_LICENSED);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->container->get('bv_mailer')->sendLicenseRenewalRefused($user, $message);
            $this->container->get('session')->getFlashBag()->add('success', 'Utilisateur '.$user->getFirstname().' '.$user->getLastname().' correctement mis à jour. Il a été notifié du refus de dossier.');
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function validateLicenseAction(Request $request)
    {
        $id = $request->get('id');
        $user = $this->getDoctrine()->getRepository('FrontBundle:User')->find($id);

        if ($user->getLicenseNumber() == '')
        {
            $this->container->get('session')->getFlashBag()->add('error', 'Vous n\'avez pas saisi le numéro de license');
        }

        if ($user != null && $user->getLicenseNumber() != '')
        {
            $user->setStatus(User::STATUS_ACTIVE_LICENSED);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->container->get('session')->getFlashBag()->add('success', 'Utilisateur '.$user->getFirstname().' '.$user->getLastname().' correctement mis à jour. Il peut maintenant avoir accès aux fonctionnalités restreintes du site.');
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    private function logModelManagerException($e)
    {
        $context = array('exception' => $e);
        if ($e->getPrevious()) {
            $context['previous_exception_message'] = $e->getPrevious()->getMessage();
        }
        $this->getLogger()->error($e->getMessage(), $context);
    }
}
