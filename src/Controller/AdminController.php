<?php

namespace App\Controller;


use App\Entity\AllocatedTopic;
use App\Entity\Domain;
use App\Entity\Student;
use App\Entity\Supervisor;
use App\Entity\Topic;
use App\Form\DomainType;
use App\Form\ProjectTopicType;
use App\GP\WorkerThread;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Choice;

/**
 *This is AdminController class.
 * User: MaHome
 * Date: 12/29/2018
 * Time: 7:53 PM
 */
class AdminController extends AbstractController {
    //Class properties goes here.....

    /**
     * @Route("admin", name="admin_dashboard", methods={"get"})
     */
    public function dashboard(){
        $allocated = count($this->getDoctrine()->getRepository(AllocatedTopic::class)->findAll());
        $totalTopics = count($this->getDoctrine()->getRepository(Topic::class)->findAll());
        return $this->render("layout/admin/dashboard.html.twig",[
            "allocated" => $allocated,
            "totalTopics" => $totalTopics,
            "unallocated" => $totalTopics - $allocated
        ]);
    }

    /**
     * @Route("admin/view-topics", name="admin_view_topics", methods={"get","post"})
     */
    public function viewTopics(){
        $topics = $this->getDoctrine()->getRepository(Topic::class)->findAll();
        return $this->render("layout/admin/view_topics.html.twig",[
            "topics" => $topics
        ]);
    }

    /**
     * @Route("admin/view-students", name="admin_view_students",methods={"get"})
     */
    public function viewStudent(){
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        return $this->render("layout/admin/view_students.html.twig",[
            "students" => $students
        ]);
    }

    /**
     * @Route("admin/view-supervisors", name="admin_view_supervisors", methods={"get"})
     */
    public function viewSupervisor(){
        $supervisors = $this->getDoctrine()->getRepository(Supervisor::class)->findAll();
        return $this->render("layout/admin/view_supervisors.html.twig",[
            "supervisors" => $supervisors
        ]);
    }

    /**
     * @Route("admin/topic/{id}/delete", name="admin_delete_topic")
     * @param Session $session
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteTopic(Session $session, $id){
        $em = $this->getDoctrine()->getManager();
        $topic = $em->getRepository(Topic::class)->find((int)$id);
        $em->remove($topic);
        $em->flush();
        return $this->redirectToRoute("admin_view_topics");
    }

    /**
     * @Route("admin/topic/{id}/edit", name="admin_edit_topic")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function editTopic($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $edited = false;
        $topic = $em->getRepository(Topic::class)->find((int)$id);
        $form = $this->createForm(ProjectTopicType::class,$topic);
        $domains = $this->getDoctrine()->getRepository(Domain::class)->findAll();
        $researchInterest = [];
        foreach ($domains as $domain){
            $researchInterest[ucwords($domain->getName())] = $domain;
        }
        $form->add("domain",ChoiceType::class,[
            "attr" => ["class"=>"form-control1"],
            "choices" => array_merge(["--Select Research Domain--"=>"null"],$researchInterest),
            "constraints" => [new Choice(["choices"=>array_values($researchInterest),
                "message"=>"Please select a valid research Domain"])]
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $edited = true;
        }
        return $this->render("layout/admin/edit_topic.html.twig",[
            "form" => $form->createView(),
            "edited" => $edited
        ]);
    }

    /**
     * @param Request $request
     * @Route("/admin/research-domain", name="admin_research_domain")
     * @return Response
     */
    public function addResearchDomain(Request $request){
        $domain = new Domain();
        $form = $this->createForm(DomainType::class,$domain);
        $form->handleRequest($request);
        $added = false;
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $domain->setCreatedDate(new \DateTime("now"));
            $em->persist($domain);
            $em->flush();
            $added = true;
        }
        return $this->render("layout/admin/add_domain.html.twig",[
            "form" => $form->createView(),
            "added"=>$added
        ]);
    }

    /**
     * @Route("/admin/allocate", name="admin_allocate", methods={"get","post"})
     * @param Request $request
     * @return Response
     */
    public function allocateTopics(Request $request){
        //allocated project topics
        $end = null;
        $statics = [];
        if($request->isMethod("post")){
            $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
            $topicList = $this->getDoctrine()->getRepository(Topic::class)->findAll();
            set_time_limit(0);
            $worker = new WorkerThread($topicList,$students);
            $allocation = $worker->run();
            foreach ($allocation->getGenes() as $key => $value){
                $em = $this->getDoctrine()->getManager();
                $student = $em->getRepository(Student::class)->find($key);
                $topic = $em->getRepository(Topic::class)->find($value);
                $allocated = new AllocatedTopic();
                $allocated->setStudent($student);
                $allocated->setTopic($topic);
                $allocated->setAllocatedDate(new \DateTime("now"));
                $em->persist($student);
                $em->persist($topic);
                $em->persist($allocated);
                $em->flush();
            }

            $statics["fitness"] = number_format($worker->getFitness(),2);
            $statics["iteration"] = $worker->getLoop();
            $statics["generation"] = $worker->getGenerationCount();
        }
        //display project topics
        $allocation = $this->getDoctrine()->getRepository(AllocatedTopic::class)->findAll();
        return $this->render("layout/admin/allocated_topics.html.twig",[
            "allocations" => $allocation,
            "statics" => $statics
        ]);
    }
}