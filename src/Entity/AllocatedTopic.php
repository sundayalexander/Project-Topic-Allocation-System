<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AllocatedTopicRepository")
 */
class AllocatedTopic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $allocated_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $allocated_date;

    /**
     * @ORM\OneToOne(targetEntity="Student", inversedBy="alloctedTopic")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="student_id")
     */
    private $student;

    /**
     * @ORM\OneToOne(targetEntity="Topic", inversedBy="allocated")
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="topic_id")
     */
    private $topic;

    public function getAllocatedDate(): ?\DateTimeInterface
    {
        return $this->allocated_date;
    }

    public function setAllocatedDate(\DateTimeInterface $allocated_date): self
    {
        $this->allocated_date = $allocated_date;

        return $this;
    }

    public function getAllocatedId(): ?int
    {
        return $this->allocated_id;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }
}
