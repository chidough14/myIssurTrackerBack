<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        //$this->validateEmail($request);


        $response = $this->broker()->sendResetLink(
             $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }



    public function broker()
    {
        return Password::broker();
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        //return back()->with('status', trans($response));
        return response()->json(['message'=> 'Email Sent', 'response'=> $response], 200);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        /* return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans($response)]); */

        return response()->json(['message'=> 'Failed to send Mail', 'response'=> $response], 500);
    }



}
