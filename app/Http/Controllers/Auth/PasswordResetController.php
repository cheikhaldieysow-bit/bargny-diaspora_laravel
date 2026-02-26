<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\ResetPasswordApiMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class PasswordResetController extends Controller
{
    /**
     * Handle the request to send password reset link.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->email;

        // Generate token
        $token = Str::random(60);

        // Store token in password_resets table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Generate reset URL (using a default frontend URL if not configured)
        $frontendUrl = config('app.frontend_url', 'http://localhost:3000');
        $resetUrl = $frontendUrl . '/reset-password?token=' . $token . '&email=' . urlencode($email);

        // For development, we'll log the reset URL instead of sending email
        // This avoids SMTP authentication issues while still allowing testing
        \Log::info('Password reset link generated', [
            'email' => $email,
            'reset_url' => $resetUrl,
            'token' => $token
        ]);

        return response()->json([
            'message' => 'Un email de réinitialisation a été envoyé (en développement, vérifiez les logs)',
            'status' => 'success',
            'debug' => [
                'reset_url' => $resetUrl,
                'token' => $token
            ]
        ]);
    }

    /**
     * Handle the password reset request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get password reset record
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => 'Token de réinitialisation invalide'
            ], 404);
        }

        // Verify token
        if (!Hash::check($request->token, $passwordReset->token)) {
            return response()->json([
                'message' => 'Token de réinitialisation invalide'
            ], 401);
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Mot de passe réinitialisé avec succès',
            'status' => 'success'
        ]);
    }

    /**
     * Verify the password reset token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get password reset record
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            return response()->json([
                'valid' => false,
                'message' => 'Aucun token trouvé pour cet email'
            ]);
        }

        // Verify token
        $isValid = Hash::check($request->token, $passwordReset->token);

        return response()->json([
            'valid' => $isValid,
            'message' => $isValid ? 'Token valide' : 'Token invalide'
        ]);
    }
}