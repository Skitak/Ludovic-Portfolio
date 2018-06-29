<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->setAction($this->generateUrl('account_modify_password'))
            ->add('new_password' , PasswordType::class, [
                'constraints' => [new NotBlank(), new Length([
                    "min" => 8,
                    "max" => 30,
                    "minMessage" => "Le mot de passe doit etre composé d'au moins {{ limit }} caractères",
                    "maxMessage" => "Le mot de passe ne doit etre composé de plus de {{ limit }} caractères" ])],
                'mapped' => false])
            ->add('new_password_retyped' , PasswordType::class, [
                'constraints' => [new NotBlank()],
                'mapped' => false])
            ->add('sauvegarder' , SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}