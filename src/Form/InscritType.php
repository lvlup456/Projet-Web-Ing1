<?php
namespace App\Form;

use App\Entity\Inscrit;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Doctrine\DBAL\Types\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;

class InscritType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', TextType::class)
        ->add('prenom', TextType::class)
        ->add('datedenaissance', BirthdayType::class)
        ->add('mail', EmailType::class)
        ->add('documents', CollectionType::class, [
            'entry_type'   		=> File2Type::class,
            'prototype'			=> true,
            'allow_add'			=> true,
            'allow_delete'		=> true,
            'by_reference' 		=> false,
            'required'			=> false,
            'label'			=> false
        ])->add('captchaCode', CaptchaType::class, array(
            'captchaConfig' => 'ExampleCaptcha'
            ))
            ->add('submit', SubmitType::class, [
            'label' => 'Redirect To Payment'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Inscrit::class,
        ));
    }
    public function getBlockPrefix()
    {
        return 'app_folder';
    }
}