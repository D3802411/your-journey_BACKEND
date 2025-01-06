<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType; 
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
// USE Symfony\Component\Validator\Constraints\Count; for photos addition
use Symfony\Component\OptionsResolver\OptionsResolver;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('place')
            ->add('city')
            ->add('country')
            ->add('attraction')            
            ->add('activity')
            ->add('title')
            ->add('textArea', TextareaType::class, [
                  'attr' => [
                    'rows' => 10, // Number of rows (height)
                    'cols' => 80, // Number of columns (width)
                    'placeholder' => 'Write your article content here...',
                    ],
                ])
            ->add('photo');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
