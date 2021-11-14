<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Formateur;





class ModifierformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prix')
            ->add('nbr_partricipant')
            ->add('nbr_heures')
            ->add('datedeb')
            ->add('descrption')

            ->add('nomformateur', EntityType::class,['class'=>Formateur::class,'choice_label'=>'nom'])
            ->add('image',FileType::class, array('data_class' => null,'required' => false))
            ->add('categories', EntityType::class,['class'=>Categories::class,'choice_label'=>'nom'])


            ->add('modifier', SubmitType::class,[
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
