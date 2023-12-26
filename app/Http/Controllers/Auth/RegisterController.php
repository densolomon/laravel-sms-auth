<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\TurboSms\TurboSmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redis;

class RegisterController extends AuthAbstract
{

    public function create(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::query()->where('phone', $data['phone'])->first();
        if ($user) {
            return redirect()->route('auth.login')->with('error', 'Ви вже в базі');
        }

        $this->putSession(
            [
                'name' => $data['name'],
                'phone' => $data['phone'],
            ]
        );

        $code = $this->generateCode();

        $message = 'Your registration code: ' . $code;

        //Відправлення смс з інтервалом 5 хв
        $this->checkRedisCodeAndSendSms($data['phone'], $code, $message);

        return redirect()->route('auth.register')->with('error', 'Сталася помилка. Будь ласка спробуйте пізніше');
    }
}
