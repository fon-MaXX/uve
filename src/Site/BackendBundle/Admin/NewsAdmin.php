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
class NewsAdmin extends AbstractAdmin
{
    use UpbeatUploadFilesAdminTrait;
    /**
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     */
    public function __construct($code, $class, $baseControllerName, $container = null,$entityManager = null,$fileHandler=null)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->container = $container;
        $this->entityManager=$entityManager;
        $this->fileHandler=$fileHandler;
    }
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('title',null,[
                'label'=>'field.title'
            ])
            ->add(
                'createdAt','doctrine_orm_datetime_range', [
                'field_type'=>'sonata_type_datetime_range_picker',
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
            ->add('poster','text',[
                'template'=>"SiteBackendBundle:List:_poster.html.twig",
                'label'=>'field.poster'
            ])
            ->add('title',null,[
                'label'=>'field.title'
            ])
            ->add('shortcut',null,[
                'label'=>'field.shortcut'
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
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $formMapper
            ->with('SEO часть')
            ->add('title','text',[
                'required'=>true
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
            ->end()
            ->with('Контентная часть')
                ->add('poster', UpbeatUploadType::class,[
                    'file_type' => 'product_icon',
                    'template'  => 'SiteBackendBundle:Upload:product_image.html.twig',
                    'extensions' => 'jpg,gif,png',
                    'label'=>'field.poster'
                ])
                ->add('posterAlt',null,[
                    'label'=>'field.poster_alt',
                    'required'=>false
                ])
                ->add('shortcut', 'textarea', [
                    'label'=>'field.shortcut',
                    'attr' => [
                        'class' => 'tinymce',
                        'data-theme' => 'simple' // simple, advanced, bbcode
                    ]
                ])
                ->add('text', 'textarea', [
                    'label'=>'field.text',
                    'attr' => [
                        'class' => 'tinymce',
                        'data-theme' => 'medium' // simple, advanced, bbcode
                    ]
                ])
                ->add('newsTags', 'sonata_type_model', [
                    'expanded' => false,
                    'property'=>'title',
                    'by_reference' => false,
                    'query' => $em->getRepository('SiteBackendBundle:NewsTag')->getAllSortedByTitle(),
                    'multiple' => true,
                    'attr' => ['class' => 'multiSelect'],
                    'label'=>'field.share_tag'
                ])
                ->add('createdAt')
                ->add('updatedAt')
            ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('newsTags', 'text', [
                'label'=>'field.share_tag',
                'template'=>"SiteBackendBundle:Show:_tag.html.twig"
            ])
            ->add('keywords')
            ->add('description')
            ->add('poster', 'text',[
                'template'  => 'SiteBackendBundle:Show:_poster.html.twig',
                'label'=>'field.poster'
            ])
            ->add('posterAlt','text',[
                'label'=>"field.poster_alt"
            ])
            ->add('shortcut', 'textarea', [
                'label'=>'field.shortcut',
                'template'=>"SiteBackendBundle:Show:_text.html.twig"
            ])
            ->add('text', 'textarea', [
                'label'=>'field.text',
                'template'=>"SiteBackendBundle:Show:_text.html.twig"
            ])
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
    public function getObjectMetadata($object)
    {
        $url = $this->generateUrl('create');

        return new Metadata($object->getTitle(), $object->getShortcut(), $url);
    }
}
