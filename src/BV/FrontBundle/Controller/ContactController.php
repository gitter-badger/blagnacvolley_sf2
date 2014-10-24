<?php

namespace BV\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BV\FrontBundle\Form\Type\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ContactController extends Controller
{
    public function contactAction(Request $request)
    {
        $form = $this->createForm(new ContactType());

        return $this->render('FrontBundle:Contact:contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function contactPostAction(Request $request)
    {
        $form = $this->createForm(new ContactType());

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $registration = $form->getData();

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
        ));
    }


}
