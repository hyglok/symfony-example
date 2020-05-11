<?php
declare(strict_types=1);

namespace Lib\HttpFoundation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

final class JsonValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * JsonValueResolver constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $request->getContentType() == "json";
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield  $this->serializer->deserialize($request->getContent(), $argument->getType(), 'json');
    }
}
