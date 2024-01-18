<?php
namespace App\Http\Payloads;

class NotFoundPayload extends Payload
{
    protected $status = 404;

    public function __construct(string $message = null)
    {
        parent::__construct(array('success' => false, 'message' => $message), $this->status);
    }
}
