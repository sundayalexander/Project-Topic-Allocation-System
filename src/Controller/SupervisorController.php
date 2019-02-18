<?php

namespace App\Controller;


use App\Entity\AllocatedTopic;
use App\Entity\Supervisor;
use App\Entity\Topic;
use App\Form\ProjectTopicType;
use App\Form\SupervisorRegistrationType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 *This is SupervisorController class.
 * User: MaHome
 * Date: 12/29/2018
 * Time: 7:52 PM
 */
class SupervisorController extends AbstractController {
    //Class properties goes here.....

    /**
     * This method handles supervisors registration.
     * @Route("supervisor/register", name="supervisor_register", methods={"post","get"})
     * @param Request $request
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request,Session $session){
        $supervisor = new Supervisor();
        $form = $this->createForm(SupervisorRegistrationType::class,$supervisor);
        $form->handleRequest($request);
        $supervisor->setPassword($supervisor->getHashedPassword());
        $supervisor->setRegistered(new \DateTime("now"));
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($supervisor);
            $entityManager->flush();
            $session->set("registered", true);
            return $this->redirectToRoute("supervisor_login");
        }
        return $this->render("layout/supervisor/register.html.twig",[
            "form" => $form->createView()
        ]);
    }

    /**
     * This method authenticate supervisor's
     * login credentials.
     * @Route("/supervisor", name="supervisor_login", methods={"post","get"})
     * @param Request $request
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request, Session $session){
        $form = $this->createFormBuilder()
            ->add("email", EmailType::class,[
                "attr"=>["placeholder" => "e.g: johdoe@example.com","class" => "form-control"],
                "label" => "Email:",
                "constraints" => [new Email(),new NotBlank(),new NotNull()]
                ])
            ->add("password",PasswordType::class,[
                "attr"=>["placeholder" => "minimum of 8 characters","class" => "form-control"],
                "label" => "Password:",
                "constraints" => [new Length(["min"=>8]),new NotBlank(),new NotNull()]
            ])->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $session->clear();
            $supervisor = $this->getDoctrine()->getRepository(Supervisor::class)
                ->findOneBy(["email"=>$form->getData()["email"]]);
            if($supervisor){
                if($supervisor->isPasswordValid($form->getData()["password"])){
                    $session->set("supervisor_id", $supervisor->getSupervisorId());
                    $session->set("email", $supervisor->getEmail());
                    return $this->redirectToRoute("supervisor_dashboard");
                }
                $form->addError(new FormError("Oops! invalid password."));
            }else{
                $form->addError(new FormError("Oops! invalid email address."));
            }
        }
        return $this->render("layout/supervisor/login.html.twig",[
            "registered" => $session->get("registered"),
            "form" => $form->createView()
        ]);
    }

    /**
     * This method handles the dashboard logic
     * @Route("/supervisor/dashboard", name="supervisor_dashboard")
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("is_granted('ROLE_SUPERVISOR')")
     */
    public function dashboard(Session $session){
        if(!$session->has("supervisor_id") && !$session->has("email")){
            return $this->redirectToRoute("supervisor_login");
        }
        $topics = count($this->getDoctrine()->getRepository(Topic::class)->findAll());
        $allocated = count($this->getDoctrine()->getRepository(AllocatedTopic::class)->findAll());

        return $this->render("layout/supervisor/dashboard.html.twig",[
            "totalTopics" => $topics,
            "allocated" => $allocated,
            "unallocated" => $topics - $allocated
        ]);
    }

    /**
     * @Route("/supervisor/view-topics", name="supervisor_view_topics")
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("is_granted('ROLE_SUPERVISOR')")
     */
    public function viewTopics(Session $session){
        if(!$session->has("supervisor_id") || !$session->has("email")){
            return $this->redirectToRoute("supervisor_login");
        }
        $topics = $this->getDoctrine()
            ->getRepository(Supervisor::class)
            ->find($session->get("supervisor_id"))->getTopics();
        return $this->render("layout/supervisor/view_topics.html.twig",[
            "topics" => $topics
        ]);
    }

    /**
     * @Route("/supervisor/add-topic", name="supervisor_add_topic")
     * @param Request $request
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("is_granted('ROLE_SUPERVISOR')")
     */
    public function addTopic(Request $request, Session $session){
        if(!$session->has("supervisor_id") && !$session->has("email")){
            return $this->redirectToRoute("supervisor_login");
        }
        $em = $this->getDoctrine()->getManager(); //Entity Manager
        $topic = new Topic();
        $added = false;
        $form = $this->createForm(ProjectTopicType::class,$topic);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $topic->setSupervisor($em->getRepository(Supervisor::class)->find($session->get("supervisor_id")));
            $topic->setAddedDate(new \DateTime("now"));
            $em->persist($topic);
            $em->flush();
            $added = true;
        }
        return $this->render("layout/supervisor/add_topic.html.twig",[
            "form" => $form->createView(),
            "added" => $added
        ]);
    }

    /**
     * @Route("/supervisor/update-data", name="supervisor_update_data")
     * @param Request $request
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("is_granted('ROLE_SUPERVISOR')")
     */
    public function updateData(Request $request, Session $session){
        if(!$session->has("supervisor_id") && !$session->has("email")){
            return $this->redirectToRoute("supervisor_login");
        }
        $entityManager = $this->getDoctrine()->getManager();
        $supervisor = $entityManager->getRepository(Supervisor::class)
            ->findOneBy(["supervisor_id" => $session->get("supervisor_id")]);
        $form = $this->createForm(SupervisorRegistrationType::class, $supervisor);
        $form->handleRequest($request);
        $updated = false;
        if($form->isSubmitted() && $form->isValid()){
            $supervisor->setPassword($supervisor->getHashedPassword());
            $entityManager->flush();
            $updated = true;
        }
        return $this->render("layout/supervisor/update_data.html.twig",[
            "form" => $form->createView(),
            "updated" => $updated
        ]);
    }

    /**
     * @Route("/supervisor/logout", name="supervisor_logout")
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logout(Session $session){
        $session->clear();
        $session->invalidate();
        return $this->redirectToRoute("supervisor_login");
    }

}