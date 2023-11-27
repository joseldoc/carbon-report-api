<?php
    namespace App\Controller;

    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpKernel\Attribute\AsController;
    use App\Entity\Report;
    use App\Entity\Views;
    use App\Service\ReportService;

    #[AsController]
    class ListReport {
        public function __construct(
            readonly private ReportService $reportService,
            readonly private EntityManagerInterface $entityManager
        ) {
        }

        /**
         * @return JsonResponse
         */
        public function __invoke(): JsonResponse {
            $reports = $this->entityManager->getRepository(Report::class)->findAll();
            $datas = [];
            foreach ($reports as $report) {
                $datas [] = $this->reportService->formatReportResponse($report);
            }
            return new JsonResponse($datas, Response::HTTP_OK);
        }
    }
