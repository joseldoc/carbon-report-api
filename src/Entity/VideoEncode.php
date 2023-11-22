<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\VideoEncodeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\ImportEncoder;

#[ORM\Entity(repositoryClass: VideoEncodeRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            name: 'videos_import_csv',
            controller: ImportEncoder::class,
            uriTemplate: '/video_encodes/import',
            deserialize:false,
            validationContext: [],
            openapiContext: [
                "summary" => "Import Video encoder CSV",
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
        'groups' => ['encoder:read', 'video:read'],
    ],
    denormalizationContext: [
        'groups' => ['encoder:write']
    ]
)]
class VideoEncode
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column]
    #[Groups(['encoder:read'])]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['encoder:read', 'encoder:write', 'video:read'])]
    private ?Video $video = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Groups(['encoder:read', 'encoder:write'])]
    private ?string $size = null;

    #[ORM\Column(length: 100)]
    #[Groups(['encoder:read', 'encoder:write'])]
    private ?string $quality = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getVideo(): ?Video
    {
        return $this->video;
    }

    public function setVideo(Video $video): static
    {
        $this->video = $video;

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

    public function getQuality(): ?string
    {
        return $this->quality;
    }

    public function setQuality(string $quality): static
    {
        $this->quality = $quality;

        return $this;
    }
}
