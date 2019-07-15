<?php

namespace DateRange\Core;

use Exception;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    /**
     * @var ServiceLocator
     */
    private $services;

    /**
     * Controller constructor.
     * @param ServiceLocator $services
     */
    public function __construct(ServiceLocator $services)
    {
        $this->services = $services;
    }

    /**
     * @param $body
     * @param int $status
     * @return Response
     */
    protected function response($body, int $status = Response::HTTP_OK): Response
    {
        if ($body instanceof ArraySerializable) {
            $body = $body->toArray();
        }
        if (!is_string($body)) {
            $body = json_encode($body, true);
        }
        return new Response($body, $status, ['Content-Type', 'application/json;charset=UTF-8']);
    }

    /**
     * @param string $abstraction
     * @return mixed
     * @throws Exception
     */
    protected function service(string $abstraction)
    {
        return $this->services->get($abstraction);
    }
}