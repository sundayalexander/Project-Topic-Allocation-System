<?php

namespace App\Form;


use App\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *This is StudentRegistration class.
 * User: MaHome
 * Date: 1/20/2019
 * Time: 10:32 AM
 */
class StudentRegistration extends AbstractType{
//Class properties goes here.....
    public function buildForm(FormBuilderInterface $builder, array $options){
       // $
        $builder->add("matric_no", TextType::class,[
            "attr"=>["placeholder" => "e.g: 14xxxxx","class" => "form-control"],
            'label'=>"Matric Number"
        ])
            ->add("first_name",TextType::class, [
                "attr"=>["placeholder" => "e.g: John", "class" => "form-control"],
                "label" => "First Name"
            ])
            ->add("session",ChoiceType::class,[
                "attr" => ["class"=>"form-control1"],
                "choices" => [
                    "-- Select Session --" => "null",
                    "2016/2017" => "2016/2017",
                    "2017/2018" => "2017/2018",
                    "2018/2019" => "2018/2019",
                    "2019/2020" => "2019/2020"
                ]
            ])
            ->add("last_name", TextType::class,[
                "attr"=>["placeholder" => "e.g: Doe", "class" => "form-control"],
                "label" => "Last Name"
            ])
            ->add("email", EmailType::class,[
                "attr"=>["placeholder" => "e.g: johndoe@gmail.com", "class" => "form-control"]
            ]);
    }

    /**
     * This method mapped the default data object.
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(["data_class" => Student::class]);
    }

}