<?php

namespace BV\FrontBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManager as BaseUserManager;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManager extends BaseUserManager implements ContainerAwareInterface
{
    protected $objectManager;
    protected $class;
    protected $repository;
    protected $container;

    /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param CanonicalizerInterface  $usernameCanonicalizer
     * @param CanonicalizerInterface  $emailCanonicalizer
     * @param ObjectManager           $om
     * @param string                  $class
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer, ObjectManager $om, $class)
    {
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer);

        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);

        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
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
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);

        if (null !== $user->certifFile) {
            $certifPath = $this->container->getParameter('front.profile.certif_path');
            $uploadDir = $this->container->getParameter('front.web_dir').$certifPath;

            $filename = $user->getUsernameCanonical().'.'.$user->certifFile->guessExtension();

            $user->certifFile->move($uploadDir, $filename);
            $user->setCertif($certifPath.'/'.$filename);
            $user->certifFile = null;
        }

        if (null !== $user->attestationFile) {
            $attestationPath = $this->container->getParameter('front.profile.attestation_path');
            $uploadDir = $this->container->getParameter('front.web_dir').$attestationPath;

            $filename = $user->getUsernameCanonical().'.'.$user->attestationFile->guessExtension();

            $user->attestationFile->move($uploadDir, $filename);
            $user->setAttestation($attestationPath.'/'.$filename);
            $user->attestationFile = null;
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
