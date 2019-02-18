<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TopicRepository")
 * @UniqueEntity("name",message="This topic name already exist.")
 */
class Topic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $topic_id;

    /**
     * @ORM\ManyToOne(targetEntity="Supervisor", inversedBy="topics")
     * @ORM\JoinColumn(name="supervisor_id", referencedColumnName="supervisor_id")
     */
    private $supervisor;

    /**
     * @ORM\OneToOne(targetEntity="AllocatedTopic", mappedBy="topic")
     */
    private $allocated;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $domain;

    /**
     * @ORM\Column(type="datetime")
     */
    private $added_date;

    /**
     * Topic constructor.
     */
    public function __construct()
    {
        $this->added_date = new \DateTime("now");
    }

    /**
     * @return mixed
     */
    public function getTopicId()
    {
        return $this->topic_id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = ucwords($name);

        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = ucwords($domain);

        return $this;
    }

    public function getAddedDate(): ?\DateTime
    {
        return $this->added_date;
    }

    public function setAddedDate(\DateTime $added_date): self
    {
        $this->added_date = $added_date;

        return $this;
    }

    public function getSupervisor(): ?Supervisor
    {
        return $this->supervisor;
    }

    public function setSupervisor(?Supervisor $supervisor): self
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    public function getAllocated(): ?AllocatedTopic
    {
        return $this->allocated;
    }

    public function setAllocated(?AllocatedTopic $allocated): self
    {
        $this->allocated = $allocated;

        // set (or unset) the owning side of the relation if necessary
        $newTopic = $allocated === null ? null : $this;
        if ($newTopic !== $allocated->getTopic()) {
            $allocated->setTopic($newTopic);
        }

        return $this;
    }
}
