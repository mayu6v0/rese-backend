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
        if($request->mailTo == 'user') {
            $mail_to = User::get();
        };
        if($request->mailTo == 'owner') {
            $mail_to = User::where('authority', 'owner')->get();
        };
        if($request->mailTo == 'admin') {
            $mail_to = User::where('authority', 'admin')->get();
        };
        $mail_title = $request->mailTitle;
        $mail_text = $request->mailText;
        foreach($mail_to as $to) {
            $name = $to->name;
            Mail::to($to)
            ->send(new SendMail($mail_title, $mail_text, $name));
        }
        return response()->json([
            'message' => 'メール送信に成功しました'
        ], 200);
    }
}
