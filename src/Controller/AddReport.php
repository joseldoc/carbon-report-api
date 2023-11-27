<?php   
    namespace App\Controller;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpKernel\Attribute\AsController;
    use App\Entity\Video;
    use App\Entity\Views;
    use App\Entity\Report;
    use App\Service\ReportService;
    use Symfony\Component\Serializer\SerializerInterface;

    #[AsController]
    class AddReport extends AbstractController {

        public function __construct(
            readonly private ReportService $service,
            readonly private SerializerInterface $serializer
        ) {

        }
        public function __invoke(Request $request)
        {
            // Get informations data
            $contentRequest = $this->serializer->decode($request->getContent(), 'json');
            if($contentRequest['mode'] === 'VIDEO') {
                $data = $this->service->createVideoReport($contentRequest);
            } else {
                // create report folder mode
                $data = [];
            }
            // return Object report
            return new JsonResponse($data, Response::HTTP_OK);
        }
    }