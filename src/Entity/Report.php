<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use App\Repository\ReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimeStampableTrait;
use App\Controller\AddReport;
use App\Controller\ListReport;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            name: 'create_report',
            controller: AddReport::class,
            uriTemplate: '/reports',
            read: false
        ),
        new Get(
            name: 'get_reports_by_id',
            uriTemplate: '/reports/{id}',
            requirements: ['id' => '\d+']
        ),
        new GetCollection(
            name: 'get_all_reports',
            uriTemplate: '/reports',
            controller: ListReport::class
        ),
        new Patch(),
        new Delete()
    ],
    normalizationContext: [
        'groups' => ['report:read', 'videos:read', 'timestamp'],
    ],
    denormalizationContext: [
        'groups' => ['folder:write']
    ]
)]
class Report
{
    use TimeStampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['report:read'])]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Video::class, inversedBy: 'reports')]
    #[Groups(['report:read', 'video:read'])]
    private Collection $videos;

    #[ORM\Column(length: 10)]
    #[Groups(['report:read'])]
    private ?string $mode = 'VIDEO';

    public function __construct()
    {
        $this->videos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): static
    {
        if (!$this->videos->contains($video)) {
            $this->videos->add($video);
        }

        return $this;
    }

    public function removeVideo(Video $video): static
    {
        $this->videos->removeElement($video);

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): static
    {
        $this->mode = $mode;

        return $this;
    }
}
