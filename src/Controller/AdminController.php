<?php

namespace App\Controller;


use App\Entity\AllocatedTopic;
use App\Entity\Student;
use App\Entity\Supervisor;
use App\Entity\Topic;
use App\Form\ProjectTopicType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

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
     * @param Session $session
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function editTopic(Session $session, $id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $edited = false;
        $topic = $em->getRepository(Topic::class)->find((int)$id);
        $form = $this->createForm(ProjectTopicType::class,$topic);
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
}