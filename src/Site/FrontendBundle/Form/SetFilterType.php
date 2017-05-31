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

class SetFilterType extends AbstractType
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
           $builder->add('sort', ChoiceType::class, [
                'choices' => [
                    'cheapest' => 'cheapest',
                    'by_title' => 'by_title',
                    'popular' => 'popular'
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => 'popular',
                'attr' => [
                    'class' => 'product-filter-sort-type'
                ]
            ])
            ->add('number', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'product-filter-number'
                ]
            ]);
        }
    }
    public function configureOptions(OptionsResolver  $resolver) {
        $resolver->setDefaults([
            'action'=>'',
            'filter_config'=>false,
        ]);
    }
    public function getParent()
    {
        return 'form';
    }
    public function getName()
    {
        return 'frontend_set_filter_type';
    }
}
