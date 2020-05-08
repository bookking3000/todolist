<?php


namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('email', EmailType::class,
                $options = [
                    'attr' => array('class' => 'form-control'),
                    'label' => 'E-Mail',
                    'constraints' => [
                        new NotBlank(),
                    ]                                ])

            ->add('username', TextType::class,
                $options = [
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Benutzername',
                    'constraints' => [
                        new NotBlank(),
                    ]
                ])

            ->add('plainPassword', RepeatedType::class,
                array(
                    'type' => PasswordType::class,
                    'first_options' =>
                        array(
                            'label' => 'Passwort',
                            'attr' => array('class' => 'form-control'),
                        ),
                    'second_options' =>
                        array(
                            'label' => 'Passwort wiederholen',
                            'attr' => array('class' => 'form-control'),
                        ),
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate', // comment me to reactivate the html5 validation!  🚥
            ]
        ));
    }
}