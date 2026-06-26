<?php

namespace App\Http\Controllers\Auth\Vendor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
  private function cacheKey(string $email): string
  {
    return 'vendor_password_reset_' . sha1(strtolower(trim($email)));
  }

  private function findVendorUser(string $email): ?User
  {
    return User::query()
      ->where('email', strtolower(trim($email)))
      ->where('role', 'vendor')
      ->with('vendor')
      ->first();
  }

  public function requestOtp(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
    ]);

    $user = $this->findVendorUser($request->email);

    if (!$user) {
      return response()->json([
        'message' => 'Vendor not found.'
      ], 404);
    }

    $otp = (string) random_int(100000, 999999);
    $cacheKey = $this->cacheKey($user->email);

    Cache::put($cacheKey, [
      'otp_hash' => Hash::make($otp),
      'verified' => false,
    ], now()->addMinutes(10));

    Mail::raw("Kode OTP reset password Anda adalah: {$otp}\nBerlaku selama 10 menit.", function ($message) use ($user) {
      $message->to($user->email)
        ->subject('OTP Reset Password Vendor');
    });

    return response()->json([
      'message' => 'OTP has been sent to your email.',
      'data' => [
        'email' => $user->email,
        'expires_in_minutes' => 10,
      ],
    ]);
  }

  public function verifyOtp(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'otp' => 'required|string|min:6|max:6',
    ]);

    $user = $this->findVendorUser($request->email);

    if (!$user) {
      return response()->json([
        'message' => 'Vendor not found.'
      ], 404);
    }

    $cacheKey = $this->cacheKey($user->email);
    $payload = Cache::get($cacheKey);

    if (!$payload) {
      return response()->json([
        'message' => 'OTP has expired. Please request a new one.'
      ], 422);
    }

    if (!Hash::check($request->otp, $payload['otp_hash'] ?? '')) {
      return response()->json([
        'message' => 'Invalid OTP.'
      ], 422);
    }

    $payload['verified'] = true;

    Cache::put($cacheKey, $payload, now()->addMinutes(10));

    return response()->json([
      'message' => 'OTP verified successfully.'
    ]);
  }

  public function reset(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'otp' => 'required|string|min:6|max:6',
      'password' => 'required|string|min:8|confirmed',
    ]);

    $user = $this->findVendorUser($request->email);

    if (!$user) {
      return response()->json([
        'message' => 'Vendor not found.'
      ], 404);
    }

    $cacheKey = $this->cacheKey($user->email);
    $payload = Cache::get($cacheKey);

    if (!$payload) {
      return response()->json([
        'message' => 'OTP has expired. Please request a new one.'
      ], 422);
    }

    if (!Hash::check($request->otp, $payload['otp_hash'] ?? '')) {
      return response()->json([
        'message' => 'Invalid OTP.'
      ], 422);
    }

    if (empty($payload['verified'])) {
      return response()->json([
        'message' => 'OTP must be verified first.'
      ], 422);
    }

    $user->update([
      'password' => Hash::make($request->password),
    ]);

    $user->tokens()->delete();
    Cache::forget($cacheKey);

    return response()->json([
      'message' => 'Password has been reset successfully.'
    ]);
  }
}
