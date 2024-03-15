<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile_no' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            } else {
                $now = now();
                $otp = rand(123456, 999999);

                $userOtp = UserOtp::create([
                    'mobile_no' => $request->mobile_no,
                    'otp' => $otp,
                    'expire_at' => $now->addMinutes(5),
                ]);

                // Customize your message here
                $message = "Your One Time Password for MyApp is: $otp";

                $sid = env("TWILIO_SID");
                $token = env("TWILIO_TOKEN");
                $senderNumber = env("TWILIO_PHONE");
                $verifySid = 'VA02938b6b00961773943a9c18459fcf46';
                $client = new Client($sid, $token);

                //$client->messages->create($request->mobile_no, [
                //    'body' => $message,
                //    "from" => $senderNumber,
                //]);
                $verification = $client->verify->v2->services($verifySid)
                    ->verifications
                    ->create($request->mobile_no, "sms", [
                        "body" => $message
                    ]);



                return response()->json(['message' => $verification->status], 200);
            }
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function verifyOTP(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile_no' => 'required|string',
                'otp' => 'required|string'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->error()], 422);
            } else {
                $sid = env("TWILIO_SID");
                $token = env("TWILIO_TOKEN");
                $verifySid = 'VA02938b6b00961773943a9c18459fcf46';
                $verifiedNumber = '+94702752116';

                $twilio = new Client($sid, $token);

                $otpCode = $request->input('otp');

                $verificationCheck = $twilio->verify->v2->services($verifySid)
                    ->verificationChecks
                    //->create($request->mobile_no, ['code' => $otpCode]);
                    ->create([
                        'to' => $request->mobile_no,
                        'code' => $otpCode,
                    ]);
                if ($verificationCheck->valid) {
                    return  response(['verified' => true, 'status' => 200], 200);
                } else {
                    return  response(['verified' => false, 'status' => 200], 200);
                }
                //$userOtp = UserOtp::find()->where('mobile_no', request('mobile_no'))->first();
                //$now = now();
                //if ($userOtp && $now->isBefore($userOtp->expire_at)) {
                //    return response(['message' => 'success', 'status' => 200], 200);
                //} else {
                //    return response(['message' => 'unsuccess', 'status' => 406], 406);
                //}
            }
        } catch (\Exception $e) {
            return response(['message' => 'Error', $e->getMessage()], 500);
        }
    }

    public function generate(Request $request)
    {
        $userOtp = $this->generateOTP($request);
        $userOtp->sendOtp($request);
        return response([
            'data' => $userOtp,
            'message' => 'One time password has been generated.'
        ], 200);
    }

    public function generateOTP(Request $request)
    {
        $user = User::where('mobile_no', $request->mobile_no)->first();
        $userOtp = UserOtp::where('user_id', $user->id)->latest()->first();
        $now = now();
        if ($userOtp && $now->isBefore($userOtp->expire_at)) {
            return $userOtp;
        }
        return UserOtp::create([
            'user_id' => $user->id,
            'otp' => rand(123456, 999999),
            'expire_at' => $now->addMinutes(5),
        ]);
    }

    /**
     *     public function sendOTP(Request $request)
    {
        $sid = 'AC8efc3bf5ef8df10cdf07711914f7e17d';
        $token = env('TWILIO_AUTH_TOKEN');
        $verifySid = 'VA02938b6b00961773943a9c18459fcf46';
        $verifiedNumber = '+94702752116';
        
        $twilio = new Client($sid, $token);

        $verification = $twilio->verify->v2->services($verifySid)
            ->verifications
            ->create($verifiedNumber, "sms");

        return $verification->status;
    }

    public function verifyOTP(Request $request)
    {
        $sid = 'AC8efc3bf5ef8df10cdf07711914f7e17d';
        $token = env('TWILIO_AUTH_TOKEN');
        $verifySid = 'VA02938b6b00961773943a9c18459fcf46';
        $verifiedNumber = '+94702752116';

        $twilio = new Client($sid, $token);

        $otpCode = $request->input('otp_code');

        $verificationCheck = $twilio->verify->v2->services($verifySid)
            ->verificationChecks
            ->create($verifiedNumber, ['code' => $otpCode]);

        return $verificationCheck->status;
    }
     */
}
