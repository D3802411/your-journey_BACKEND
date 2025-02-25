<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'expanded' => true,  // Render as checkboxes 
                'multiple' => true,]) // Allow multiple roles
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
                        new Assert\Regex(
                            pattern: '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/',
                            message: 'Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.'
                            // '/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
                            ),
                    ],
            ])
                //I MAY REMOVE PW FIELD
            //->add('password', PasswordType::class, [
            //   'label' => 'New Password',
            //    'mapped' => false, 
            //    'required' => false, // Allow the admin to leave it empty
            //   'attr' => ['autocomplete' => 'new-password'],  //instructs the browser not to autofill with existing saved pw. Suggests creating or entering a new password.
            //])
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}