<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('token', TextType::class, [
                'label' => 'Reset Token',
                'required' => true,
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'New Password',
                'required' => true 
                //here other pw constarints: minlength, capital, number, sp characters...
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, // Configure your form options here
        ]);
    }
}
