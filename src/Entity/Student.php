<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentRepository")
 * @UniqueEntity("matric_no", message="This Matric Number already exist!")
 * @UniqueEntity("email", message="This email address already exist!")
 */
class Student
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(name="student_id", type="integer", options={"unsigned": true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $student_id;

    /**
     * @ORM\OneToOne(targetEntity="AllocatedTopic", mappedBy="student")
     */
    private $alloctedTopic;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min=9)
     * @ORM\Column(type="integer",length=9, unique=true,options={"unsigned": true})
     */
    private $matric_no;
    /**
     * @ORM\Column(type="string",length=20)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    private $first_name;
    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @ORM\Column(type="string",length=20)
     */
    private $last_name;
    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string",length=50, unique=true)
     */
    private $email;
    /**
     * @Assert\Choice(
     *     choices={"2016/2017","2017/2018","2018/2019","2019/2020"},
     *     message="Select a valid session"
     * )
     * @ORM\Column(type="string",length=10)
     */
    private $session;
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @ORM\Column(type="string",length=50)
     */
    private $research_interest;
    /**
     * @ORM\GeneratedValue()
     * @ORM\Column(type="datetime")
     */
    private $registered;

    /**
     * @return int
     */
    public function getStudentId(): int
    {
        return $this->student_id;
    }

    /**
     * @param string $session
     */
    public function setSession(string $session): void
    {
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function getSession(){
        return $this->session;
    }

    /**
     * @param string $research_interest
     */
    public function setResearchInterest(string $research_interest): void
    {
        $this->research_interest = $research_interest;
    }

    /**
     * @return string
     */
    public function getResearchInterest()
    {
        return $this->research_interest;
    }

    /**
     * @return \DateTime
     */
    public function getRegistered(): \DateTime
    {
        return $this->registered;
    }

    /**
     * @param \DateTime $registered
     */
    public function setRegistered(\DateTime $registered): void
    {
        $this->registered = $registered;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    /**
     * This method returns the matric number of a
     * student.
     * @return integer
     */
    public function getMatricNo(){
        return $this->matric_no;
    }

    /**
     * This method set the matric number of  a student.
     * @param int $matric_no
     */
    public function setMatricNo(int $matric_no){
        $this->matric_no = $matric_no;
    }

    /**
     * @return string
     */
    public function getFirstName():?string    {
        return $this->first_name;
    }

    /**
     * @param string  $first_name
     */
    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getAlloctedTopic(): ?AllocatedTopic
    {
        return $this->alloctedTopic;
    }

    public function setAlloctedTopic(?AllocatedTopic $alloctedTopic): self
    {
        $this->alloctedTopic = $alloctedTopic;

        // set (or unset) the owning side of the relation if necessary
        $newStudent = $alloctedTopic === null ? null : $this;
        if ($newStudent !== $alloctedTopic->getStudent()) {
            $alloctedTopic->setStudent($newStudent);
        }

        return $this;
    }
}
