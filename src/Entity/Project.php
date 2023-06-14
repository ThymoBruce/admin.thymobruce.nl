<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modifyDate;

    //have to add the projectType entity first
    private $projectType;

    /**
     * @ORM\Column(type="integer")
     */
    private $user;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $gitRepository;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFinished;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $image;

    public function __construct()
    {
        $this->createDate = new \DateTime();
        $this->modifyDate = new \DateTime();
        $this->isDeleted = false;
        $this->isFinished = false;
        if(empty($this->description))
            $this->description = "";
    }

    /**
     * @param \DateTime $modifyDate
     */
    public function setModifyDate(\DateTime $modifyDate): void
    {
        $this->modifyDate = $modifyDate;
    }

    /**
     * @return \DateTime
     */
    public function getModifyDate(): \DateTime
    {
        return $this->modifyDate;
    }

    /**
     * @param \DateTime $createDate
     */
    public function setCreateDate(\DateTime $createDate): void
    {
        $this->createDate = $createDate;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate(): \DateTime
    {
        return $this->createDate;
    }

    /**
     * @return false
     */
    public function getIsDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * @param false $isDeleted
     */
    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getGitRepository()
    {
        return $this->gitRepository;
    }

    /**
     * @param false $isFinished
     */
    public function setIsFinished(bool $isFinished): void
    {
        $this->isFinished = $isFinished;
    }

    /**
     * @return false
     */
    public function getIsFinished(): bool
    {
        return $this->isFinished;
    }

    /**
     * @return mixed
     */
    public function getProjectType()
    {
        return $this->projectType;
    }

    /**
     * @param mixed $projectType
     */
    public function setProjectType($projectType): void
    {
        $this->projectType = $projectType;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @param mixed $gitRepository
     */
    public function setGitRepository($gitRepository): void
    {
        $this->gitRepository = $gitRepository;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }
}