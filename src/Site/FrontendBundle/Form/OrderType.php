<?php

namespace Site\FrontendBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Site\FrontendBundle\Form\OrderHasProductType;
use Site\FrontendBundle\Form\OrderHasSetType;
use Site\FrontendBundle\Form\NovaPoshtaType;
use Site\FrontendBundle\Form\UkrPoshtaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();
        $payTypes = $entity->payTypes;
        $deliveryTypes = $entity->deliveryTypes;
        $builder
            ->setAction($options['action'])
            ->add('username',TextType::class,[
                'required'=>true,
                'label'=>'Ф.И.О.',
                'attr'=>[
                    'class'=>'name-validation clear-input order-personal-data-input'
                ],
                'constraints'=>[
                    new NotBlank()
                ]
            ])
            ->add('email',TextType::class,[
                'required'=>false,
                'attr'=>[
                    'class'=>'email-validation clear-input order-personal-data-input'
                ]
            ])
            ->add('phone',TextType::class,[
                'label'=>'Телефон',
                'required'=>true,
                'attr'=>[
                    'class'=>'callback-input phone-validation clear-input order-personal-data-input',
                    'placeholder'=>"0XXXXXXXXX"
                ],
                'constraints'=>[
                    new NotBlank()
                ]
            ])
            ->add('payType',ChoiceType::class,[
                'choices'=>array_flip($payTypes),
                'expanded'=>false,
                'multiple'=>false,
                'label'=>'Способ оплаты',
                'attr'=>[
                    'class'=>'select-default-view payment-description-select'
                ]
            ])
            ->add('deliveryType',ChoiceType::class,[
                'choices'=>array_flip($deliveryTypes),
                'expanded'=>false,
                'multiple'=>false,
                'label'=>'Способ доставки',
                'attr'=>[
                    'data-sonata-select2'=>"false",
                    'class'=>'select-default-view order-delivery-type-select'
                ]
            ])
            ->add('comment',TextareaType::class,[
                'required'=>false,
                'attr'=>[
                    'class'=>'clear-input order-personal-data-textarea'
                ]
            ]);
        if(!$options['is_frontend']) {
            $builder->add('price', 'text', [
                'label' => "Сумма",
                'required' => false
                ])
                ->add('discount', 'text', [
                    'label' => "Скидка",
                    'required' => false
                ]);
            }
            $builder->add('orderHasProducts',CollectionType::class,[
                'entry_type' => OrderHasProductType::class
            ])
            ->add('orderHasSets',CollectionType::class,[
                'entry_type' => OrderHasSetType::class
            ]);
        if(!$options['is_ajax']){
            $builder
                ->add('novaPoshtaData', NovaPoshtaType::class,[
                    'container'=>$options['container'],
                    'isBackend'=>!$options['is_frontend']
                ])
                ->add('ukrPoshtaData', UkrPoshtaType::class)
            ;
        }
        $builder
            ->get('phone')
            ->addModelTransformer(new CallbackTransformer(
                function ($phone) {
//                  call`s after getter
                    if(!$phone){
                        return null;
                    }
                    $replace = [' ','-','+','(',')'];
                    $result = str_replace($replace,'',$phone);
                    return  substr($result,2,strlen($result));
                },
                function ($phone) {
//                   call`s before setter
                    if(!$phone){
                        return null;
                    }
                    $phone = str_replace(' ','',$phone);
                    $number = [
                        substr($phone,0,3),
                        substr($phone,3,3),
                        substr($phone,6,2),
                        substr($phone,8,2)
                    ];
                    return "+3(8".$number[0].")".$number[1].'-'.$number[2].'-'.$number[3];
                }
            ));
        $builder
            ->get('payType')
            ->addModelTransformer(new CallbackTransformer(
                function ($type) use($payTypes) {
//                  call`s after getter
                    $types  = array_flip($payTypes);
                    return  (isset($types[$type]))?$types[$type]:null;
                },
                function ($type) use($payTypes) {
//                   call`s before setter
                    return (isset($payTypes[$type]))?$payTypes[$type]:null;
                }
            ));
        $builder
            ->get('deliveryType')
            ->addModelTransformer(new CallbackTransformer(
                function ($type) use($deliveryTypes) {
//                  call`s after getter
                    $types  = array_flip($deliveryTypes);
                    return  (isset($types[$type]))?$types[$type]:null;
                },
                function ($type) use($deliveryTypes) {
//                   call`s before setter
                    return (isset($deliveryTypes[$type]))?$deliveryTypes[$type]:null;
                }
            ))
        ;
    }
    public function configureOptions(OptionsResolver  $resolver) {
        $resolver->setDefaults([
            'action'=>'',
            'data_class'=>'Site\BackendBundle\Entity\Order',
            'is_frontend'=>false,
            'container'=>null,
            'is_ajax'=>false
        ]);
    }
    public function getParent()
    {
        return 'form';
    }
    public function getName()
    {
        return 'frontend_order_type';
    }
}
