<?php

namespace BV\FrontBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use BV\FrontBundle\Services\Email;

class ProfileController extends BaseController
{
    /**
     * Edit the user
     */
    public function editAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->container->get('fos_user.profile.form');
        $formHandler = $this->container->get('fos_user.profile.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('fos_user_success', 'profile.flash.updated');

            return new RedirectResponse($this->getRedirectionUrl($user));
        }

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
            array(
                'form' => $form->createView(),
                'user' => $user
            )
        );
    }

    public function updateRequestAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->container->get('fos_user.profile.form');

        $params = $request->request->get('fos_user_profile_form');
        if (!array_key_exists('message', $params) || $params['message'] == '')
        {
            $request->getSession()->getFlashBag()->add('error', 'Un message est requis' );
        }

        $this->container->get('bv_mailer')->sendInformationsChangedEmail(
            $user, $params['message']
        );

        $request->getSession()->getFlashBag()->add('success', 'Votre message a bien été envoyé' );

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
            array(
                'form' => $form->createView(),
                'user' => $user
            )
        );
    }
}
