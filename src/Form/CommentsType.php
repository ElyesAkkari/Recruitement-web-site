<?php

namespace App\Form;

use App\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content',TextareaType::class,[
                'label'=>'Votre commentaire',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('email',EmailType::class,[
                'label'=>'Votre e-mail',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('nickname',TextareaType::class,[
                'label'=>'Votre pseudo',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('rgpd',CheckboxType::class)
            ->add('parent',HiddenType::class,[
                'mapped'=> false
            ])
            ->add('commenter',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
