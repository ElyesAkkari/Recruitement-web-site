<?php

namespace App\Form;

use App\Entity\Entretient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditEntretientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datedeb')
            ->add('temps')
            ->add('recruteur',TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('resultat',TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('mail',TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('etat',TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('edit', SubmitType::class,array(

                'attr' => array('class' => 'btn btn-primary')))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entretient::class,
        ]);
    }
}
