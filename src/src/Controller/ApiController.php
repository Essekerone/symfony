<?php

namespace App\Controller;

use App\Services\CurrencyService;
use App\ValueObjects\CurrencyServiceRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/show', name: 'show')]
    public function index(CurrencyService $service, Request $request): Response
    {
        try {
            if ($code = $request->query->get(CurrencyServiceRequest::FILTER_KEY)) {
                $result = $service->filter($code);
                if ($result) {
                    $result = array_pop($result);
                }
            } else {
                $result = $service->get();
            }
        } catch (\Throwable $exception) {
            // LOG::LOG($exception);
            // MESSENGER->err("hmm")
        }

        return $this->json([
            'result' => $result
        ]);
    }
}
