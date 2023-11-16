<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
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
    private $firstname;

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
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Veuillez saisir votre numéro de téléphone.")
     * @Assert\Regex(
     *     pattern="/^\+?[0-9]+$/",
     *     message="Le numéro de téléphone est invalide."
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Veuillez saisir votre code postal.")
     * @Assert\Length(
     *     max = 10,
     *     maxMessage = "Ce champ ne peut pas dépasser {{ limit }} caractères."
     * )
     * @Assert\Regex(
     *     pattern="/^[0-9]+$/",
     *     message="Le code postal est invalide."
     * )
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Veuillez saisir votre ville.")
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Veuillez saisir votre code postal.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]{2,}/",
     *     message="Le nom du pays est invalide."
     * )
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity=OrderDetails::class, mappedBy="relation")
     */
    private $orderDetails;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, OrderDetails>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetails $orderDetail): self
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails[] = $orderDetail;
            $orderDetail->setCustomer($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetails $orderDetail): self
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getCustomer() === $this) {
                $orderDetail->setCustomer(null);
            }
        }

        return $this;
    }
}
