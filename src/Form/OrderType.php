<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   

        $user = $options['user'];

        $builder
            ->add('addresses',EntityType::class,[
                'label'=>false ,
                'required'=> true,
                'class'=>Address::class,
                'multiple'=> false,
                'expanded'=> true,
                'choices'=>$user->getAddresses()

            ])
            ->add('Carriers',EntityType::class,[
                'label'=>'Choisissez  votre  livreur ',
                'required'=> true,
                'class'=>Carrier::class,
                'multiple'=> false,
                'expanded'=> true,
            
            ])
            ->add('submit',SubmitType::class,[
                'label'=>'Valider ma commande ',
                'attr'=>[
                    'class'=>'btb btn-success btn-block'
                ]
            ])
            ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => array()
        ]);
    }
}
