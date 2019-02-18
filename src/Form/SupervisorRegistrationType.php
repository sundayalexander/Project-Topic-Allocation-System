<?php

namespace App\Form;

use App\Entity\Supervisor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupervisorRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title",ChoiceType::class,[
                "attr" => ["class"=>"form-control1"],
                "choices" => [
                    "-- Select Title --" => "null",
                    "Prof." => "Prof",
                    "Dr." => "Dr",
                    "Mr." => "Mr"
                ]
            ])
            ->add("first_name",TextType::class, [
                "attr"=>["placeholder" => "e.g: John", "class" => "form-control"],
                "label" => "First Name"
            ])
            ->add("last_name", TextType::class,[
                "attr"=>["placeholder" => "e.g: Doe", "class" => "form-control"],
                "label" => "Last Name"
            ])
            ->add("phone_number", TelType::class,[
                "attr"=>["placeholder" => "e.g: 081xxx or 23481xx", "class" => "form-control"],
                "label" => "Phone Number"
            ])
            ->add("email", EmailType::class,[
                "attr"=>["placeholder" => "e.g: johndoe@gmail.com", "class" => "form-control"]
            ])
            ->add("password", PasswordType::class,[
                "attr"=>["class" => "form-control","placeholder"=>"minimum of 8 characters"]
            ])
            ->add("capacity", IntegerType::class,[
                "attr"=>["placeholder" => "e.g: 4", "class" => "form-control"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Supervisor::class
        ]);
    }
}
