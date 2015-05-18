<?php

namespace BV\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BV\FrontBundle\Form\Type\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ContactController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(new ContactType());

        return $this->render('FrontBundle:Contact:contact.html.twig', array(
            'form' => $form->createView(),
            'public_key' => $this->container->getParameter('re_captcha.public_key')
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function contactPostAction(Request $request)
    {
        $form = $this->createForm(new ContactType());

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $recaptcha = $request->request->get('g-recaptcha-response');
                $res = $this->container->get('tool_recaptcha')->checkReCaptcha($recaptcha);

                if ($res != null)
                {
                    foreach ($res as $message)
                    {
                        $t = $this->get('translator')->trans($message);
                        $this->container->get('session')->getFlashBag()->add('error', $t);
                    }

                    return $this->render('FrontBundle:Contact:contact.html.twig', array(
                        'form' => $form->createView(),
                        'public_key' => $this->container->getParameter('re_captcha.public_key')
                    ));
                }

                $this->get('bv_mailer')->sendContactEmail(
                    $form->get('name')->getData(),
                    $form->get('email')->getData(),
                    $form->get('message')->getData()
                );

                $request->getSession()->getFlashBag()->add('success', 'Votre email a bien été envoyé ! Merci !');

                return $this->redirect($this->generateUrl('contact'));
            }
        }

        return $this->render('FrontBundle:Contact:contact.html.twig', array(
            'form' => $form->createView(),
            'public_key' => $this->container->getParameter('re_captcha.public_key')
        ));
    }
}
