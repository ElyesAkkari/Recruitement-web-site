<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Formateur;
use App\Entity\Formation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class AjouterinformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,['label'=>'nom'])
            ->add('prix')
            ->add('nbrpartricipant')
            ->add('nbrheures')
            ->add('datedeb')
            ->add('descrption')

            ->add('image',FileType::class, array('data_class' => null,'required' => false))
            ->add('nomformateur', EntityType::class,['class'=>Formateur::class,'choice_label'=>'nom'])
            ->add('categories', EntityType::class,['class'=>Categories::class,'choice_label'=>'nom'])


            ->add('save', SubmitType::class,[
                'attr' => ['class' => 'btn btn-primary']])



        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
