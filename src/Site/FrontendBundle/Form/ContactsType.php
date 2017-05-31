<?php

namespace Site\FrontendBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\CallbackTransformer;

class ContactsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action'])
            ->add('name',TextType::class,[
                'label'=>'Имя, Фамилия:',
                'attr'=>[
                    'class'=>'callback-input name-validation clear-input'
                ]
            ])
            ->add('phone',TextType::class,[
                'label'=>'Телефон',
                'attr'=>[
                    'class'=>'callback-input phone-validation clear-input',
                    'placeholder'=>"0XXXXXXXXX"
                ]
            ])
            ->add('email',TextType::class,[
                'label'=>'Email:',
                'attr'=>[
                    'class'=>'callback-input email-validation clear-input'
                ]
            ])
            ->add('theme',TextType::class,[
                'label'=>'Тема сообщения:',
                'attr'=>[
                    'class'=>'callback-input name-validation clear-input'
                ]
            ])
            ->add('text',TextareaType::class,[
                'label'=>'Сообщение:',
                'attr'=>[
                    'class'=>'callback-input name-validation clear-input'
                ]
            ])
            ;
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
    }
    public function configureOptions(OptionsResolver  $resolver) {
        $resolver->setDefaults([
            'action'=>'',
            'data_class'=>'Site\BackendBundle\Entity\Contacts',
        ]);
    }
    public function getParent()
    {
        return 'form';
    }
    public function getName()
    {
        return 'frontend_contacts_type';
    }
}
