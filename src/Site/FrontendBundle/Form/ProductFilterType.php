<?php

namespace Site\FrontendBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Site\FrontendBundle\Form\SiteFrontendRangeType;
use Site\FrontendBundle\Form\SiteFrontendCustomExpandedSelectType;

class ProductFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action']);
        if($config=$options['filter_config']){
            if(isset($config['price'])){
                $builder->add('range',SiteFrontendRangeType::class,[
                    'required'=>false,
                    'min'=>0,
                    'max'=>$config['price']
                ]);
            }
            if(isset($config['insertionType'])){
                $builder->add('insertionType',SiteFrontendCustomExpandedSelectType::class,[
                    'required'=>false,
                    'choices'=>array_flip($config['insertionType']),
                    'expanded'=> true,
                    'multiple'=>true,
                    'label'=>'Тип вставки'
                ]);
            }
            if(isset($config['state'])){
                $builder->add('state',SiteFrontendCustomExpandedSelectType::class,[
                    'required'=>false,
                    'choices'=>array_flip($config['state']),
                    'expanded'=> true,
                    'multiple'=>true,
                    'label'=>'Статус'
                ]);
            }
            if(isset($config['chainSizes'])){
                $builder->add('chainSizes',SiteFrontendCustomExpandedSelectType::class,[
                    'required'=>false,
                    'choices'=>array_flip($config['chainSizes']),
                    'expanded'=> true,
                    'multiple'=>true,
                    'label'=>'Длинна'
                ]);
            }
            if(isset($config['theme'])){
                $builder->add('theme',SiteFrontendCustomExpandedSelectType::class,[
                    'required'=>false,
                    'choices'=>array_flip($config['theme']),
                    'expanded'=> true,
                    'multiple'=>true,
                    'label'=>'Тематика',
                    'attr'=>[
                        'class'=>'square-checkbox'
                    ]
                ]);
            }
            $builder->add('sort',ChoiceType::class,[
                'choices'=>[
                    'cheapest'=>'cheapest',
                    'by_title'=>'by_title',
                    'popular'=>'popular'
                ],
                'expanded'=> true,
                'multiple'=>false,
                'data'=>'popular',
                'attr'=>[
                    'class'=>'product-filter-sort-type'
                ]
            ])
                ->add('number',NumberType::class,[
                    'required'=>false,
                    'attr'=>[
                        'class'=>'product-filter-number'
                    ]
                ])
            ;
            if($options['is_sub_category'] == false){
                $builder->add('subCategory',SiteFrontendCustomExpandedSelectType::class,[
                    'required'=>false,
                    'choices'=>array_flip($options['sub_category_list']),
                    'expanded'=> true,
                    'multiple'=>true,
                    'label'=>'Подкатегория'
                ]);
            }
        }
    }
    public function configureOptions(OptionsResolver  $resolver) {
        $resolver->setDefaults([
            'action'=>'',
            'is_sub_category'=>false,
            'sub_category_list'=>[],
            'filter_config'=>false,
        ]);
    }
    public function getParent()
    {
        return 'form';
    }
    public function getName()
    {
        return 'frontend_product_filter_type';
    }
}
