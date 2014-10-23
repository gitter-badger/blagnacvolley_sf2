<?php

namespace BV\FrontBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;

use BV\FrontBundle\Entity\FileImage;
use BV\FrontBundle\Form\Type\RegistrationFormType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{
    public function registerAction()
    {
        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);
        if ($process) {

            $file = new FileImage();
            $em = $this->container->get('doctrine')->getManager();
            $em->persist($file);
            $em->flush();

            $user = $form->getData();

            $authUser = false;
            if ($confirmationEnabled) {
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $route = 'fos_user_registration_check_email';
            } else {
                $authUser = true;
                $route = 'fos_user_registration_confirmed';
            }

            $this->setFlash('fos_user_success', 'registration.flash.user_created');
            $url = $this->container->get('router')->generate($route);
            $response = new RedirectResponse($url);

            if ($authUser) {
                $this->authenticateUser($user, $response);
            }

            return $response;
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.'.$this->getEngine(), array(
            'form' => $form->createView(),
        ));
    }



//    public function uploadPictureAction(Request $request)
//    {
//        $file = new File();
//        $form = $this->createForm(new RegistrationFormType());
//
//        if ($request->isMethod('POST')) {
//            $form->handleRequest($request);
//
//            if ($form->isValid()) {
//                $em = $this->getDoctrine()->getManager();
//
//                $em->persist($file);
//                $em->flush();
//
//                return $this->redirect($this->generateUrl('register'));
//            }
//        }
//
//        return array('form' => $form->createView());
//    }
}
