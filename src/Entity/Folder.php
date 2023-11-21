<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\FolderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FolderRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ],
    normalizationContext: [
        'groups' => ['folder:read', 'video:read'],
    ],
    denormalizationContext: [
        'groups' => ['folder:write']
    ]
)]
class Folder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['folder:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['folder:read', 'folder:write'])]
    private ?string $dossier = null;

    #[ORM\ManyToMany(targetEntity: Video::class, inversedBy: 'folders')]
    #[Groups(['folder:read', 'video:read'])]
    private Collection $video;

    public function __construct()
    {
        $this->video = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDossier(): ?string
    {
        return $this->dossier;
    }

    public function setDossier(string $dossier): static
    {
        $this->dossier = $dossier;

        return $this;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getVideo(): Collection
    {
        return $this->video;
    }

    public function addVideo(Video $video): static
    {
        if (!$this->video->contains($video)) {
            $this->video->add($video);
        }

        return $this;
    }

    public function removeVideo(Video $video): static
    {
        $this->video->removeElement($video);

        return $this;
    }
}
