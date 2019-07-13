<?php

namespace DateRange\Controllers;

use DateRange\Core\Controller;
use Symfony\Component\HttpFoundation\Response;

class Ranges extends Controller
{
    /**
     * @return Response
     */
    public function list(): Response
    {
        return $this->response([
            [
                'start' => 'Y-m-d',
                'end' => 'Y-m-d',
                'price' => 15
            ]
        ]);
    }

    /**
     * @return Response
     */
    public function save(): Response
    {
        return $this->response([
            [
                'start' => 'Y-m-d',
                'end' => 'Y-m-d',
                'price' => 15,
            ]
        ]);
    }

    /**
     * @param $range
     * @return Response
     */
    public function delete($range): Response
    {
        return $this->response(null, Response::HTTP_NO_CONTENT);
    }
}