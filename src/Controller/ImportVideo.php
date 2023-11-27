<?php
namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Video;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ReadCsvFile;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ImportVideo extends AbstractController {
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer
    ) {
    }

    /**
     * @return JsonResponse
     * @throws \League\Csv\Exception
     */
    public function __invoke(): JsonResponse {
        $filePath = $this->getParameter('kernel.project_dir') . '/public/assets/Liste-video-source.csv';
        $videoRecord = ReadCsvFile::getRecordsFile($filePath);

        foreach ($videoRecord as $record) {
            $record['id'] = intval($record['id']);
            $record['duration'] = intval($record['duration']);

            $video = new Video();
            $video->setId($record['id']);
            $video->setDuration($record['duration']);
            $video->setName($record['name']);
            $video->setSize($record['size']);
            $video->setVideoQuality($record['video_quality']);
            //$video = $this->serializer->deserialize(json_encode($record), Video::class, 'json');
            $this->entityManager->persist($video);
        }
        $this->entityManager->flush();
        return new JsonResponse('Import successfull', Response::HTTP_OK);
    }
}