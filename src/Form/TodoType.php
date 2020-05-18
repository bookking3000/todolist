<?php

namespace App\Form;

use App\Entity\Todo;
use App\Entity\TodoCategory;
use App\Entity\User;
use App\Repository\TodoCategoryRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TodoType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $username = $options['user']->getUsername();
        $builder
            ->add('Name', null, $options = [
                'attr' => array('class' => 'form-control'),
                'label' => 'Name',
            ])
            ->add('Description', TextareaType::class, $options = [
                'attr' => array('class' => 'form-control'),
                'label' => 'Beschreibung',
            ])
            ->add('DueDate', DateTimeType::class, array(
                'required' => true,
                'label' => 'Fälligkeitsdatum',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control input-inline datetimepicker',
                    'data-provide' => 'datetimepicker',
                    'html5' => false,
                ],
            ))
            ->add('Contributors', EntityType::class, $options = [
                'class' => User::class,
                'choice_label' => function ($user) {
                    return $user->getDisplayName();
                },
                'placeholder' => false,
                'multiple' => true,
                'query_builder' =>
                    function (UserRepository $er) use ($username) {
                        return $er->createQueryBuilder('u')
                            ->where('u.Username != :val')
                            ->setParameter('val', $username)
                            ->orderBy('u.Username', 'ASC');
                    },
                'attr' => array('class' => 'form-control'),
                'label' => 'Teilnehmer',
            ])
            ->add('Category', EntityType::class, $options = [
                'class' => TodoCategory::class,
                'choice_label' => function ($category) {
                    return $category->getName();
                },
                'placeholder' => false,
                'multiple' => true,
                'query_builder' =>
                    function (TodoCategoryRepository $er) use ($username) {
                        return $er->createQueryBuilder('u')
                            ->where('u.user != :val')
                            ->setParameter('val', $username)
                            ->orderBy('u.user', 'ASC');
                    },
                'attr' => array('class' => 'form-control'),
                'label' => 'Kategorien',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Todo::class,
            'user' => null,
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
