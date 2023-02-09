<?php

namespace App\Form;

use App\Model\DriverModel;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DriverUpdateType extends DriverType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DriverModel::class,
            'csrf_protection' => false,
            'is_update' => true,
        ]);
    }
}
