<?php
namespace App\Http\Payloads;

class UnauthorizedPayload extends Payload
{
    protected $status = 401;
    protected $data = ['message' => 'Вы не авторизованы'];

    public function __construct(string $message = null)
    {
        if($message) $this->data['message'] = $message;
        parent::__construct($this->data, $this->status);
    }
}
