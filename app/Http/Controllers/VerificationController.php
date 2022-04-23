<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
  // use VerifiesEmails;

  public function __construct()
  {
    $this->middleware('throttle:6,1');
  }

  public function verify(Request $request)
  {
    $user = User::find($request->route('id'));
    if (!$user->email_verified_at) {
      $user->markEmailAsVerified();
      event(new Verified($user));
      return new JsonResponse('Email Verified');
    }
    return new JsonResponse('Email Verify Failed');
  }

  public function resend(Request $request)
  {
    $user = User::where('email', $request->email)->first();
    if (!$user) {
      return new JsonResponse('メールアドレスが登録されていません');
    }
    if ($user->hasVerifiedEmail()) {
      return new JsonResponse('メール認証済みです');
    }

    $user->sendEmailVerificationNotification();

    return new JsonResponse('認証メールを再送信しました');
  }
}
