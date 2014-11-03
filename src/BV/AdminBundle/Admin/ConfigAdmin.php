<?php

namespace BV\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use BV\FrontBundle\Entity\Events;

class ConfigAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('value')
            ->add('updated_at')
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', 'doctrine_orm_string')
            ->add('value', 'doctrine_orm_string')
            ->add('updated_at', 'doctrine_orm_datetime')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('value','string')
            ->add('updated_at','datetime',array('date_format' => 'dd/MM/yyyy HH:mm'))
        ;
    }
}
