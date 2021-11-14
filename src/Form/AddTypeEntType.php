<?php

namespace App\Form;

use App\Entity\Entretient;
use App\Entity\Typeent;
use phpDocumentor\Reflection\Types\False_;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddTypeEntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('entretient',EntityType::class,['class'=>Entretient::class,'choice_label'=>'id',
                'multiple'=>False])
            ->add('titre',TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('description',TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('ajouter',SubmitType::class,array(

                'attr' => array('class' => 'btn btn-success my-3')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Typeent::class,
        ]);
    }
}
