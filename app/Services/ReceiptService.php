<?php

namespace App\Services;

use App\Models\Receipt;
use App\Models\ReceiptStatus;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Zxing\QrReader;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ReceiptService
{

    public string $url;

    public $receipt_status_id = ReceiptStatus::NOT_FOUND;

    public $file;

    public function isAccesed(UploadedFile $file): bool
    {
        if($result = $this->recognize($file)) {
            if($this->isOfd($result)) {
                Log::debug($result);

                if($this->isUnique() && $this->isHavePosition($result, 'orbit')) {
                    $this->receipt_status_id = ReceiptStatus::ACCEPTED;
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param \Illuminate\Http\UploadedFile $file
     * @return null|string
     */
    public function recognize(UploadedFile $file): ?string
    {
        $this->file = $file;
        $qrCodeReader = new QrReader($file->getPathname());
        $result = $qrCodeReader->text();
        Log::debug($qrCodeReader->text());

        if($result != "") {
            return $result;
        }
        return null;
    }

    public function isUnique(): bool
    {
        return !Receipt::where('url', $this->getFiscalSign())->where('receipt_status_id', ReceiptStatus::ACCEPTED)->exists();
    }

    public function getFiscalSign()
    {
        $queryParams = [];
        parse_str(parse_url($this->url, PHP_URL_QUERY), $queryParams);

        $iValue = isset($queryParams['i']) ? $queryParams['i'] : $this->url;
        return $iValue;
    }

    public function isOfd(string $url): bool
    {
        if(Str::isUrl($url)) {
            $host = parse_url($url)['host'];
            $ofd_domains = Arr::get(config('settings'), 'ofd_domains');

            if(in_array($host, array_keys($ofd_domains))) {
                $this->url = $url;
                return true;
            }
        }
        return false;
    }

    public function isHavePosition(string $url, string $searchPosition): bool
    {
        $operator = Arr::get(config('settings.ofd_domains'), parse_url($url)['host']);
        $positions = $operator::make($url)->positions();
        return strpos(implode('|', $positions), $searchPosition) !== false;
    }

    public function store(User $user)
    {
        $receipt = Receipt::create([
            'user_id' => $user->id,
            'receipt_status_id' => $this->receipt_status_id,
            'url' => $this->getFiscalSign()
        ]);
        $receipt->addMedia($this->file)->toMediaCollection('images');
    }
}
