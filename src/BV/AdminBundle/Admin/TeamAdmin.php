<?php

namespace BV\AdminBundle\Admin;

use BV\FrontBundle\Entity\Team;
use BV\FrontBundle\Entity\User;
use Doctrine\ORM\Query\Expr\Join;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class TeamAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $type = $this->getSubject()->getType();

        $formMapper
            ->tab('Management')
                ->with('Management')
                    ->add('name', 'text', array(
                        'label' => 'Nom de l\'équipe'
                    ))
                    ->add('type', 'bv_team_type', array(
                        'disabled' => !is_null($type),
                    ))
                    ->add('level', 'bv_user_level', array(
                        'label' => 'Niveau FSGT'
                    ))
                    ->add('slot', 'text', array(
                        'label' => 'Créneau attribué',
                        'required' => false
                    ))
                ->end()
            ->end()
        ;

        // Type has been set on this team : Editing now -> Can display Members tab
        if (!is_null($type)) {
            switch ($this->getSubject()->getType()) {
                case Team::TYPE_FEM:
                    $members = 'membersFem';
                    $gender = User::GENDER_FEMALE;
                    break;
                case Team::TYPE_MSC:
                    $members = 'membersMsc';
                    $gender = User::GENDER_MALE;
                    break;
                case Team::TYPE_MIX:
                    $members = 'membersMix';
                    $gender = null;
                    break;
            }

            /* @var $em \Doctrine\ORM\EntityManager */
            $em = $this->getModelManager()->getEntityManager($this->getClass());
            $membersAssociated = $em->getRepository('FrontBundle:User')->findAllByTeam($this->getSubject()->getId());

            $meta = $em->getClassMetadata($this->getClass());
            $expr = $em->getExpressionBuilder();
            $qbMember = $em->createQueryBuilder()
                ->select('u')
                ->from($meta->getAssociationMapping($members)['targetEntity'], 'u')
                ->where($expr->orX(
                    $expr->eq('u.' . $meta->getAssociationMapping($members)['mappedBy'], ':team'),
                    $expr->isNull('u.' . $meta->getAssociationMapping($members)['mappedBy'])
                ))
                ->andWhere($expr->eq('u.gender', ':gender'))
                ->setParameter('team', $this->getSubject()->getId())
                ->setParameter('gender', $gender);
            ;

            $formMapper
                ->tab('Members')
                ->with('Players')
                ->add($members, 'sonata_type_model', array(
                    'by_reference' => false,
                    'query' => $qbMember,
                    'multiple' => true,
                    'required' => false,
                    'label' => 'Members',
                    'btn_add' => false,
                ))
                ->end()
                ->end()
            ;

            if (count($membersAssociated) > 0)
            {
                $qbCaptain = $em->createQueryBuilder()
                    ->select('u')
                    ->from($meta->getAssociationMapping($members)['targetEntity'], 'u')
                    ->where($expr->eq('u.' . $meta->getAssociationMapping($members)['mappedBy'], ':team'))
                    ->leftJoin($this->getClass(), 't', Join::WITH, $expr->andX(
                        $expr->neq('t.id', ':team'),
                        $expr->neq('t.captain', 'u.id')
                    ))
                    ->setParameter('team', $this->getSubject()->getId())
                ;

                $qbSubCaptain = $em->createQueryBuilder()
                    ->select('u')
                    ->from($meta->getAssociationMapping($members)['targetEntity'], 'u')
                    ->where($expr->eq('u.' . $meta->getAssociationMapping($members)['mappedBy'], ':team'))
                    ->leftJoin($this->getClass(), 't', Join::WITH, $expr->andX(
                        $expr->neq('t.id', ':team'),
                        $expr->neq('t.subCaptain', 'u.id')
                    ))
                    ->setParameter('team', $this->getSubject()->getId())
                ;

                $formMapper
                    ->tab('Members')
                    ->with('Leaders')
                        ->add('captain', 'sonata_type_model', array(
                            'query' => $qbCaptain,
                            'btn_add' => false,
                            'required' => false,
                        ))
                        ->add('subCaptain', 'sonata_type_model', array(
                            'query' => $qbSubCaptain,
                            'btn_add' => false,
                            'required' => false,
                        ))
                        ->end()
                    ->end()
                ;
            }
        }
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('type')
            ->add('level')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('type', 'bv_team_type')
            ->add('level', 'bv_user_level')
        ;
    }

}
