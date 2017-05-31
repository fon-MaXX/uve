<?php

namespace Site\FrontendBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\CallbackTransformer;

class OrderHasSetComponentType extends AbstractType
{
    private $chainSizes=[];
    private $ringSizes=[];
    private $insertionColors=[];
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('presence',CheckboxType::class,[
                "label"=>' ',
                'required'=>false
            ]);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder) {
            $form = $event->getForm();
            $data  = $event->getData();
            $product = ($data->getProduct())?$data->getProduct():new Product();
            $this->insertionColors = $product->getInsertionColorsList();
            $this->ringSizes = $product->getRingSizesList();
            $this->chainSizes = $product->getChainSizesList();
            if(count($product->getInsertionColors())>0){
                $form->add($builder->create('insertionColor',ChoiceType::class,[
                    'auto_initialize' => false,
                    'required'=>true,
                    'expanded'=> false,
                    'multiple'=>false,
                    'label'=>'Цвет вставки',
                    'choices'=>array_flip($this->insertionColors),
                    'attr'=>[
                        'class'=>"select-default-view insertion-color-select"
                    ]
                ])
                    ->addModelTransformer(new CallbackTransformer(
                        function ($insertionColor) {
//                  call`s after getter
                            $colors = array_flip($this->insertionColors);
                            return  (isset($colors[$insertionColor]))?$colors[$insertionColor]:null;
                        },
                        function ($insertionColor) {
//                   call`s before setter
                            return (isset($this->insertionColors[$insertionColor]))?$this->insertionColors[$insertionColor]:null;
                        }
                    ))
                    ->getForm()
                );
            }
            if(count($product->getRingSizes())>0){
                $form->add($builder->create('ringSize',ChoiceType::class,[
                    'auto_initialize' => false,
                    'required'=>true,
                    'expanded'=> false,
                    'multiple'=>false,
                    'label'=>'Размер кольца',
                    'choices'=>array_flip($this->ringSizes),
                    'attr'=>[
                        'class'=>"select-default-view ring-size-select"
                    ]
                ])
                    ->addModelTransformer(new CallbackTransformer(
                        function ($ringSize) {
                            //                  call`s after getter
                            $sizes = array_flip($this->ringSizes);
                            return  (isset($sizes[$ringSize]))?$sizes[$ringSize]:null;
                        },
                        function ($ringSize) {
                            //                   call`s before setter
                            return (isset($this->ringSizes[$ringSize]))?$this->ringSizes[$ringSize]:null;
                        }
                    ))
                    ->getForm()
                );
            }
            if(count($product->getChainSizes())>0){
                $form->add($builder->create('chainSize',ChoiceType::class,[
                    'auto_initialize' => false,
                    'required'=>true,
                    'expanded'=> false,
                    'multiple'=>false,
                    'label'=>'Длинна цепи',
                    'chocies'=>array_flip($this->chainSizes),
                    'attr'=>[
                        'class'=>"select-default-view chain-size-select"
                    ]
                ])
                    ->addModelTransformer(new CallbackTransformer(
                        function ($chainSize) {
//                  call`s after getter
                            $sizes = array_flip($this->chainSizes);
                            return  (isset($sizes[$chainSize]))?$sizes[$chainSize]:null;
                        },
                        function ($chainSize) {
//                   call`s before setter
                            return (isset($this->chainSizes[$chainSize]))?$this->chainSizes[$chainSize]:null;
                        }
                    ))
                    ->getForm()
                );
            }
        });
    }
    public function configureOptions(OptionsResolver  $resolver) {
        $resolver->setDefaults([
            'data_class'=>'Site\BackendBundle\Entity\OrderHasSetComponent',
        ]);
    }
    public function getParent()
    {
        return 'form';
    }
    public function getName()
    {
        return 'frontend_order_has_set_component_type';
    }
}
