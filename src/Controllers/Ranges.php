<?php

namespace DateRange\Controllers;

use DateRange\Core\Controller;
use DateRange\Services\Range\RangeService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class Ranges extends Controller
{
    /**
     * @return Response
     * @throws Exception
     */
    public function list(): Response
    {
        return $this->response($this->rangeService()->list());
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

    /**
     * @return RangeService
     * @throws Exception
     */
    private function rangeService(): RangeService
    {
        return $this->service(RangeService::class);
    }
}