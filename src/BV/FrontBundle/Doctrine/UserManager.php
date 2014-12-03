<?php

namespace BV\FrontBundle\Doctrine;

use BV\FrontBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManager as BaseUserManager;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Tools\LogBundle\Logger\Logger;

class UserManager extends BaseUserManager implements ContainerAwareInterface
{
    protected $objectManager;
    protected $class;
    protected $repository;
    protected $container;
    protected $logger;

    /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param CanonicalizerInterface  $usernameCanonicalizer
     * @param CanonicalizerInterface  $emailCanonicalizer
     * @param ObjectManager           $om
     * @param string                  $class
     * @param Logger $logger
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer, ObjectManager $om, $class, Logger $logger)
    {
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer);

        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);

        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteUser(UserInterface $user)
    {
        $this->objectManager->remove($user);
        $this->objectManager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritDoc}
     */
    public function findUserBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findUsers()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function reloadUser(UserInterface $user)
    {
        $this->objectManager->refresh($user);
    }

    /**
     * Updates a user.
     *
     * @param UserInterface $user
     * @param Boolean       $andFlush Whether to flush the changes (default true)
     */
    public function updateUser(UserInterface $user, $andFlush = true)
    {
        /* @var $user User */
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);

        // user creation
        if ($user->getId() == null) {
            // Save to get the user Id
            $user->setPicture('EMPTY');
            $this->objectManager->persist($user);
            if ($andFlush) {
                $this->objectManager->flush();
            }
        }

        if (null !== $user->certifFile) {
            $path = $this->container->getParameter('front.profile.certif_path');
            $ext = $user->certifFile->guessExtension();
            $uploadDir = $this->container->getParameter('front.web_dir').$path;
            $filename = $user->getId().'.'.$ext;
            $user->certifFile->move($uploadDir, $filename);
            $user->setCertif($path.'/'.$filename);
            $user->certifFile = null;

            $user->setDateCertif(new \DateTime());

            $this->container->get('bv_cache')->resetCache($path, $filename, $uploadDir, 'img_50_50');
        }
        if (null !== $user->attestationFile) {
            $path = $this->container->getParameter('front.profile.attestation_path');
            $ext = $user->attestationFile->guessExtension();
            $uploadDir = $this->container->getParameter('front.web_dir').$path;
            $filename = $user->getId().'.'.$ext;
            $user->attestationFile->move($uploadDir, $filename);
            $user->setAttestation($path.'/'.$filename);
            $user->attestationFile = null;

            $user->setDateAttestation(new \DateTime());

            $this->container->get('bv_cache')->resetCache($path, $filename, $uploadDir, 'img_50_50');
        }
        if (null !== $user->pictureFile) {
            $path = $this->container->getParameter('front.profile.pictures_path');
            $ext = $user->pictureFile->guessExtension();
            $uploadDir = $this->container->getParameter('front.web_dir').$path;
            $filename = $user->getId().'.'.$ext;
            $user->pictureFile->move($uploadDir, $filename);
            $user->setPicture($path.'/'.$filename);
            $user->pictureFile = null;

            $this->container->get('bv_cache')->resetCache($path, $filename, $uploadDir, 'img_50_50');
        }
        if (null !== $user->parentalAdvisoryFile) {
            $path = $this->container->getParameter('front.profile.parental_advisory_path');
            $ext = $user->parentalAdvisoryFile->guessExtension();
            $uploadDir = $this->container->getParameter('front.web_dir').$path;
            $filename = $user->getId().'.'.$ext;
            $user->parentalAdvisoryFile->move($uploadDir, $filename);
            $user->setParentalAdvisory($path.'/'.$filename);
            $user->parentalAdvisoryFile = null;

            $user->setDateParentalAdvisory(new \DateTime());

            $this->container->get('bv_cache')->resetCache($path, $filename, $uploadDir, 'img_50_50');
        }

        $this->objectManager->persist($user);
        if ($andFlush) {
            $this->objectManager->flush();
        }
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
