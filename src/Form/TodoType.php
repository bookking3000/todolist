<?php

namespace App\Form;

use App\Entity\Todo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name',null, $options =  [
                'attr'   => array('class' => 'form-control'),
                'label' => 'Name',
            ])
            ->add('Description',null, $options =  [
                'attr'   => array('class' => 'form-control'),
                'label' => 'Beschreibung',
            ] )

            ->add('DueDate',null, $options =  [
                'attr'   => array('class' => 'form-control'),
                'label' => 'Fälligkeitsdatum',
            ])
            ->add('Contributors',null, $options =  [
                'attr'   => array('class' => 'form-control'),
                'label' => 'Teilnehmer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Todo::class,
        ]);
    }
}
