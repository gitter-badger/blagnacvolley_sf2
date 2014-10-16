<?php

namespace BlagnacVolley\WebsiteBundle\Controller;

use BlagnacVolley\WebsiteBundle\Form\Type\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BlagnacVolleyWebsiteBundle:Default:index.html.twig', array());
    }

    public function staticAction($page)
    {
        $tpl = '    :static:'.$page.'.html.twig';
        if ($this->container->get('templating')->exists($tpl)) {
            $response = $this->render($tpl, array());
        } else {
            throw $this->createNotFoundException('Page not found');
        }
        return $response;
    }

    /**
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(new ContactFormType());

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $message = \Swift_Message::newInstance()
                    ->setSubject("[BlagnacVolley] Nouveau message depuis le formulaire de contacts")
                    ->setFrom($form->get('email')->getData())
                    ->setTo('y.lastapis@gmail.com')
                    ->setContentType('text/html')
                    ->setBody(
                        $this->renderView(
                            'BlagnacVolleyWebsiteBundle:Mail:contact.html.twig',
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

        return $this->render('BlagnacVolleyWebsiteBundle:Default:contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
