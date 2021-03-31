<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class PostType
 * @package App\Form
 */
class PostType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title',TextType::class,[
            "label" => "Titre :",
            "attr" => [
                "class"=>'form-control my-1'
            ],
            "label_attr" => [
                "class"=>'form-label'
            ]
        ])
        ->add('content',TextareaType::class,[
            "label" => 'Article :',
            "attr" => [
                "class"=>'form-control my-1'
            ],
            "label_attr" => [
                "class"=>'form-label'
            ]
        ])
        ->add("file", FileType::class,[
            "required"=>false,
            "mapped" => false,
            "constraints" => [
                new Image(),
                new NotNull([
                    "groups" => "create"
                ])
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault("data_class", Post::class);
    }
}