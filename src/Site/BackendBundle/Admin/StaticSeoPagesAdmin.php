<?php

namespace Site\BackendBundle\Admin;

use Site\BackendBundle\Entity\Repository\StaticPageRepository;
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
class StaticSeoPagesAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper;
    }
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('title','text',[
                'lable'=>'title'
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
                'label'=>'title'
            ])
            ->add('h1','text',[
                'required'=>true,
                'label'=>'h1'
            ])
            ->add('keywords','textarea',[
                'required'=>false,
                'label'=>'keywords'
            ])
            ->add('description','textarea',[
                'required'=>false,
                'label'=>'description'
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
