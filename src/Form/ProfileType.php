<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('email', TextType::class, [
                'label' => 'Adresse email',
                'disabled' => true, // L'email ne peut pas être modifié dans ce formulaire
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'data_class' => null,
                // mapped false permet d'extraire un input du reste du formulaire, ça évite qu'un input
                // soit lié à l'objet envoyé dans le formulaire
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
