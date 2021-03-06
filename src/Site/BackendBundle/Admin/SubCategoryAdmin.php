<?php

namespace Site\BackendBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class SubCategoryAdmin extends AbstractAdmin
{
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
            ->add('position','text',[
                'editable'=>true
            ])
            ->add('category','text',[
                'template'=>"SiteBackendBundle:List:_category.html.twig",
                'label'=>'field.category'
            ])
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [
                        'template' => 'SiteBackendBundle:List:_listEdit.html.twig'
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
            ->add('position')
            ->add('keywords','textarea', [
                'attr' => [
                    'class' => 'keywords-textarea'
                ],
                'required'=>false
            ])
            ->add('description','textarea',[
                'required'=>false
            ])
            ->add('category', 'sonata_type_model', [
                'required' => false,
                'multiple'=>false,
                'expanded'=>false,
                'btn_add'=>false,
                'label'=>'field.category',
                'property' => 'title',
                //                'empty_value' => '//---------------------//',
                'class' => 'SiteBackendBundle:Category',
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
            ->add('category', 'string', [
                'label'=>'field.category',
                'template'=>"SiteBackendBundle:Show:_category.html.twig"
            ])
        ;
    }
}
