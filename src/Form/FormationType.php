<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Diplome;
use App\Entity\Organization;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
 



class FormationType extends AbstractType
{



    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('mail')
            ->add('name')
            ->add('perspective')
            ->add('mode_financement')
            ->add('address')
            ->add('city')
            ->add('postal_code')
            ->add('phone_number')
            ->add('date')
            ->add('date_fin')
            ->add('price')
            ->add('diplome', EntityType::class,[
                'class' => Diplome::class,
                'choice_label' => 'name',
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
