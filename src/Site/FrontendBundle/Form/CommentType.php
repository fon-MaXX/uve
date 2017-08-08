<?php

namespace Site\FrontendBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\CallbackTransformer;
use Site\FrontendBundle\Form\SiteFrontendCustomExpandedSelectRadioType;

class CommentType extends AbstractType
{
    public $genders=[];
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $object = $builder->getData();
        $this->genders = $object->genders;
        $builder
            ->setAction($options['action'])
            ->add('pageUrl',HiddenType::class,[
                'required'=>true
            ])
            ->add('rating',HiddenType::class,[
                'required'=>true,
                'attr'=>[
                    'class'=>'user-rating-field'
                ]
            ])
            ->add('username',TextType::class,[
                'required'=>true,
                'label'=>'Имя',
                'attr'=>[
                    'class'=>'comment-input name-validation clear-input',
                    'placeholder'=>'Имя'
                ]
            ])
            ->add('gender',SiteFrontendCustomExpandedSelectRadioType::class,[
                'required'=>true,
                'choices'=>$this->genders,
                'expanded'=> true,
                'multiple'=>false,
                'label'=>''
            ])
            ->add('text',TextareaType::class,[
                'required'=>true,
                'attr'=>[
                    'placeholder'=>'Ваше сообщение',
                    'class'=>'comment-input'
                ]
            ])
            ;
        $builder
            ->get('gender')
            ->addModelTransformer(new CallbackTransformer(
                function ($string) {
//                  call`s after getter
                    if(!$string||!isset($this->genders[$string])){
                        return null;
                    }
                    return  $this->genders[$string];
                },
                function ($string) {
//                   call`s before setter
                    if(!$string||!in_array($string,$this->genders)){
                        return null;
                    }
                    $arr = array_flip($this->genders);
                    return $arr[$string];
                }
            ));
    }
    public function configureOptions(OptionsResolver  $resolver) {
        $resolver->setDefaults([
            'action'=>'',
            'data_class'=>'Site\BackendBundle\Entity\Comment',
        ]);
    }
    public function getParent()
    {
        return 'form';
    }
    public function getName()
    {
        return 'frontend_comment_type';
    }
}
