<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\IsTrue;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(
     *     message = "Merci de remplir ce champ."
     * )
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "Ce champ doit comporter au moins {{ limit }} caractères.",
     *     maxMessage = "Ce champ ne peut pas dépasser {{ limit }} caractères."
     * )
     *  * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message = "Ce champ ne doit pas contenir des nombres."
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(
     *     message = "Merci de remplir ce champ."
     * )
     * @Assert\Email(
     *     message = "L'email {{ value }} n'est pas valide."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(
     *     message = "Merci de remplir ce champ."
     * )
     * @Assert\Length(
     * min = 2,
     * max = 50,
     * minMessage = "Ce champ doit comporter au moins {{ limit }} caractères.",
     * maxMessage = "Ce champ ne peut pas dépasser {{ limit }} caractères."
     * )
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     *     message = "Merci d'ajouter un message."
     * )
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="contacts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $administrator;


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
        $this->name = $name;

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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getAdministrator(): ?admin
    {
        return $this->administrator;
    }

    public function setAdministrator(?admin $administrator): self
    {
        $this->administrator = $administrator;

        return $this;
    }
}
