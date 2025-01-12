<?php

namespace App\Form;

use App\Entity\Article;
// use App\Entity\User; //???????do I need this
// use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// use Symfony\Component\Validator\Constraints\NotBlank;


class SearchArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('place', TextType::class, [
            'label' => 'Place',
            'required' => true,
            'attr' => ['placeholder' => 'ex: "Montmartre"'],
            ])

            ->add('city', TextType::class, [
            'label' => 'City',
            'required' => false,
            'attr' => ['placeholder' => 'city name'],
            ])

            ->add('country', TextType::class, [
            'label' => 'Country',
            'required' => false,
            'attr' => ['placeholder' => 'country name'],
            ])

            ->add('attraction', TextType::class, [
            'label' => 'Attraction',
            'required' => false,
            'attr' => ['placeholder' => 'ex: "beach"'],
            ])

            ->add('activity', TextType::class, [
            'label' => 'Activity',
            'required' => false,
            'attr' => ['placeholder' => 'ex: "swimming"'],
            ])

            ->add('title', TextType::class, [
            'label' => 'Title',
            'required' => false,
            'attr' => ['placeholder' => 'Search by title'],
            ])
            
           //BUTTON DONE IN TWIG  ->add("search", SubmitType::class, [
            //    "label" => "Search",
            //])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET', // Use GET to include parameters in the URL
            'data_class' => null,  // This ensures the form data is returned as an array, 
            // not an Article object; This will fix the form data issue and allow you to work with an array of form fields in the controller.
            //We don't need a class for the search form 'data_class' => Article::class,
        ]);
    }
}
