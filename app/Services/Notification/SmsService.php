<?php
namespace App\Services\Notification;

use SoapClient;
use App\SmsServiceInterface;
use Illuminate\Support\Facades\Log;

class SmsService implements SmsServiceInterface {
    protected $client;
    protected $config;

    public function __construct() {
        $this->config = config('services.sms');
        $this->initializeClient();
    }

    protected function initializeClient(): void {
        $this->client = new SoapClient($this->config['uri'], [
            'trace' => true,
            'exceptions' => true,
            'encoding' => 'UTF-8',
            'cache_wsdl' => WSDL_CACHE_NONE
        ]);
    }

    public function send(string $to, string $message): bool {
        try {
            $result = $this->client->send([
                'from' => $this->config['from_number'],
                'to' => $to,
                'message' => $message,
                'user' => $this->config['username'],
                'pass' => $this->config['password']
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('SMS sending failed', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function sendPattern(string $to, string $patternCode, array $inputData): bool
    {
        try {
            $this->client->sendPatternSms(
                $this->config['auth']['fromNum'],
                $to,
                $this->config['auth']['user'],
                $this->config['auth']['pass'],
                $patternCode,
                $inputData
            );

            return true;

        } catch (\Exception $e) {
            Log::error('Pattern SMS sending failed', [
                'to' => $to,
                'pattern' => $patternCode,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}