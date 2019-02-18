<?php

namespace App\Controller;


use App\Entity\AllocatedTopic;
use App\Entity\Student;
use App\Entity\Topic;
use App\Form\StudentRegistration;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 *This is StudentController class.
 * User: MaHome
 * Date: 12/15/2018
 * Time: 7:44 PM
 */
class StudentController extends AbstractController{
//Class properties goes here.....

    /**
     * This method handles the student dashboard.
     * @Route("/student/dashboard",name="student_dashboard")
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function dashboard(Session $session){
        if(!$session->has("student_id") || !$session->has("matric_no")){
            $session->clear();
            return $this->redirectToRoute("student_login");
        }
        $totalTopics = count($this->getDoctrine()->getRepository(Topic::class)->findAll());
        $allocated = count($this->getDoctrine()->getRepository(AllocatedTopic::class)->findAll());
        return $this->render('layout/student/dashboard.html.twig',[
            "totalTopics" => $totalTopics,
            "allocated" => $allocated,
            "unallocated" => $totalTopics - $allocated
        ]);
    }

    /**
     * This method authenticate students login
     * @Route("/student", name="student_login", methods={"post","get"})
     * @param Request $request
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request, Session $session){
        $form = $this->createFormBuilder()
            ->add("matric_no", TextType::class,[
                "attr"=>["placeholder" => "e.g: 14xxxxx","class" => "form-control"],
                "label" => "Matric Number:",
                "constraints" => [new Length(["min"=>9]),new NotBlank(),new NotNull()]
            ])
            ->add("session",ChoiceType::class,[
                "label" => "Session:",
                "attr" => ["class"=>"form-control1"],
                "choices" => [
                    "-- Select Session --" => "null",
                    "2016/2017" => "2016/2017",
                    "2017/2018" => "2017/2018",
                    "2018/2019" => "2018/2019",
                    "2019/2020" => "2019/2020"
                ],
                "constraints" => [new Choice(["choices"=>["2016/2017","2017/2018","2018/2019","2019/2020"],
                    "message"=>"Please select a valid session"])]
            ])->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $session->clear();
            $student = $this->getDoctrine()->getRepository(Student::class)->findOneBy([
                "matric_no" => $form->getData()["matric_no"],
                "session" => $form->getData()["session"]
            ]);
            if($student != null){
                $session->set("matric_no",$student->getMatricNo());
                $session->set("student_id",$student->getStudentId());
                return $this->redirectToRoute("student_dashboard");
            }else{
                $form->addError(new FormError("Oops! invalid login details"));
            }
        }
        return $this->render("layout/student/login.html.twig",[
            "registered" => $session->get("registered"),
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/student/register", name="student_register", methods={"post","get"})
     * @param Request $request
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request, Session $session){
        $student = new Student();
        $student->setRegistered(new \DateTime("now"));
        $form = $this->createForm(StudentRegistration::class,$student);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();
            $session->set("registered",true);
            return $this->redirectToRoute("student_login");
        }
        return $this->render("layout/student/register.html.twig",["form" => $form->createView()]);
    }

    /**
     * @Route("/student/update-data", name="student_update_data",methods={"get","post"})
     * @param Request $request
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateData(Request $request, Session $session){
        if(!$session->has("student_id") || !$session->has("matric_no")){
            $session->clear();
            return $this->redirectToRoute("student_login");
        }
        $entityManager = $this->getDoctrine()->getManager();
        $updated = false;
        $student = $entityManager->getRepository(Student::class)->findOneBy([
            "student_id" => $session->get("student_id"),
            "matric_no" => $session->get("matric_no")
        ]);
        $form = $this->createForm(StudentRegistration::class,$student);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
                $entityManager->flush();
                $updated = true;
        }
        return $this->render("layout/student/update_data.html.twig",[
            "form" => $form->createView(),
            "updated" => $updated
        ]);
    }

    /**
     * @Route("/student/view-topic", name="student_view_topic", methods={"get"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewTopic(Request $request, Session $session){
        if(!$session->has("student_id") || !$session->has("matric_no")){
            $session->clear();
            return $this->redirectToRoute("student_login");
        }
        return $this->render("layout/student/view_topic.html.twig");
    }

    /**
     * This method handles student logout request.
     * @Route("/student/logout", name="student_logout", methods={"get"})
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logout(Session $session){
        $session->clear();
        $session->invalidate();
        return $this->redirectToRoute("student_login");
    }

}