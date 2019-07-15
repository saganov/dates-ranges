<?php

namespace DateRange\Controllers;

use DateRange\Core\Controller;
use DateRange\Services\Range\RangeService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function save(Request $request): Response
    {
        $this->rangeService()->save(json_decode($request->getContent(), true));
        return $this->response(null, Response::HTTP_CREATED);
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