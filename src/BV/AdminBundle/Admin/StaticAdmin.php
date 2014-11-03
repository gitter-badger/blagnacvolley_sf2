<?php

namespace BV\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use BV\FrontBundle\Entity\Events;

class StaticAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('description')
            ->add('content', 'textarea', array(
                'attr' => array(
                    'class' => 'redactor',
                ),
            ))
        ;
    }

//    public function createQuery($context = 'list')
//    {
//        $query = parent::createQuery($context);
//        $query->andWhere(
//            $query->expr()->eq($query->getRootAlias() . '.type', ':my_param')
//        );
//        $query->setParameter('my_param', Events::TYPE_CLOSED);
//        return $query;
//    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', 'doctrine_orm_string')
            ->add('description', 'doctrine_orm_string')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('description','string')
        ;
    }
}
