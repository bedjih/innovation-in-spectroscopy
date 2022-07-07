<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname',TextType::class, [
            'disabled'=>true,
            'label'=>'Mon prénom'
        ])

        ->add('lastname',TextType::class, [
            'disabled'=> true,
            'label' => 'Mon nom'
        ])

        ->add('email',EmailType::class, [
           'disabled'=> true,
           'label' => 'Mon adresse mail'
        ]) 

        ->add('old_password', PasswordType::class,[
            'label' => 'Mon mot de passe actuel ',
            'mapped'=>false,
            'label'=>' Mon mot de passe',
            'attr'=> ['placeholder'=> 'Merci de saisir votre mot de passe']

        ])
        ->add('new_password', RepeatedType::class,[
            'type'=> PasswordType::class,
            'mapped'=>false,
            'invalid_message'=>'le mot de passe et la confirmation doivent etre identique.',
            'label'=>'Mon nouveau  mot de passe',
            'required'=>true,
            'first_options' => ['label'=>'Mon nouveau  mot de passe','attr'=> ['placeholder'=> 'Merci de saisir votre nouveau  mot de passe']],
            'second_options'=> ['label'=>'Confirmer le nouveau  mot de passe','attr'=> ['placeholder'=> 'Merci de confirmer votre mot de passe']],
            
        ])
       
        ->add('submit', SubmitType::class,[
            'label'=> 'Mettre a jour ',
        ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

