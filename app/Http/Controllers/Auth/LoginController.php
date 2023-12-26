<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\TurboSms\TurboSmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redis;

class LoginController extends AuthAbstract
{
    public function loginUser(LoginRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::query()->where('phone', $data['phone'])->first();

        if (!$user) {
            return redirect()->route('auth.register');
        }

        $this->putSession(
            [
                'phone' => $data['phone']
            ]
        );

        $code = $this->generateCode();

        $message = 'Your login code: ' . $code;

        //Відправлення смс з інтервалом 5 хв
        $this->checkRedisCodeAndSendSms($data['phone'], $code, $message);

        return redirect()->route('auth.verify');
    }
}
