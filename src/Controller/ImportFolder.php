<?php
namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Video;
use App\Entity\Folder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ReadCsvFile;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ImportFolder extends AbstractController {
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
        $filePath = $this->getParameter('kernel.project_dir') . '/public/assets/Liste-dossier-video.csv';
        $videoRecord = ReadCsvFile::getRecordsFile($filePath);

        foreach ($videoRecord as $record) {
            $folder = $this->entityManager->getRepository(Folder::class)->findOneBy(['id' => $record['id']]);
            $video = $this->entityManager->getRepository(Video::class)->find($record['video']);
            if($folder) { // Verify if Folder exist
                $folder->addVideo($video);
                $this->entityManager->flush();
            } else {
                // Create Folder
                $folder = new Folder();
                $folder->setId($record['id']);
                $folder->setDossier($record['dossier']);
                $folder->addVideo($video);
                $this->entityManager->persist($video);
                $this->entityManager->flush();
            }

        }
        return new JsonResponse('Import successfull', Response::HTTP_OK);
    }
}