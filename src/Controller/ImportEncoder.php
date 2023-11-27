<?php
namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\VideoEncode;
use App\Entity\Video;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ReadCsvFile;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ImportEncoder extends AbstractController {
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
        $filePath = $this->getParameter('kernel.project_dir') . '/public/assets/Liste-video-encoder.csv';
        $videoRecord = ReadCsvFile::getRecordsFile($filePath);

        foreach ($videoRecord as $record) {
            $videoEncode = new VideoEncode();
            $videoEncode->setId($record['id']);
            $videoEncode->setSize($record['size']);
            $videoEncode->setVideo($this->entityManager->getRepository(Video::class)->find(intval($record['video_id'])));
            $videoEncode->setQuality($record['quality']);
            $this->entityManager->persist($videoEncode);
        }
        $this->entityManager->flush();
        return new JsonResponse('Import successfull', Response::HTTP_OK);
    }
}