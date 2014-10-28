<?php

namespace BV\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use BV\FrontBundle\Entity\Events;

class VacationAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('start_date', 'datetime')
            ->add('end_date', 'datetime')
            ->add('text', 'text')
            ->add('type', 'bv_events_type')
        ;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->andWhere(
            $query->expr()->eq($query->getRootAlias() . '.type', ':my_param')
        );
        $query->setParameter('my_param', Events::TYPE_CLOSED);
        return $query;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('start_date', 'doctrine_orm_datetime')
            ->add('end_date', 'doctrine_orm_datetime')
//            ->add('text')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('start_date','datetime',array('date_format' => 'dd/MM/yyyy HH:mm'))
            ->add('end_date','datetime',array('date_format' => 'dd/MM/yyyy HH:mm'))
            ->add('text')
        ;
    }

    public function prePersist($object)
    {
        /* @var $object \BV\FrontBundle\Entity\Events */
        parent::prePersist($object);
//        $object->setContentFormatter('richhtml');
//        $object->setContent($object->getRawContent());
//        $object->setTitle($object->getTitle());
    }
}