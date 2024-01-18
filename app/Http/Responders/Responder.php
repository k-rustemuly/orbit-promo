<?php
namespace App\Http\Responders;

abstract class Responder
{
    protected $response;

    public function withResponse($response)
    {
        $this->response = $response;
        return $this;
    }
}
