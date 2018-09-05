<?php

namespace App\Form;

use App\Entity\ContactPro;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ContactProType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('telephone')
            ->add('organisme')
            ->add('adresse')
            ->add('vousetes', ChoiceType::class, array(
                'choices'  => array(
                    'Artiste ou Producteur' => 'Artiste ou Producteur',
                    'Diffuseur ou Gestionnaire de salle' => 'Diffuseur ou Gestionnaire de salle',
                    'Intermitent du spectacle' => 'Intermitent du spectacle',
                ),
            ))
            ->add('newsletter')
            ->add('message')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactPro::class,
        ]);
    }
}
