<?php
namespace App\Http\Payloads;

class PaginatePayload extends Payload
{

    /**
     * @param <mixed> $data
     * @param string|null $message
     */
    public function __construct($data = null)
    {
        $data["success"] = true;
        $data["message"] = null;
        parent::__construct($data, $this->status);
    }
}
