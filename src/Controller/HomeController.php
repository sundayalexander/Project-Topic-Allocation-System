<?php

namespace App\Controller;


use App\Entity\AllocatedTopic;
use App\Entity\Supervisor;
use App\Entity\Topic;
use App\Form\StudentLogin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 *This is HomeController class.
 * User: MaHome
 * Date: 12/18/2018
 * Time: 9:10 AM
 */
class HomeController extends AbstractController {
//Class properties goes here.....

    /**
     * This method handles homepage routing.
     * @Route("/",name="homepage", methods={"get","post"})
     */
    public function index(Request $request){
        $topics = $this->getDoctrine()->getRepository(Topic::class)->findAll();
        $allocated = count($this->getDoctrine()->getRepository(AllocatedTopic::class)->findAll());
        $supervisors = count($this->getDoctrine()->getRepository(Supervisor::class)->findAll());
        $form = $this->createFormBuilder()
            ->add("name", TextType::class,[
                "attr" => ["placeholder" =>"YOUR NAME", "class"=>"form-control"],
                "constraints" => [new NotBlank(), new NotNull()]
            ])
            ->add("phone", TelType::class,[
                "attr" => ["placeholder" => "PHONE", "class"=>"form-control"],
                "constraints" => [new Length(["min"=>11,"max"=>14]), new NotBlank(), new NotNull()]
            ])
            ->add("email", EmailType::class,[
                "attr" => ["placeholder" => "EMAIL ADDRESS", "class"=>"form-control"],
                "constraints" => [new NotBlank(), new NotNull()]
            ])
            ->add("comment", TextareaType::class,[
                "attr" => ["placeholder" => "COMMENT", "class"=>"form-control custom-textarea-style"],
                "constraints" => [new Length(["min"=>10]), new NotBlank(), new NotNull()]
            ])->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            dump($form);
        }
        return $this->render("layout/home.html.twig",[
            "topics" => $topics,
            "allocated" => $allocated,
            "unallocated" => count($topics) - $allocated,
            "supervisors" => $supervisors,
            "form" => $form->createView()
        ]);
    }

}