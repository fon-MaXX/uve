<?php

namespace Site\BackendBundle\Admin;

use Site\BackendBundle\Entity\ShareTag;
use Site\UploadBundle\UpbeatTraits\UpbeatUploadFilesAdminTrait;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Site\UploadBundle\Form\UpbeatUploadType;
use Sonata\CoreBundle\Model\Metadata;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class StaticTextAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('linkname',null,[
                'label'=>'linkname'
            ])
        ;
    }
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('linkname',null,[
                'label'=>'linkname'
            ])
            ->add('text',null,[
                'label'=>'field.text'
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
            ->add('linkname','text',[
                'required'=>true
            ])
                ->add('text', 'textarea', [
                    'label'=>'field.text',
                    'attr' => [
                        'class' => 'tinymce',
                        'data-theme' => 'simple' // simple, advanced, bbcode
                    ]
                ])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('linkname')
            ->add('text', 'textarea', [
                'label'=>'field.text',
                'template'=>"SiteBackendBundle:Show:_text.html.twig"
            ])
        ;
    }
}
