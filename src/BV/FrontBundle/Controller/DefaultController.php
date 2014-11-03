<?php

namespace BV\FrontBundle\Controller;

use BV\FrontBundle\Entity\CmsPage;
use BV\FrontBundle\Entity\User;
use BV\FrontBundle\Form\CmsPageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FrontBundle:Default:index.html.twig', array());
    }

    public function volleySchoolAction()
    {
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        /* @var CmsPage $cmsPage */
        $cmsPage = $this->getDoctrine()->getRepository('FrontBundle:CmsPage')->findSingleByName(CmsPage::STATIC_PAGE_VOLLEYSCHOOL);

        return $this->render('FrontBundle:Default:volleyschool.html.twig', array(
            'allowed' => $this->getDoctrine()->getRepository('FrontBundle:User')->isAllowedToEditCmsPages($user),
            'content' => $cmsPage->getContent(),
        ));
    }

    public function volleySchoolEditAction(Request $request)
    {
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Redirect to consultation if not logged or not allowed to edit
        if (!$user instanceof User || !$user->isSuperAdmin())
        {
            return $this->redirect($this->generateUrl('bv_static_volley_school'), 302);
        }

        /* @var CmsPage $cmsPage */
        $cmsPage = $this->getDoctrine()->getRepository('FrontBundle:CmsPage')->findSingleByName(CmsPage::STATIC_PAGE_VOLLEYSCHOOL);
        $form = $this->createForm(new CmsPageFormType(), $cmsPage);

        if ('POST' === $request->getMethod())
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($cmsPage);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Modifications sauvegardées avec succès' );

                return $this->redirect($this->generateUrl('bv_static_volley_school'), 302);
            }
        }

        return $this->render('FrontBundle:Default:volleyschool_edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function jeuLibreAction()
    {
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        /* @var CmsPage $cmsPage */
        $cmsPage = $this->getDoctrine()->getRepository('FrontBundle:CmsPage')->findSingleByName(CmsPage::STATIC_PAGE_FREE_GAME);

        return $this->render('FrontBundle:Default:jeulibre.html.twig', array(
            'allowed' => $this->getDoctrine()->getRepository('FrontBundle:User')->isAllowedToEditCmsPages($user),
            'content' => $cmsPage->getContent(),
        ));
    }

    public function jeuLibreEditAction(Request $request)
    {
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Redirect to consultation if not logged or not allowed to edit
        if (!$user instanceof User || !$user->isSuperAdmin())
        {
            return $this->redirect($this->generateUrl('bv_static_jeu_libre'), 302);
        }

        /* @var CmsPage $cmsPage */
        $cmsPage = $this->getDoctrine()->getRepository('FrontBundle:CmsPage')->findSingleByName(CmsPage::STATIC_PAGE_FREE_GAME);
        $form = $this->createForm(new CmsPageFormType(), $cmsPage);

        if ('POST' === $request->getMethod())
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($cmsPage);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Modifications sauvegardées avec succès' );

                return $this->redirect($this->generateUrl('bv_static_jeu_libre'), 302);
            }
        }

        return $this->render('FrontBundle:Default:jeulibre_edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function addressesAction()
    {
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        /* @var CmsPage $cmsPage */
        $cmsPage = $this->getDoctrine()->getRepository('FrontBundle:CmsPage')->findSingleByName(CmsPage::STATIC_PAGE_ADDRESSES);

        return $this->render('FrontBundle:Default:addresses.html.twig', array(
            'allowed' => $this->getDoctrine()->getRepository('FrontBundle:User')->isAllowedToEditCmsPages($user),
            'content' => $cmsPage->getContent(),
        ));
    }

    public function addressesEditAction(Request $request)
    {
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Redirect to consultation if not logged or not allowed to edit
        if (!$user instanceof User || !$user->isSuperAdmin())
        {
            return $this->redirect($this->generateUrl('bv_static_addresses'), 302);
        }

        /* @var CmsPage $cmsPage */
        $cmsPage = $this->getDoctrine()->getRepository('FrontBundle:CmsPage')->findSingleByName(CmsPage::STATIC_PAGE_ADDRESSES);
        $form = $this->createForm(new CmsPageFormType(), $cmsPage);

        if ('POST' === $request->getMethod())
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($cmsPage);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Modifications sauvegardées avec succès' );

                return $this->redirect($this->generateUrl('bv_static_addresses'), 302);
            }
        }

        return $this->render('FrontBundle:Default:addresses_edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function scheduleAction()
    {
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        /* @var CmsPage $cmsPage */
        $cmsPage = $this->getDoctrine()->getRepository('FrontBundle:CmsPage')->findSingleByName(CmsPage::STATIC_PAGE_SCHEDULE);

        return $this->render('FrontBundle:Default:schedule.html.twig', array(
            'allowed' => $this->getDoctrine()->getRepository('FrontBundle:User')->isAllowedToEditCmsPages($user),
            'content' => $cmsPage->getContent(),
        ));
    }

    public function scheduleEditAction(Request $request)
    {
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Redirect to consultation if not logged or not allowed to edit
        if (!$user instanceof User || !$user->isSuperAdmin())
        {
            return $this->redirect($this->generateUrl('bv_static_schedule'), 302);
        }

        /* @var CmsPage $cmsPage */
        $cmsPage = $this->getDoctrine()->getRepository('FrontBundle:CmsPage')->findSingleByName(CmsPage::STATIC_PAGE_SCHEDULE);
        $form = $this->createForm(new CmsPageFormType(), $cmsPage);

        if ('POST' === $request->getMethod())
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($cmsPage);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Modifications sauvegardées avec succès' );

                return $this->redirect($this->generateUrl('bv_static_schedule'), 302);
            }
        }

        return $this->render('FrontBundle:Default:schedule_edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    public function CmsPageUploadAction(Request $request)
    {
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Redirect to consultation if not logged or not allowed to edit
        if (!$user instanceof User || !$user->isSuperAdmin())
        {
            $response = new Response(json_encode(""));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $dir = __DIR__.'/../../../../web/uploads/files/';
        $array = array();
        foreach($request->files as $uploadedFile) {
            /* @var UploadedFile $uploadedFile */
            $file = $uploadedFile->move($dir, $uploadedFile->getClientOriginalName());
            $array = array(
                'filelink' => $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath()."/uploads/files/".$uploadedFile->getClientOriginalName(),
                'filename' => $uploadedFile->getClientOriginalName()
            );
        }

        $response = new Response(json_encode($array));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
