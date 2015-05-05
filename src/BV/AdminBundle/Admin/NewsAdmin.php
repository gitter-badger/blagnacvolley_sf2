<?php

namespace BV\AdminBundle\Admin;

use BV\FrontBundle\Entity\Events;
use BV\FrontBundle\Entity\News;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class NewsAdmin extends Admin
{
    protected $container;

    public function __construct($code, $class, $baseControllerName, $container)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->container = $container;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $news = $this->getSubject(); /* @var $user News */
        $em = $this->container->get('doctrine')->getEntityManager();
        $events = ($news->getEventsId() == null ? null : $em->getRepository('FrontBundle:Events')->find($news->getEventsId())); /* @var $events Events */

        $formMapper
            ->tab('News')
                ->add('title')
                ->add('content', 'textarea', array(
                    'attr' => array(
                        'class' => 'ckeditor',
                    ),
                ))
                ->add('enabled', null, array('required' => false))
            ->end()
            ->end()
            ->tab('Optional Event')
                ->add('level', 'choice', array(
                    'label' => 'Evènement spécial',
                    'choices' => array(
                        Events::TYPE_VOLLEYSCHOOL_ADULT => 'constants.events.type.'.Events::TYPE_VOLLEYSCHOOL_ADULT,
                        Events::TYPE_VOLLEYSCHOOL_YOUTH => 'constants.events.type.'.Events::TYPE_VOLLEYSCHOOL_YOUTH,
                        Events::TYPE_FREE_PLAY          => 'constants.events.type.'.Events::TYPE_FREE_PLAY
                    ),
                    'data' => ($events == null ? null : $events->getType()),
                    'required'=>false, 'mapped'=>false, 'attr' => array( 'class' => 'bv-level form-control' ,)))
                ->add('start_date', 'sonata_type_datetime_picker',  array('data' => ($events == null ? null : $events->getStartDate()), 'mapped' => false, 'required' => false, 'format' => 'dd/MM/yyyy HH:mm', 'attr' => array( 'class' => 'bv-start-date' ,)))
                ->add('end_date', 'sonata_type_datetime_picker',    array('data' => ($events == null ? null : $events->getEndDate()), 'mapped' => false, 'required' => false, 'format' => 'dd/MM/yyyy HH:mm', 'attr' => array( 'class' => 'bv-end-date' ,)))
            ->end()
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('enabled')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('enabled')
            ->add('createdAt')
        ;
    }

    public function prePersist($object)
    {
        parent::prePersist($object);
        if ($object instanceof News) {
            /* @var $object \BV\FrontBundle\Entity\News */
            $object->setContentFormatter('richhtml');
            $object->setRawContent(htmlentities($object->getContent()));
            $object->setCreatedAt(new \DateTime());
            $object->setUpdatedAt(new \DateTime());
        }
    }

    public function create($object)
    {
        $this->prePersist($object);
        foreach ($this->extensions as $extension) {
            $extension->prePersist($this, $object);
        }

        $result = $this->getModelManager()->create($object);
        // BC compatibility
        if (null !== $result) {
            $object = $result;
        }

        $this->postPersist($object);
        foreach ($this->extensions as $extension) {
            $extension->postPersist($this, $object);
        }

        $this->createObjectSecurity($object);

        return $object;
    }
}
