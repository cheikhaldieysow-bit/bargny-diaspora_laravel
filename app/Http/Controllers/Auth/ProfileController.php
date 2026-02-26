<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\EmailVerificationMail;
use App\Mail\PhoneVerificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile
     */
    public function getProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json([
            'message' => 'Profil utilisateur',
            'data' => $user
        ]);
    }

    /**
     * Update user's name
     */
    public function updateName(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $user->name = $request->name;
        $user->save();

        return response()->json([
            'message' => 'Nom mis à jour avec succès',
            'data' => $user
        ]);
    }

    /**
     * Initiate email change process
     */
    public function initiateEmailChange(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'new_email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $newEmail = $request->new_email;

        // Generate verification code (5 alphanumeric characters)
        $verificationCode = Str::random(5);

        // Store verification code with expiration (5 minutes)
        DB::table('email_verifications')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'new_email' => $newEmail,
                'verification_code' => $verificationCode,
                'expires_at' => now()->addMinutes(5),
                'created_at' => now()
            ]
        );

        // Send verification email
        try {
            Mail::to($user->email)->send(new EmailVerificationMail($newEmail, $verificationCode));

            return response()->json([
                'message' => 'Code de vérification envoyé à votre email actuel',
                'debug' => [
                    'verification_code' => $verificationCode,
                    'new_email' => $newEmail
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Échec de l\'envoi du code de vérification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify email change
     */
    public function verifyEmailChange(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|string|size:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $verificationCode = $request->verification_code;

        // Get verification record
        $verification = DB::table('email_verifications')
            ->where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return response()->json([
                'message' => 'Code de vérification invalide ou expiré'
            ], 404);
        }

        if ($verification->verification_code !== $verificationCode) {
            return response()->json([
                'message' => 'Code de vérification incorrect'
            ], 401);
        }

        // Update user email
        $user->email = $verification->new_email;
        $user->save();

        // Delete verification record
        DB::table('email_verifications')->where('user_id', $user->id)->delete();

        return response()->json([
            'message' => 'Email mis à jour avec succès',
            'data' => $user
        ]);
    }

    /**
     * Initiate phone number change process
     */
    public function initiatePhoneChange(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'new_phone' => 'required|string|unique:users,phone',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $newPhone = $request->new_phone;

        // Generate verification code (5 alphanumeric characters)
        $verificationCode = Str::random(5);

        // Store verification code with expiration (5 minutes)
        DB::table('phone_verifications')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'new_phone' => $newPhone,
                'verification_code' => $verificationCode,
                'expires_at' => now()->addMinutes(5),
                'created_at' => now()
            ]
        );

        // Send verification email
        try {
            Mail::to($user->email)->send(new PhoneVerificationMail($newPhone, $verificationCode));

            return response()->json([
                'message' => 'Code de vérification envoyé à votre email actuel',
                'debug' => [
                    'verification_code' => $verificationCode,
                    'new_phone' => $newPhone
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Échec de l\'envoi du code de vérification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify phone number change
     */
    public function verifyPhoneChange(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|string|size:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $verificationCode = $request->verification_code;

        // Get verification record
        $verification = DB::table('phone_verifications')
            ->where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return response()->json([
                'message' => 'Code de vérification invalide ou expiré'
            ], 404);
        }

        if ($verification->verification_code !== $verificationCode) {
            return response()->json([
                'message' => 'Code de vérification incorrect'
            ], 401);
        }

        // Update user phone
        $user->phone = $verification->new_phone;
        $user->save();

        // Delete verification record
        DB::table('phone_verifications')->where('user_id', $user->id)->delete();

        return response()->json([
            'message' => 'Numéro de téléphone mis à jour avec succès',
            'data' => $user
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Mot de passe actuel incorrect'
            ], 401);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Mot de passe mis à jour avec succès'
        ]);
    }

    /**
     * Delete user account
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Mot de passe incorrect'
            ], 401);
        }

        // Delete user account
        $user->delete();

        return response()->json([
            'message' => 'Compte supprimé avec succès'
        ]);
    }
}