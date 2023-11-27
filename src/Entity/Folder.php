<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\FolderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\ImportFolder;

#[ORM\Entity(repositoryClass: FolderRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            name: 'folders_import_csv',
            controller: ImportFolder::class,
            uriTemplate: '/folders/import',
            deserialize:false,
            validationContext: [],
            openapiContext: [
                "summary" => "Import Folders CSV",
                "requestBody"=> ["required" => false, "content" => []],
                "parameters"=> [],
                "responses"=> [
                    "200" => [
                        "description" =>"CSV imported successfully"
                    ]
                ]
            ],
        ),
        new Get(),
        new GetCollection()
    ],
    normalizationContext: [
        'groups' => ['folder:read', 'video:read', 'report:read', 'timeStamp'],
    ],
    denormalizationContext: [
        'groups' => ['folder:write']
    ]
)]
class Folder
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column]
    #[Groups(['folder:read', 'report:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['folder:read', 'folder:write', 'report:read'])]
    private ?string $dossier = null;

    #[ORM\ManyToMany(targetEntity: Video::class, inversedBy: 'folders')]
    private Collection $video;

    public function __construct()
    {
        $this->video = new ArrayCollection();
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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
