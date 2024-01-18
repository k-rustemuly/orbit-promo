<?php

namespace App\Http\Controllers;

use App\Http\Responders\JsonResponder as Responder;
use App\Http\Payloads\SuccessPayload;
use App\Http\Payloads\UnauthorizedPayload;
use App\Http\Payloads\NotFoundPayload;
use App\Http\Payloads\ErrorPayload;
use App\Http\Payloads\PaginatePayload;

class BaseController extends Controller
{
    protected $responder;

    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
    }

    public function success($data = null, string $message = null)
    {
        return $this->responder->withResponse(
            isset($data["data"]) ?
            new PaginatePayload($data):
            new SuccessPayload($data, $message)
        )->respond();
    }

    public function unauthorized(string $message = null)
    {
        return $this->responder->withResponse(
            new UnauthorizedPayload($message)
        )->respond();
    }

    public function notFound(string $message = "Не найдено")
    {
        return $this->responder->withResponse(
            new NotFoundPayload($message)
        )->respond();
    }

    public function error(string $message = "Ошибка"){
        return $this->responder->withResponse(
            new ErrorPayload($message)
        )->respond();
    }
}
