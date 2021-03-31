<?php

namespace App\Form;

use App\DataTransferObject\Credentials;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class LoginType
 * @package App\Form
 */
class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("username",TextType::class, [
                "label" => "Email :"
            ])
            ->add("password",PasswordType::class,[
                "label" => "Mot de passe :"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault("data_class",Credentials::class);
    }
}