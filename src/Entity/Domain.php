<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DomainRepository")
 * @UniqueEntity("name", message="This domain name already exist!")
 */
class Domain
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Topic", mappedBy="domain")
     */
    private $topics;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_date;

    public function __construct(){
        $this->topics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedDate(): ?\DateTimeInterface{
        return $this->created_date;
    }

    public function setCreatedDate(\DateTimeInterface $created_date): self
    {
        $this->created_date = $created_date;
        return $this;
    }

    /**
     * @return Collection|Topic[]
     */
    public function getTopics(): Collection{
        return $this->topics;
    }

    public function addTopic(Topic $topic): self{
        if (!$this->topics->contains($topic)) {
            $this->topics[] = $topic;
            $topic->setDomain($this);
        }
        return $this;
    }

}
