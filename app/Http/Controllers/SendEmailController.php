<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendEmailController extends Controller
{
    public function sendmail(Request $request)
    {
        $users = User::get();
        $mail_title = $request->mailTitle;
        $mail_text = $request->mailText;
        foreach($users as $user) {
            $name = $user->name;
            Mail::to($user)
            ->send(new SendMail($mail_title, $mail_text, $name));
        }
        return response()->json([
            'message' => 'メール送信に成功しました'
        ], 200);
    }
}
