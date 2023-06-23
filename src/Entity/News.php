<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="news")
     * @ORM\JoinColumn(nullable=false)
     */
    private $administrator;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=ImageNews::class, mappedBy="news", orphanRemoval=true, cascade={"persist"})
     */
    private $imageNews;

    public function __construct()
    {
        $this->imageNews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, ImageNews>
     */
    public function getImageNews(): Collection
    {
        return $this->imageNews;
    }

    public function setImageNews(ImageNews $imageNews): self
    {
        if (!$this->imageNews->contains($imageNews)) {
            $this->imageNews[] = $imageNews;
            $imageNews->setNews($this);
        }

        return $this;
    }

    public function removeImage(ImageNews $imageNews): self
    {
        if ($this->imageNews->removeElement($imageNews)) {
            // set the owning side to null (unless already changed)
            if ($imageNews->getNews() === $this) {
                $imageNews->setNews(null);
            }
        }

        return $this;
    }
}

