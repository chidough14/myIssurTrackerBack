<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function reset(ResetPasswordRequest $request)
    {
       // $request->validate($this->rules(), $this->validationErrorMessages());

        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($request, $response)
                    : $this->sendResetFailedResponse($request, $response);
    }

    protected function credentials(Request $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }

    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        //event(new PasswordReset($user));

        //$this->guard()->login($user);
    }

    protected function setUserPassword($user, $password)
    {
        $user->password = Hash::make($password);
    }

    public function broker()
    {
        return Password::broker();
    }



    protected function sendResetResponse(Request $request, $response)
    {
        /* return redirect($this->redirectPath())
                            ->with('status', trans($response)); */

        return response()->json(['message'=> 'Password Reset Success', 'response'=> $response], 200);
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        /* return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]); */

        return response()->json(['message'=> 'Password Reset Failed', 'response'=> $response], 500);
    }
}
