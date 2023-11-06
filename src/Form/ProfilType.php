<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label'=> 'Pseudo :',
                'label_attr' => ['class' => 'pseudo-label'],
                'required'=> true,
            ])
            ->add('firstname', TextType::class, [
                'label'=>'Prénom : ',
                'label_attr' => ['class' => 'prenom-label'],
                'required'=>true,
            ])
            ->add('lastname', TextType::class, [
                'label'=>'Nom : ',
                'label_attr' => ['class' => 'nom-label'],
                'required'=>true,
            ])
//            ->add('telephone', TextType::class, [
//                'label'=>'Téléphone : ',
//                'label_attr' => ['class' => 'telephone-label'],
//                'required'=>false,
//            ])
            ->add('email', EmailType::class, [
                'label'=>"Email : ",
                'label_attr' => ['class' => 'email-label'],
                'required'=>true,
            ])
            ->add('password', RepeatedType::class, [
                'type'=> PasswordType::class,
                'trim'=>true,
                'mapped'=>false,
                'invalid_message' => 'Les mots de passe saisis ne correspondent pas.',
                'required'=>false,
                'first_options' => [
                    'label' => '<span class="first-password-label">Nouveau mot de passe :</span> ',
                    'label_html' => true,
                ],
                'second_options' => ['label' => 'Confirmation du mot de passe : '],
            ])
//            ->add('photo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}