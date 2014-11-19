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

        $events = [];
        $cms = [];
        $nb = $this->getDoctrine()->getRepository('FrontBundle:User')->countUsersByGroups();

        // VOLLEYSCHOOL_ADULTS
        $cmsPage = $this->getDoctrine()->getRepository('FrontBundle:CmsPage')->findSingleByName(CmsPage::STATIC_PAGE_VOLLEYSCHOOL_ADULTS);
        $cms[Events::TYPE_VOLLEYSCHOOL_ADULT] = $cmsPage->getContent();
        $event = $this->getDoctrine()->getRepository('FrontBundle:Events')->findEventsByType(Events::TYPE_VOLLEYSCHOOL_ADULT);
        $events[Events::TYPE_VOLLEYSCHOOL_ADULT] = $event;
        $availabilities[Events::TYPE_VOLLEYSCHOOL_ADULT] = $this->getDoctrine()->getRepository('FrontBundle:Availability')->countAvailabilities($event);
        $availability[Events::TYPE_VOLLEYSCHOOL_ADULT] = $this->getDoctrine()->getRepository('FrontBundle:Availability')->findByUserAndEvent($user, $event);
        $users[Events::TYPE_VOLLEYSCHOOL_ADULT] = $this->getDoctrine()->getRepository('FrontBundle:Availability')->findByEventAndAvailable($event);

        // VOLLEYSCHOOL_YOUTH
        $cmsPage = $this->getDoctrine()->getRepository('FrontBundle:CmsPage')->findSingleByName(CmsPage::STATIC_PAGE_VOLLEYSCHOOL_YOUTH);
        $cms[Events::TYPE_VOLLEYSCHOOL_YOUTH] = $cmsPage->getContent();
        $event = $this->getDoctrine()->getRepository('FrontBundle:Events')->findEventsByType(Events::TYPE_VOLLEYSCHOOL_YOUTH);
        $events[Events::TYPE_VOLLEYSCHOOL_YOUTH] = $event;
        $availabilities[Events::TYPE_VOLLEYSCHOOL_YOUTH] = $this->getDoctrine()->getRepository('FrontBundle:Availability')->countAvailabilities($event);
        $availability[Events::TYPE_VOLLEYSCHOOL_YOUTH] = $this->getDoctrine()->getRepository('FrontBundle:Availability')->findByUserAndEvent($user, $event);
        $users[Events::TYPE_VOLLEYSCHOOL_YOUTH] = $this->getDoctrine()->getRepository('FrontBundle:Availability')->findByEventAndAvailable($event);

        return $this->render('FrontBundle:Volleyschool:volleyschool.html.twig', array(
            'allowed' => $this->getDoctrine()->getRepository('FrontBundle:User')->isAllowedToEditCmsPages($user),
            'user' => $user,
            'users' => $users,
            'cms' => $cms,
            'events' => $events,
            'availabilities' => $availabilities,
            'availability' => $availability,
            'nb' => $nb
        ));
    }

    public function toggleAvailabilityAction(Request $request)
    {
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Redirect to consultation if not logged or not allowed to edit
        if (!$user instanceof User)
        {
            return $this->redirect($this->generateUrl('bv_static_volley_school'), 302);
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

        return $this->redirect($this->generateUrl('bv_static_volley_school'), 302);
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
        $cmsPage = $this->getDoctrine()->getRepository('FrontBundle:CmsPage')->findSingleByName(CmsPage::STATIC_PAGE_VOLLEYSCHOOL_ADULTS);
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
