<?php
namespace App\Http\Payloads;

class SuccessPayload extends Payload
{

    /**
     * @param <mixed> $data
     * @param string|null $message
     */
    public function __construct($data = null, string $message = null)
    {
        parent::__construct(
            array('success' => true, "data" => $data, 'message' => $message),
            $this->status
        );
    }
}
