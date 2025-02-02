<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('firstName', TextType::class, [
      
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a First Name',
                ]),
                new Length([
   
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ])
        ->add('lastName', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a Last Name',
                ]),
                new Length([
                     // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ])
 
            ->add('email')

            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a username',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your username should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            
            ->add('plainPassword', PasswordType::class, [
                // PW is hashed in the controller, not here
                'mapped' => false, // not bind to the entity as string, because it's more secure if it stays hashed
                'attr' => ['autocomplete' => 'new-password'],  //sets HTML attribute that prevents pw from autofilling and ask the U to write it anew
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a valid password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password must be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 64,
                    ]),
                    new Regex(
                        pattern: '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/',
                        message: 'Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.'
                        // '/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
                        ),
                ],
            ])

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
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
