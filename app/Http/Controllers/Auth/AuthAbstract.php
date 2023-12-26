<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TurboSms\TurboSmsService;
use Illuminate\Support\Facades\Redis;

class AuthAbstract extends Controller
{
    const REDIS_EXPIRE_TIME = 300;

    protected function redisSetCode(string $phone, int $code): void
    {
        Redis::set("sms:{$phone}", $code);
        Redis::expire("sms:{$phone}", self::REDIS_EXPIRE_TIME);
    }

    protected function putSession(array $arraySessionData): void
    {
        foreach ($arraySessionData as $key => $data) {
            session()->put($key, $data);
        }
    }

    protected function generateCode(): int
    {
        return rand(1111, 9999);
    }

    protected function checkRedisCodeAndSendSms(string $phone, int $code, string $message)
    {
        if (Redis::get("sms:{$phone}")) {
            return redirect()->route('auth.register')->with('error', 'Новий код ви можете отримати через 5 хвилин');
        }
        $turboSmsService = new TurboSmsService();
        $sendSmsResponse = $turboSmsService->sendSms($phone, $message);
        $sendSmsResponse = json_decode($sendSmsResponse);
        if ($sendSmsResponse->response_status === TurboSmsService::SUCCESS_MESSAGE_ACCEPTED) {
            $this->redisSetCode($phone, $code);

            return redirect()->route('auth.verify');
        }

        return $sendSmsResponse->response_status;
    }
}
