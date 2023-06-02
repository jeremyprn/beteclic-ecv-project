<?php

namespace App\Form;

use App\Entity\Event;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('date')
            ->add('firstChoice', TextType::class,['mapped' => false, 'label' => 'Choix 1'])
            ->add('firstOdd', NumberType::class,['mapped' => false, 'label' => 'Cote du choix 1'])
            ->add('secondChoice', TextType::class,['mapped' => false, 'label' => 'Choix 2'])
            ->add('secondOdd', NumberType::class,['mapped' => false, 'label' => 'Cote du choix 2'])
//            ->add('isOpen')
//            ->add('userId')
            ->add('category')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
