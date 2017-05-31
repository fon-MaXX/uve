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

class NewsFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action'])
            ->add('number',NumberType::class,[
                'required'=>false,
                'attr'=>[
                    'class'=>'product-filter-number'
                ]
            ])
        ;
    }
    public function configureOptions(OptionsResolver  $resolver) {
        $resolver->setDefaults([
            'action'=>'',
        ]);
    }
    public function getParent()
    {
        return 'form';
    }
    public function getName()
    {
        return 'frontend_news_filter_type';
    }
}
