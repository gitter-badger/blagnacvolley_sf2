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
            return $this->redirect($this->generateUrl('bv_static_volley_school'), 401);
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

                return $this->render('FrontBundle:Default:volleyschool.html.twig', array(
                    'allowed' => $this->getDoctrine()->getRepository('FrontBundle:User')->isAllowedToEditCmsPages($user),
                    'content' => $cmsPage->getContent(),
                ));
            }
        }

        return $this->render('FrontBundle:Default:volleyschool_edit.html.twig', array(
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
