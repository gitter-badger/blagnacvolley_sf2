<?php

namespace BV\FrontBundle\Controller;

use BV\FrontBundle\Entity\Availability;
use BV\FrontBundle\Entity\CmsPage;
use BV\FrontBundle\Entity\Events;
use BV\FrontBundle\Entity\User;
use BV\FrontBundle\Form\CmsPageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Tools\LogBundle\Entity\SystemLog;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FrontBundle:Default:index.html.twig', array());
    }

    public function adminNotificationsAction()
    {
        return $this->render('FrontBundle:Default:admin_notifications.html.twig', array(
            'notifications' => $this->getDoctrine()->getRepository('ToolsLogBundle:SystemLog')->countGroupByLevel(),
            'notificationsList' => $this->getDoctrine()->getRepository('ToolsLogBundle:SystemLog')->findAllOrderByLevel(),
            'levels' => SystemLog::getLevels(),
            'types' => SystemLog::getTypes()
        ));
    }

    public function volleySchoolAction()
    {
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        $content = [];
        $nb = $this->getDoctrine()->getRepository('FrontBundle:User')->countUsersByGroups();

        $content[Events::TYPE_VOLLEYSCHOOL_ADULT] = $this->_populateResults(CmsPage::STATIC_PAGE_VOLLEYSCHOOL_ADULTS, Events::TYPE_VOLLEYSCHOOL_ADULT);
        $content[Events::TYPE_VOLLEYSCHOOL_YOUTH] = $this->_populateResults(CmsPage::STATIC_PAGE_VOLLEYSCHOOL_YOUTH, Events::TYPE_VOLLEYSCHOOL_YOUTH);

        $cmsPage = $this->getDoctrine()->getRepository('FrontBundle:CmsPage')->findSingleByName(CmsPage::STATIC_PAGE_VOLLEYSCHOOL);

        return $this->render('FrontBundle:Volleyschool:volleyschool.html.twig', array(
            'allowed' => $this->getDoctrine()->getRepository('FrontBundle:User')->isAllowedToEditCmsPages($user),
            'user' => $user,
            'nb' => $nb,
            'content' => $content,
            'text' => ($cmsPage instanceof CmsPage ? $cmsPage->getContent() : null)
        ));
    }

    private function _populateResults($typeCms, $typeEvent)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $cmsPage = $this->getDoctrine()->getRepository('FrontBundle:CmsPage')->findSingleByName($typeCms);
        $event = $this->getDoctrine()->getRepository('FrontBundle:Events')->findEventsByType($typeEvent);

        return array(
            'cmsPage'         => $cmsPage->getContent(),
            'events'          => $event,
            'availabilities'  => $this->getDoctrine()->getRepository('FrontBundle:Availability')->countAvailabilities($event),
            'availability'    => $this->getDoctrine()->getRepository('FrontBundle:Availability')->findByUserAndEvent($user, $event),
            'users'           => $this->getDoctrine()->getRepository('FrontBundle:Availability')->findByEventAndAvailable($event),
        );
    }

    public function toggleAvailabilityAction(Request $request)
    {
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();
        $referer = $request->headers->get('referer');

        // Redirect to consultation if not logged or not allowed to edit
        if (!$user instanceof User)
        {
            return $this->redirect($referer);
        }

        if ('GET' === $request->getMethod())
        {
            if ($request->get('event') != null && $request->get('is_available') != null)
            {
                $event = $this->getDoctrine()->getRepository('FrontBundle:Events')->find($request->get('event'));
                if ($event instanceof Events)
                {
                    $availability = $this->getDoctrine()->getRepository('FrontBundle:Availability')->findByUserAndEvent($user, $event);
                    if (count($availability) == 0)
                    {
                        $availability = new Availability();
                        $availability->setUser($user);
                        $availability->setEvent($event);
                        $availability->setIsAvailable($request->get('is_available'));
                    }
                    else
                    {
                        $availability->setIsAvailable($request->get('is_available'));
                    }

                    $em = $this->container->get('doctrine.orm.entity_manager');
                    $em->persist($availability);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('success', 'Disponibilité enregistrée pour cette session' );
                }
                else
                {
                    $request->getSession()->getFlashBag()->add('error', 'Evènement invalide' );
                }
            }
            else
            {
                $request->getSession()->getFlashBag()->add('error', 'Paramètres invalides' );
            }
        }

        return $this->redirect($referer);
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

        if ('GET' === $request->getMethod())
        {
            $name = $request->get('name');
        }
        else
        {
            $name = $request->get('bv_frontbundle_cmspage')['name'];
        }

        /* @var CmsPage $cmsPage */
        $cmsPage = $this->getDoctrine()->getRepository('FrontBundle:CmsPage')->findSingleByName($name);
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

        return $this->render('FrontBundle:Volleyschool:volleyschool_edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function jeuLibreAction()
    {
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        $content = [];
        $nb = $this->getDoctrine()->getRepository('FrontBundle:User')->countUsersByGroups();

        $content[Events::TYPE_FREE_PLAY] = $this->_populateResults(CmsPage::STATIC_PAGE_FREE_GAME, Events::TYPE_FREE_PLAY);

        $cmsPage = $this->getDoctrine()->getRepository('FrontBundle:CmsPage')->findSingleByName(CmsPage::STATIC_PAGE_FREE_GAME);

        return $this->render('FrontBundle:Freeplay:freeplay.html.twig', array(
            'allowed' => $this->getDoctrine()->getRepository('FrontBundle:User')->isAllowedToEditCmsPages($user),
            'user' => $user,
            'nb' => $nb,
            'content' => $content,
            'text' => ($cmsPage instanceof CmsPage ? $cmsPage->getContent() : null)
        ));
    }

    public function bureauAction()
    {
        return $this->render('FrontBundle:Bureau:bureau.html.twig');
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
