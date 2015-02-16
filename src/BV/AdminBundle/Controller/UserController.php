<?php

namespace BV\AdminBundle\Controller;

use BV\FrontBundle\Entity\User;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends CRUDController
{
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
            $this->container->get('session')->getFlashBag()->add('success', 'Utilisateur '.$user->getFirstname().' '.$user->getLastname().' correctement mis à jour. Il peut maintenant avoir accès aux fonctionnalités restreintes du site.');
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
        if ($user != null)
        {
            $user->setStatus(User::STATUS_ACTIVE_WAITING_LICENSE);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->container->get('bv_mailer')->sendLicenseRenewalRefused($user, $message);
            $this->container->get('session')->getFlashBag()->add('success', 'Utilisateur '.$user->getFirstname().' '.$user->getLastname().' correctement mis à jour. Il a été notifié du refus de dossier.');
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}
