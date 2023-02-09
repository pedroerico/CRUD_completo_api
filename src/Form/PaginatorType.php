<?php

namespace App\Form;

use App\Model\Paginator\PaginatorModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaginatorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('limit', IntegerType::class, [
                'required' => false,
                'empty_data' => '10',
            ])
            ->add('page', IntegerType::class, [
                'required' => false,
                'empty_data' => '1',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PaginatorModel::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }
}
