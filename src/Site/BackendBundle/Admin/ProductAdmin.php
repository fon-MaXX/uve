<?php

namespace Site\BackendBundle\Admin;

use Site\BackendBundle\Entity\ShareTag;
use Site\FrontendBundle\Form\FilterConfig;
use Site\UploadBundle\UpbeatTraits\UpbeatUploadFullAdminTrait;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Site\UploadBundle\Form\UpbeatUploadType;
use Sonata\CoreBundle\Model\Metadata;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class ProductAdmin extends AbstractAdmin
{
    private $insertionTypes=null;
    private $states=null;
    use UpbeatUploadFullAdminTrait;
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
        $this->childAdmins = new \SplObjectStorage();
        $imagesAdmin = new \StdClass;
        $imagesAdmin->fieldName = 'productGallery';
        $imagesAdmin->adminService = 'site_backend.admin.product.gallery';
        $this->childAdmins->attach($imagesAdmin);
    }
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('cod',null,[
                'label'=>'field.cod'
            ])
            ->add('title',null,[
                'label'=>'field.title'
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
            ->add('cod',null,[
                'label'=>'field.cod'
            ])
            ->add('poster','text',[
                'template'=>"SiteBackendBundle:List:_poster.html.twig",
                'label'=>'field.poster'
            ])
            ->add('title',null,[
                'label'=>'field.title'
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
        $formConfig = new FilterConfig();
        $this->states = $formConfig->getAdminProductStates();
        $this->insertionTypes = $formConfig->getInsertionTypes();
        $formMapper
            ->with('SEO часть')
            ->add('title','text',[
                'required'=>true
            ])
            ->add('titleMeta','text',[
                'required'=> false,
                'label'=>'Meta title'
            ])
            ->add('isFresh',CheckboxType::class,[
                'required'=>true,
                'label'=>'Присутствовал в синхронизации'
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
                ->add('cod','text',[
                    'required'=>true,
                    'label'=>'field.cod'
                ])
                ->add('subCategory', 'sonata_type_model', [
                    'required' => false,
                    'multiple'=>false,
                    'expanded'=>false,
                    'btn_add'=>false,
                    'label'=>'field.category',
                    'property' => 'title',
    //                'empty_value' => '//---------------------//',
                    'class' => 'SiteBackendBundle:SubCategory',
                ])
                ->add('shareTags', 'sonata_type_model', [
                    'expanded' => false,
                    'property'=>'title',
                    'by_reference' => false,
                    'query' => $em->getRepository('SiteBackendBundle:ShareTag')->getAllSortedByTitle(),
                    'multiple' => true,
                    'attr' => ['class' => 'multiSelect'],
                    'label'=>'field.share_tag'
                ])
                ->add('rating','text',[
                    'required'=>false,
                    'label'=>'Рейтинг'
                ])
                ->add('state',ChoiceType::class,[
                    'label'=>"field.state",
                    'choices'=>array_flip($this->states),
                ])
                ->add('poster', UpbeatUploadType::class,[
                    'file_type' => 'product_icon',
                    'template'  => 'SiteBackendBundle:Upload:product_image.html.twig',
                    'extensions' => 'jpg,gif,png',
                    'label'=>'field.poster'
                ])
                ->add('shortcut', 'textarea', [
                    'label'=>'field.shortcut',
                    'attr' => [
                        'class' => 'tinymce',
                        'data-theme' => 'medium' // simple, advanced, bbcode
                    ]
                ])
            ->end()
            ->with("Свойства товара")
                ->add('price',null,[
                    'label'=>'field.price',
                    'required'=>false
                ])
                ->add('sharePrice',null,[
                    'label'=>'field.share_price',
                    'required'=>false
                ])
                ->add('weight',null,[
                    'label'=>'field.weight',
                    'required'=>false
                ])
                ->add('metal',null,[
                    'label'=>'field.metal',
                    'required'=>false
                ])
                ->add('insertionType',ChoiceType::class,[
                    'choices'=>array_flip($this->insertionTypes),
                    'label'=>'field.insertion_type',
                    'required'=>false
                ])
                ->add('insertionShape',null,[
                    'label'=>'field.insertion_shape',
                    'required'=>false
                ])
                ->add('insertionParameters',null,[
                    'label'=>'field.insertion_parameters',
                    'required'=>false
                ])
                ->add('productParameters',null,[
                    'label'=>'field.product_parameters',
                    'required'=>false
                ])
                ->add('weavingType',null,[
                    'label'=>'field.weaving_type',
                    'required'=>false
                ])
                ->add('covering',null,[
                    'label'=>'field.covering',
                    'required'=>false
                ])
                ->add('theme',null,[
                    'label'=>'field.theme',
                    'required'=>false
                ])
                ->add('manufacturer',null,[
                    'label'=>'field.manufacturer',
                    'required'=>false
                ])
                ->add('ringSizes', 'sonata_type_model', [
                    'expanded' => false,
                    'property'=>'title',
                    'by_reference' => false,
                    'query' => $em->getRepository('SiteBackendBundle:RingSize')->getAllSortedByTitle(),
                    'multiple' => true,
                    'attr'     => ['class' => 'multiSelect'],
                    'label'=>'field.ring_sizes',
                    'required'=>false
                ])
                ->add('chainSizes', 'sonata_type_model', [
                    'expanded' => false,
                    'property'=>'title',
                    'by_reference' => false,
                    'query' => $em->getRepository('SiteBackendBundle:ChainSize')->getAllSortedByTitle(),
                    'multiple' => true,
                    'attr'     => ['class' => 'multiSelect'],
                    'label'=>'field.chain_length',
                    'required'=>false
                ])
                ->add('insertionColors', 'sonata_type_model', [
                    'expanded' => false,
                    'property'=>'title',
                    'by_reference' => false,
                    'query' => $em->getRepository('SiteBackendBundle:InsertionColor')->getAllSortedByTitle(),
                    'multiple' => true,
                    'attr'     => ['class' => 'multiSelect'],
                    'label'=>'field.insertion_colors',
                    'required'=>false
                ])
            ->end()
            ->with("Gallery")
            ->add('productGallery', 'sonata_type_collection', [
                'label' => 'Photos',
                'by_reference' => false
                ],
                [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'targetEntity' => 'SiteBackendBundle\Entity\ProductGallery'
                ])
            ->end();
        $formMapper->get('insertionType')->addModelTransformer(new CallbackTransformer(
            function ($insertionType) {
                //                  call`s after getter
                $types = array_flip($this->insertionTypes);
                return  (isset($types[$insertionType]))?$types[$insertionType]:null;
            },
            function ($insertionType) {
                //                   call`s before setter
                return (isset($this->insertionTypes[$insertionType]))?$this->insertionTypes[$insertionType]:null;
            }
        ));
        $formMapper->get('state')->addModelTransformer(new CallbackTransformer(
            function ($state) {
                //                  call`s after getter
                $states = array_flip($this->states);
                return  (isset($states[$state]))?$states[$state]:null;
            },
            function ($state) {
                //                   call`s before setter
                return (isset($this->states[$state]))?$this->states[$state]:null;
            }
        ));
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
            ->add('cod','text',[
                'label'=>'field.cod'
            ])
            ->add('subCategory', 'text', [
                'label'=>'field.category',
                'template'=>'SiteBackendBundle:Show:_category.html.twig'
                ])
            ->add('shareTags', 'text', [
                'label'=>'field.share_tag',
                'template'=>"SiteBackendBundle:Show:_tag.html.twig"
            ])
            ->add('rating','text',[
                'label'=>"рейтинг"
            ])
            ->add('state','text',[
                'label'=>"field.state"
            ])
            ->add('poster', 'text',[
                'template'  => 'SiteBackendBundle:Show:_poster.html.twig',
                'label'=>'field.poster'
            ])
            ->add('shortcut', 'textarea', [
                'label'=>'field.shortcut',
                'template'=>"SiteBackendBundle:Show:_text.html.twig"
            ])
            ->add('price',null,[
                'label'=>'field.price',
            ])
            ->add('sharePrice',null,[
                'label'=>'field.share_price',
            ])
            ->add('weight',null,[
                'label'=>'field.weight',
            ])
            ->add('metal',null,[
                'label'=>'field.metal',
            ])
            ->add('insertionType',null,[
                'label'=>'field.insertion_type',
            ])
            ->add('insertionShape',null,[
                'label'=>'field.insertion_shape',
            ])
            ->add('insertionParameters',null,[
                'label'=>'field.insertion_parameters',
            ])
            ->add('productParameters',null,[
                'label'=>'field.product_parameters',
            ])
            ->add('weavingType',null,[
                'label'=>'field.weaving_type',
            ])
            ->add('covering',null,[
                'label'=>'field.covering',
            ])
            ->add('productType',null,[
                'label'=>'field.product_type',
            ])
            ->add('theme',null,[
                'label'=>'field.theme',
            ])
            ->add('manufacturer',null,[
                'label'=>'field.manufacturer',
            ])
            ->add('ringSizes', 'sonata_type_model', [
                'template'=>"SiteBackendBundle:Show:_tag.html.twig",
                'label'=>'field.ring_sizes',
            ])
            ->add('chainSizes', 'sonata_type_model', [
                'template'=>"SiteBackendBundle:Show:_tag.html.twig",
                'label'=>'field.chain_length',
            ])
            ->add('insertionColors', 'sonata_type_model', [
                'label'=>'field.insertion_colors',
                'template'=>"SiteBackendBundle:Show:_tag.html.twig",
            ])
        ;
    }
    public function getObjectMetadata($object)
    {
        $url = $this->generateUrl('create');

        return new Metadata($object->getTitle(), $object->getShortcut(), $url);
    }
}
