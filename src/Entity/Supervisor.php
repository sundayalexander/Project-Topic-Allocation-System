<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use \DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SupervisorRepository")
 * @UniqueEntity("phone_number")
 * @UniqueEntity("email")
 */
class Supervisor implements UserInterface,  \Serializable{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $supervisor_id;

    /**
     * @ORM\OneToMany(targetEntity="Topic", mappedBy="supervisor")
     */
    private $topics;

    /**
     * @ORM\Column(type="string", length=4)
     * @Assert\Choice(
     *     choices={"Prof","Dr","Mr"},
     *     message="Please select a valid title")
     */
    private $title;

    /**
     * @ORM\Column(type="string",length=30)
     * @var string
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=20)
     */
    private $first_name;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @ORM\Column(type="string", length=20)
     */
    private $last_name;

    /**
     * @var string
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Length(min=10,max=14)
     * @ORM\Column(type="string", length=14,unique=true)
     */
    private $phone_number;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Email()
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min=8)
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value=0)
     * @Assert\NotNull()
     * @ORM\Column(type="integer", length=5)
     */
    private $capacity;

    /**
     * @ORM\GeneratedValue()
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $registered;

    public function __construct(){
        $this->topics = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getRegistered(): DateTime
    {
        return $this->registered;
    }

    /**
     * @param \DateTime $registered
     */
    public function setRegistered(DateTime $registered): void
    {
        $this->registered = $registered;
    }

    public function getRoles(){
        return ["ROLE_SUPERVISOR"];
    }

    /**
     * @return mixed
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * @param int $capacity
     */
    public function setCapacity(int $capacity): void
    {
        $this->capacity = $capacity;
    }

    public function getSupervisorId(): ?int
    {
        return $this->supervisor_id;
    }

    public function setSupervisorId(int $supervisor_id): self
    {
        $this->supervisor_id = $supervisor_id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = ucfirst($first_name);
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = ucfirst($last_name);

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(int $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * This
     * @param string $password
     * @return bool
     */
    public function isPasswordValid(string $password): bool {
        return password_verify($password,$this->password);
    }

    /**
     * This method return the
     * hashed password.
     * @return string
     */
    public function getHashedPassword(): string{
        return password_hash($this->password, PASSWORD_BCRYPT);
    }

    /**
     * @return Collection|Topic[]
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics[] = $topic;
            $topic->setSupervisor($this);
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->contains($topic)) {
            $this->topics->removeElement($topic);
            // set the owning side to null (unless already changed)
            if ($topic->getSupervisor() === $this) {
                $topic->setSupervisor(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()  {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([$this->supervisor_id,$this->username,$this->email,$this->password]);
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list($this->supervisor_id,$this->username,
            $this->email,$this->password) = unserialize($serialized);
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
