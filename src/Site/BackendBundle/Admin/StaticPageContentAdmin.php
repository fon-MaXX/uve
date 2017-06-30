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
class StaticPageContentAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $choices=[
            'главная'=>"main_page",
            'о нас'=>"about_company",
            'контакты'=>"contacts",
            'доставка'=>"about_delivery",
            'оплата'=>"about_payment",
            'страница товара'=>"product_show",
            'контент футера и хедера'=>"footer_and_header",
        ];
        $datagridMapper
            ->add('id')
            ->add('linkname',null,[
                'label'=>'linkname'
            ])
            ->add('staticPage', 'doctrine_orm_callback', [
                'label'=>'Страница',
                'callback' => function($queryBuilder, $alias, $field, $value) {
                    if (!$value['value']) {
                        return;
                    }
                    $queryBuilder->leftJoin($alias.'.staticPage','sp');
                    $queryBuilder->andWhere('sp.title = :title');
                    $queryBuilder->setParameter('title', $value['value']);

                    return true;
                },
                'field_type' => 'choice',
                'field_options'=>[
                        'choices' => $choices,
                        'expanded' => false,
                        'multiple' => false,
                        'attr' => ['class' => 'filter-static-page']
                    ]
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
            ->add('staticPage','text',[
                'template'=>'SiteBackendBundle:List:_category.html.twig'
            ])
            ->add('linkname',null,[
                'label'=>'linkname',
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
            ->add('staticPageTitle','text',[
                'label'=>'Страница',
                'required'=>true,
                'attr'=>[
                    'readonly'=>true
                ]
            ])
            ->add('linkname','text',[
                'required'=>true,
                'attr'=>[
                    'readonly'=>true
                ]
            ])
            ->add('text', 'textarea', [
                'label'=>'field.text',
                'attr' => [
                    'class' => 'tinymce',
                    'data-theme' => 'medium' // simple, advanced, bbcode
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
