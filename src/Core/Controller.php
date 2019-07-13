<?php

namespace DateRange\Core;

use Symfony\Component\HttpFoundation\Response;

class Controller
{
    /**
     * @param $body
     * @param int $status
     * @return Response
     */
    protected function response($body, int $status = Response::HTTP_OK): Response
    {
        if (is_array($body)) {
            $body = json_encode($body, true);
        }
        return new Response($body, $status);
    }
}