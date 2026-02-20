<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordApiMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    /**
     * Envoyer l'email de réinitialisation.
     * 
     * @endpoint POST /api/password/forgot
     */
    public function forgotPassword(Request $request)
    {
        // Rate limiting : 5 tentatives max par email toutes les 15 minutes
        $key = 'password-reset:' . $request->email;
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            
            return response()->json([
                'message' => 'Trop de tentatives. Veuillez réessayer dans ' . ceil($seconds / 60) . ' minutes.',
                'success' => false,
            ], 429);
        }

        // Validation
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.exists' => 'Aucun compte n\'est associé à cette adresse email.',
        ]);

        RateLimiter::hit($key, 900); // 15 minutes

        // Supprimer les anciens tokens pour cet email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Créer un nouveau token
        $token = Str::random(60);

        // Stocker le token dans la base de données (hashé pour la sécurité)
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Envoyer l'email avec le token en clair (il sera hashé côté client dans l'URL)
        Mail::to($request->email)->send(new ResetPasswordApiMail($token, $request->email));

        return response()->json([
            'message' => 'Un lien de réinitialisation a été envoyé à votre adresse email.',
            'success' => true,
        ], 200);
    }

    /**
     * Réinitialiser le mot de passe.
     * 
     * @endpoint POST /api/password/reset
     */
    public function resetPassword(Request $request)
    {
        // Validation
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'token.required' => 'Le token est requis.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.exists' => 'Aucun compte n\'est associé à cette adresse email.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        // Vérifier si le token existe
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenData) {
            return response()->json([
                'message' => 'Ce lien de réinitialisation est invalide.',
                'success' => false,
            ], 422);
        }

        // Vérifier si le token n'a pas expiré (24 heures)
        if (now()->diffInHours($tokenData->created_at) > 24) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            
            return response()->json([
                'message' => 'Ce lien de réinitialisation a expiré.',
                'success' => false,
            ], 422);
        }

        // Vérifier le token
        if (!Hash::check($request->token, $tokenData->token)) {
            return response()->json([
                'message' => 'Ce lien de réinitialisation est invalide.',
                'success' => false,
            ], 422);
        }

        // Mettre à jour le mot de passe
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Supprimer le token utilisé
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Révoquer tous les tokens Sanctum existants (déconnexion de tous les appareils)
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Votre mot de passe a été réinitialisé avec succès.',
            'success' => true,
        ], 200);
    }

    /**
     * Vérifier si un token est valide (optionnel).
     * 
     * @endpoint POST /api/password/verify-token
     */
    public function verifyToken(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
        ]);

        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenData) {
            return response()->json([
                'valid' => false,
                'message' => 'Token invalide.',
            ], 200);
        }

        // Vérifier l'expiration
        if (now()->diffInHours($tokenData->created_at) > 24) {
            return response()->json([
                'valid' => false,
                'message' => 'Token expiré.',
            ], 200);
        }

        // Vérifier le token
        if (!Hash::check($request->token, $tokenData->token)) {
            return response()->json([
                'valid' => false,
                'message' => 'Token invalide.',
            ], 200);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Token valide.',
        ], 200);
    }
}
