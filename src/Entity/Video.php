<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;


#[ORM\Entity(repositoryClass: VideoRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ],
    normalizationContext: [
        'groups' => ['video:read'],
    ],
    denormalizationContext: [
        'groups' => ['video:write']
    ]
)]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['video:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['video:read', 'video:write'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['video:read', 'video:write'])]
    private ?int $duration = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Groups(['video:read', 'video:write'])]
    private ?string $size = null;

    #[ORM\Column(length: 100)]
    #[Groups(['video:read', 'video:write'])]
    private ?string $video_quality = null;

    #[ORM\ManyToMany(targetEntity: Folder::class, mappedBy: 'video')]
    private Collection $folders;

    public function __construct()
    {
        $this->folders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getVideoQuality(): ?string
    {
        return $this->video_quality;
    }

    public function setVideoQuality(string $video_quality): static
    {
        $this->video_quality = $video_quality;

        return $this;
    }

    /**
     * @return Collection<int, Folder>
     */
    public function getFolders(): Collection
    {
        return $this->folders;
    }

    public function addFolder(Folder $folder): static
    {
        if (!$this->folders->contains($folder)) {
            $this->folders->add($folder);
            $folder->addVideo($this);
        }

        return $this;
    }

    public function removeFolder(Folder $folder): static
    {
        if ($this->folders->removeElement($folder)) {
            $folder->removeVideo($this);
        }

        return $this;
    }
}
