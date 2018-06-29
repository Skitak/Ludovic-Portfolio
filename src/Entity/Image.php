<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Il faut ajouter une image s'il vous plait")
     * @Assert\File(
     *     maxSize = "1024M",
     *     mimeTypes = {"image/png", "image/jpeg", "image/gif"},
     *     mimeTypesMessage = "Please upload a valid png, jpeg, jpg or gif"
     * )
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Il faut ajouter une image s'il vous plait")
     * @Assert\File(
     *     maxSize = "1024M",
     *     mimeTypes = {"image/png", "image/jpeg", "image/gif"},
     *     mimeTypesMessage = "Please upload a valid png, jpeg, jpg or gif"
     * )
     */
    private $thumbnail;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    public function getId()
    {
        return $this->id;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage(string $imageName)
    {
        $this->image = $imageName;

        return $this;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $imageName)
    {
        $this->thumbnail = $imageName;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }
}
