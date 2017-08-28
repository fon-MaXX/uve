<?php

namespace Site\BackendBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class CategoryAdmin extends AbstractAdmin
{
    public function preUpdate($entity)
    {
        $this->prePersist($entity);
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('title')
        ;
    }
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('title',null,[
                'label'=>'field.title'
            ])
            ->add('h1','text',[
                'required'=> false,
                'label'=>'h1'
            ])
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [
                        'template' => 'SiteBackendBundle:List:_listShow.html.twig'
                    ],
                    'edit' => [
                        'template' => 'SiteBackendBundle:List:_listEdit.html.twig'
                    ],
                    'delete' => [
                        'template' => 'SiteBackendBundle:List:_listDelete.html.twig'
                    ],
                ]
            ])
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title','text',[
                'required'=>true,
                'label'=>'Title'
            ])
            ->add('titleMeta','text',[
                'required'=> false,
                'label'=>'Meta title'
            ])
            ->add('h1','text',[
                'required'=> false,
                'label'=>'h1'
            ])
            ->add('keywords','textarea', [
                'attr' => [
                    'class' => 'keywords-textarea'
                ],
                'required'=>false
            ])
            ->add('description','textarea',[
                'required'=>false
            ])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('keywords')
            ->add('description')
        ;
    }
}
