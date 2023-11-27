<?php
    namespace App\Service;

    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Serializer\SerializerInterface;
    use App\Entity\Report;
    use App\Entity\VideoEncode;
    use App\Entity\Views;
    use App\Entity\Video;
    use App\Entity\Folder;

    enum Mode: string {
        case VIDEO= 'VIDEO';
        case FOLDER= 'FOLDER';
    }

    class ReportService {

        public const UNIT_VIEW_CARBON = 0.05;

        public function __construct(
            private EntityManagerInterface $entityManager,
            private SerializerInterface $serializer
        ) {
        }

        /**
         * Format object reponse report
         * @param Report $report
         * @return array
         */
        public function formatReportResponse(Report $report) {
            $data['id'] = $report->getId();
            $data['mode'] = $report->getMode();
            $data['createdAt'] = $report->getCreatedAt() ? $report->getCreatedAt()->format('Y-m-d\TH:i:sO') : '';
            
            // Create video serialisation
            $data['videos'] = [];
            foreach ($report->getVideos() as $video) {

                $videoData = $this->serializer->normalize($video, null, ['groups' => ['video:read', 'report:read']]);
                $videoEncoder =  $this->entityManager->getRepository(VideoEncode::class)->findOneBy(['video' => $video->getId()]);
                // get video encoder object
                $encoderData = $this->serializer->normalize($videoEncoder, null, ['groups' => ['report:read']]);

                // Get hte veiw object
                /** @var Views $views */
                $views = $this->entityManager->getRepository(Views::class)->findOneBy(['video' => $video->getId(), 'report' => $report->getId()]);
                if(!$views instanceof Views) {
                    $videoData['number_views'] = 0;
                } else {
                    $videoData['number_views'] = $views->getNumberViews();
                }
                // consume carbone
                $videoData['consume_carbon'] = self::consumeCarbon($videoData['number_views'], $video->getSize());
                $encoderData['consume_carbon'] = self::consumeCarbon($videoData['number_views'], $videoEncoder->getSize());
                $videoData['earnCarbon'] = self::earnCarbon($videoData['consume_carbon'], $encoderData['consume_carbon']);
                $videoData['unit_price'] = self::UNIT_VIEW_CARBON;
                // encoder data
                $videoData['encoder'] = $encoderData;
                $data['videos'][] = $videoData;
            }
            return $data;
        }

        /**
         * @param float $carbonConsumer
         * @param float $encodedCarbonConsumers
         * @return float
         */
        public function earnCarbon(float $carbonConsumer, float $encodedCarbonConsumer) {
            return $carbonConsumer - $encodedCarbonConsumer;
        }

        /**
         * calculate the carbon consumers by a video
         * @param int $number_views
         * @param int $size
         * @return float
         */
        public static function consumeCarbon(int $number_views, int $size) {
            $size = intval($size);
            $encodedCarbonConsumptionPerView = self::UNIT_VIEW_CARBON * ($size/1024/1024) / $number_views;
            $consumeCarbon = $encodedCarbonConsumptionPerView * $number_views;
            return round($consumeCarbon, 7);
        }

        public function createVideoReport(array $contentRequest): array {
            // Create report
            $report = new Report();
            $report->setMode($contentRequest['mode']);

            $this->entityManager->persist($report);
            $this->entityManager->flush();
            foreach ($contentRequest['data'] as $item) {
                // Create a view video
                /** @var Video $video */
                $video = $this->entityManager->getRepository(Video::class)->find($item['id']);
                if(!$video instanceof Video) {
                    return new JsonResponse(['message' => 'Impossible de generer un rapport'], Response::HTTP_BAD_REQUEST);
                }
                // Create view
                /** @var Views $view */
                $view = new Views();
                $view->setVideo($video);
                $view->setNumberViews($item['number_views']);
                $view->setReport($report);

                // add video to report
                $report->addVideo($video);
                // store the data
                $this->entityManager->persist($view);
                $this->entityManager->flush();
            }
            return $this->formatReportResponse($report);
        }
    }