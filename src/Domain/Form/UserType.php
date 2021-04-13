<?php

namespace App\Domain\Form;

use App\Application\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class PostType
 * @package App\Domain\Form
 */
class UserType extends AbstractType 
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'label' => 'Email :'
            ])
            ->add('plainPassword', RepeatedType::class,[
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Mot de passe :'
                ],
                'second_options' => [
                    'label' => 'Confirmez le mot de passe :'
                ],
                'invalid_message' => 'Les deux saisies du mot de passe ne correspondent pas.',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 8])
                ]

            ])
            ->add('pseudo',TextType::class,[
                'label' => 'Pseudo :'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class',User::class);
    }

}