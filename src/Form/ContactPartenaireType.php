<?php

namespace App\Form;

use App\Entity\ContactPartenaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ContactPartenaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('telephone')
            ->add('sujet', ChoiceType::class, array(
                'choices'  => array(
                    'Partenariat en cours' => 'Partenariat en cours',
                    'Proposer un partenariat' => 'Proposer un partenariat',
                    'Autre' => 'Autre'
                )
            ))
            ->add('newsletter')
            ->add('message')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactPartenaire::class,
        ]);
    }
}
