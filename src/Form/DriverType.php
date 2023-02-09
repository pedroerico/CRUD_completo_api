<?php

namespace App\Form;

use App\Model\DriverModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class DriverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('name')
            ->add('document')
            ->add('vehicleName')
            ->add('vehicleColor')
            ->add('vehiclePlate', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 7, 'max' => 7]),
                ],
            ]);

        $builder->get('document')
            ->addModelTransformer(new CallbackTransformer(
                function ($document) {
                    return $document;
                },
                function ($document) {
                    $replace = implode('', explode('-', (string)$document));
                    return filter_var($replace, FILTER_SANITIZE_NUMBER_INT);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DriverModel::class,
            'csrf_protection' => false,
            'allow_extra_fields' => false,
        ]);
    }
}
