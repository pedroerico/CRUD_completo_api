<?php

namespace App\Form;

use App\Model\FiltersModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sort_by', ChoiceType::class, [
                'choices' => ['id'],
            ])
            ->add('sort_order', ChoiceType::class, [
                'choices' => ['asc', 'desc'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FiltersModel::class,
            'csrf_protection' => false,
            'choices' => [],
            'allow_extra_fields' => true,
        ]);
    }
}
