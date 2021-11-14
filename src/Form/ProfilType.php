<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\FileType;



use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('prenom')
            ->add('cin')
            ->add('email', EmailType::class)
            ->add('numtel')
            ->add('adresse')  
            ->add('niveau')   
            ->add('datenaissance',BirthdayType::class)   
            ->add('imageFile', FileType::class,[
                'required'=>false])   
            ->add('Ajouter',SubmitType::class,[
                'label' =>'Save Profile Settings ',
                'attr' => ['class' => 'btn btn-secondary'],
            ])
        ;
    }

    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Profil::class,
    //     ]);
    // }
}
