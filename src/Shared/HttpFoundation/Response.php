<?php
declare(strict_types=1);

namespace Shared\HttpFoundation;


use Shared\Serializer\Normalizer\MoneyNormalizer;
use Shared\Serializer\Normalizer\ResponseNormalizer;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Response
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * Response constructor.
     *
     * @param ResponseNormalizer $responseNormalizer
     * @param MoneyNormalizer $moneyNormalizer
     */
    public function __construct(ResponseNormalizer $responseNormalizer, MoneyNormalizer $moneyNormalizer)
    {
        $this->serializer = new Serializer([$responseNormalizer, $moneyNormalizer], [new JsonEncoder()]);
    }

    /**
     * @param $data
     * @param int $status
     * @param array $headers
     * @param array $context
     *
     * @return JsonResponse
     */
    public function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        $json = $this->serializer->serialize($data, 'json', $context);

        return new JsonResponse($json, $status, $headers, true);
    }

    /**
     * @param string $url
     *
     * @return RedirectResponse
     */
    public function redirect(string $url): RedirectResponse
    {
        return new RedirectResponse($url);
    }

    /**
     * @return JsonResponse
     */
    public function badRequest(): JsonResponse
    {
        return $this->json([], 400);
    }

    /**
     * @param $data
     *
     * @return JsonResponse
     */
    public function invalidRequest($data): JsonResponse
    {
        return $data instanceof ConstraintViolationListInterface ? $this->json($this->getFormArrayErrors($data), 422) : $this->json(
            $data,
            422
        );
    }

    /**
     * @param string $message
     *
     * @return JsonResponse
     */
    public function ok($message = 'ok'): JsonResponse
    {
        return $this->json([$message]);
    }
}