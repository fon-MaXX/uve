<?php

namespace Site\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SiteFrontendCustomExpandedSelectRadioType extends AbstractType
{
    public function getParent()
    {
        return ChoiceType::class;
    }
    public function getName()
    {
        return 'site_frontend_custom_expanded_select_radio_type';
    }
}
