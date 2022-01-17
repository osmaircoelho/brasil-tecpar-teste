<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\HashService;
use App\Repository\HashRepository;

class HashController extends AbstractController
{
    public function __construct(
         private HashService $hashService,
         private HashRepository $hashRepository
    ) {}

    #[Route('/hash', name: 'hash_list')]
    public function index(Request $request): JsonResponse{

        $numberAttempts = (int) $request->query->get('number-attempts');

        return new JsonResponse([
            'data' => $this->hashRepository->findAllFilterPaginate($numberAttempts),
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);

    }

    #[Route('/hash/create', name:'hash_create')]
    public function createHash(Request $request): JsonResponse{

        $string = $request->query->get('string');

        $response['data'] = [];
        $response['status'] = Response::HTTP_CREATED;

        try {
            $response['data'] = $this->hashService
                ->setString($string)
                ->setAttempts(10000000)
                ->createHash();
        } catch (\Exception $exception) {
            $response['data'] = $exception->getMessage();
            $response['status'] = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return new JsonResponse([
            'data' => $response['data'],
            'status' => $response['status']
        ], $response['status']);
    }
}
