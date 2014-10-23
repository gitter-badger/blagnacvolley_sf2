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

                $message = \Swift_Message::newInstance()
                    ->setSubject("[BlagnacVolley] Nouveau message depuis le formulaire de contacts")
                    ->setFrom($form->get('email')->getData())
                    ->setTo('y.lastapis@gmail.com')
                    ->setContentType('text/html')
                    ->setBody(
                        $this->renderView(
                            'FrontBundle:Mail:contact.html.twig',
                            array(
                                'name' => $form->get('name')->getData(),
                                'email' => $form->get('email')->getData(),
                                'message' => $form->get('message')->getData()
                            )
                        ),
                        'text/html'
                    );

                $this->get('mailer')->send($message);

                $request->getSession()->getFlashBag()->add('success', 'Votre email a bien été envoyé ! Merci !');

                return $this->redirect($this->generateUrl('contact'));
            }
        }

        return $this->render('FrontBundle:Contact:contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }


}
