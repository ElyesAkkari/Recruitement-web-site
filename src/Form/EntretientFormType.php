<?php

namespace App\Form;

use App\Entity\Entretient;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;
class EntretientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user',EntityType::class,['class'=>User::class,'choice_label'=>'id','multiple'=>false])
            ->add('datedeb',DateType::class)
            ->add('temps',TimeType::class)
            ->add('recruteur',TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('resultat',TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('mail',TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('etat',TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('NumeroTelephone',TextType::class,array('attr'=>array('class'=>'form-control')))

            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entretient::class,
        ]);
    }
}
