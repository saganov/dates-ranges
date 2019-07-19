<?php

namespace DateRange\Controllers;

use DateRange\Core\Controller;
use DateRange\Models\RangeRequest;
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
     * @param RangeRequest $range
     * @return Response
     * @throws Exception
     */
    public function save(RangeRequest $range): Response
    {
        $this->rangeService()->save($range);
        return $this->response(null, Response::HTTP_CREATED);
    }

    /**
     * @param RangeRequest|null $range
     * @return Response
     * @throws Exception
     */
    public function delete(?RangeRequest $range): Response
    {
        $this->rangeService()->delete($range);
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