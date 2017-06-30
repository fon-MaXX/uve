<?php
namespace Site\FrontendBundle\Form;

use Site\BackendBundle\Entity\NovaPoshtaData;
use Site\BackendBundle\Entity\NovaPoshtaRegion;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class NovaPoshtaType extends AbstractType
{
    private $container=null;
    private $cities = [];
    private $builder;
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->container = $options['container'];
        $em = $this->container->get('doctrine')->getManager();
        $this->builder = $builder;
        $regionList = $em->getRepository("SiteBackendBundle:NovaPoshtaRegion")->getAllIndexByHref();
        $builder
            ->add('regionHref',ChoiceType::class,[
                'auto_initialize' => false,
                'required'=>true,
                'expanded'=> false,
                'multiple'=>false,
                'label'=>'Область',
                'choices'=>$regionList,
                'attr'=>[
                    'data-sonata-select2'=>"false",
                    'class'=>"select-default-view new-post-select new-post-select-region"
                ]
            ]);
        if($options['isBackend']){
            $entity = new NovaPoshtaData();
            $builder
                ->add('payer','choice',[
                    'choices'  => $entity->payerType,
                    'attr'=>[
                        'class'=>'select-default-view payer-select'
                    ]
                ]);
        }

        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'constructNPForm']);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'constructNPForm']);

    }
    public function constructNPForm(FormEvent $event){
//        dump($event);die();
        $form = $event->getForm();
        $formData  = $event->getData();
        if(!$formData) return;
        if(!is_array($formData)){
            $data['regionHref'] = $formData->getRegionHref();
            $data['cityHref'] = $formData->getCityHref();
            $data['warehouseHref'] = $formData->getWarehouseHref();
        }
        else{
            $data = $formData;
        }
        if(isset($data['regionHref'])&&$data['regionHref']){
            $currentRegion = $this->getCurrentRegion(['ref'=>$data['regionHref']]);
            $this->cities = $this->getCitiesForRegion($currentRegion);
            $form->add($this->builder->create('cityHref',ChoiceType::class,[
                'auto_initialize' => false,
                'required'=>true,
                'expanded'=> false,
                'multiple'=>false,
                'label'=>'Город',
                'choices'=>array_flip($this->cities),
                'attr'=>[
                    'data-sonata-select2'=>"false",
                    'class'=>"select-default-view new-post-select new-post-select-city"
                ]
            ])
                ->getForm()
            );
        }
        if(
            isset($data['regionHref'])&&
            $data['regionHref']&&
            isset($data['cityHref'])&&
            $data['cityHref']
        ){
            $currentCity = $this->getCurrentCity($this->cities,$data['cityHref']);
            $warehouses = $this->getWarehousesForCity($currentCity);
            $form->add($this->builder->create('warehouseHref',ChoiceType::class,[
                'auto_initialize' => false,
                'required'=>true,
                'expanded'=> false,
                'multiple'=>false,
                'label'=>'Отделение',
                'choices'=>array_flip($warehouses),
                'attr'=>[
                    'data-sonata-select2'=>"false",
                    'class'=>"select-default-view new-post-select new-post-select-warehouse"
                ]
            ])
                ->getForm()
            );
        }
    }
    private function getCitiesForRegion(NovaPoshtaRegion $region){
        $service = $this->container->get('new.post.service');
        $citiesList = $service->getCities($region);
        if(!$citiesList){
            throw new NotFoundHttpException('Empty response for cities');
        }
        $result=null;
        $result[]='form.empty.data';
        foreach($citiesList as $item){
            $result[$item->ref] = $item->description;
        }
        return $result;
    }
    private function getWarehousesForCity(\stdClass $city){
        $service = $this->container->get('new.post.service');
        $warehousesList = $service->getWarehouses($city);
        if(!count($warehousesList)){
            throw new NotFoundHttpException('Some troubles with access to NP api, warehouses list is empty');
        }
        $result=null;
        $result[]='form.empty.data';
        foreach($warehousesList as $item){
            $result[$item->ref] = $item->description;
        }
        return $result;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'=>'Site\BackendBundle\Entity\NovaPoshtaData',
                'container'=>null,
                'isBackend'=>false
            ]
        );
    }
    public function getName()
    {
        return 'site_frontend_nova_poshta_type';
    }
    private function getCurrentRegion($ref=null,$description=null){
        $em = $this->container->get('doctrine')->getManager();
        $currentRegion = null;
        if($ref){
            $currentRegion = $em->getRepository('SiteBackendBundle:NovaPoshtaRegion')->findOneByRef($ref);
        }
        elseif($description){
            $currentRegion = $em->getRepository('SiteBackendBundle:NovaPoshtaRegion')->findOneByDescription($description);
        }
        if(!$currentRegion){
            throw new NotFoundHttpException('No region for given value ='.($ref)?$ref:$description);
        }
        return $currentRegion;
    }
    private function getCurrentCity($cities,$ref){
        $current = null;
        foreach($cities as $k=>$item){
            if($k==$ref){
                $current = (object)[
                    'ref'=>$k,
                    'description'=>$item
                ];
            }
        }
        if(!$current){
            throw new NotFoundHttpException('No city for given value from select ='.$ref);
        }
        return $current;
    }
}
