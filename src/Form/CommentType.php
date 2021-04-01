<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CommentType
 * @package App\Form
 */
class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("content",TextareaType::class,[
                "label" => "Votre message :",
                "attr" => [
                    "class"=>'form-control my-1'
                ],
                "label_attr" => [
                    "class"=>'form-label'
                ]
            ])
            ;

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event){
                if($event->getData()->getUser()!== null){
                    return;
                }
                $event->getForm()->add("author",TextType::class, [
                    "label" => "Pseudo :",
                    "attr" => [
                        "class"=>'form-control my-1'
                    ],
                    "label_attr" => [
                        "class"=>'form-label'
                    ]
                ]);
            });
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault("data_class",Comment::class);
    }

}