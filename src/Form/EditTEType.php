<?php

namespace App\Form;

use App\Entity\Entretient;
use App\Entity\Typeent;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditTEType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('entretient',EntityType::class,['class'=>Entretient::class,'choice_label'=>'id'])
            ->add('titre',\Symfony\Component\Form\Extension\Core\Type\TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('description',\Symfony\Component\Form\Extension\Core\Type\TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('edit',SubmitType::class,array(

                'attr' => array('class' => 'btn btn-primary')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Typeent::class,
        ]);
    }
}
