<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f4f7fa;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7fa; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                    
                    <!-- Header avec gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 50px 30px; text-align: center;">
                            <div style="background-color: rgba(255, 255, 255, 0.2); width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 40px;">🔐</span>
                            </div>
                            <h1 style="color: #ffffff; margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -0.5px;">
                                Réinitialisation de mot de passe
                            </h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 50px 40px;">
                            <p style="color: #1a202c; font-size: 18px; line-height: 1.6; margin-bottom: 24px; font-weight: 500;">
                                Bonjour,
                            </p>

                            <p style="color: #4a5568; font-size: 16px; line-height: 1.7; margin-bottom: 24px;">
                                Nous avons reçu une demande de réinitialisation de mot de passe pour votre compte associé à <strong>{{ $email }}</strong>.
                            </p>

                            <p style="color: #4a5568; font-size: 16px; line-height: 1.7; margin-bottom: 35px;">
                                Pour créer un nouveau mot de passe, cliquez sur le bouton ci-dessous :
                            </p>

                            <!-- Button avec effet hover simulé -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 10px 0 40px 0;">
                                        <a href="{{ $resetUrl }}" 
                                           style="display: inline-block; padding: 18px 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; letter-spacing: 0.3px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); transition: all 0.3s;">
                                            Réinitialiser mon mot de passe
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Info box -->
                            <div style="background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%); border-left: 4px solid #f56565; padding: 20px; border-radius: 6px; margin-bottom: 30px;">
                                <p style="color: #742a2a; font-size: 14px; line-height: 1.6; margin: 0; font-weight: 500;">
                                    ⏰ <strong>Important :</strong> Ce lien expirera dans <strong>24 heures</strong> pour des raisons de sécurité.
                                </p>
                            </div>

                            <p style="color: #718096; font-size: 15px; line-height: 1.6; margin-bottom: 25px;">
                                Si vous n'avez pas demandé cette réinitialisation, vous pouvez ignorer cet email en toute sécurité. Votre mot de passe actuel reste inchangé.
                            </p>

                            <!-- Divider -->
                            <div style="border-top: 1px solid #e2e8f0; margin: 35px 0;"></div>

                            <!-- Alternative Link Section -->
                            <div style="background-color: #f7fafc; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0;">
                                <p style="color: #4a5568; font-size: 13px; line-height: 1.6; margin-bottom: 12px; font-weight: 600;">
                                    🔗 Problème avec le bouton ?
                                </p>
                                <p style="color: #718096; font-size: 12px; line-height: 1.5; margin-bottom: 10px;">
                                    Copiez et collez ce lien dans votre navigateur :
                                </p>
                                <p style="color: #667eea; font-size: 12px; word-break: break-all; margin: 0; font-family: 'Courier New', monospace; background-color: #ffffff; padding: 10px; border-radius: 4px; border: 1px solid #e2e8f0;">
                                    {{ $resetUrl }}
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%); padding: 35px 40px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="color: #4a5568; font-size: 15px; margin: 0 0 15px 0; font-weight: 500;">
                                Cordialement,<br>
                                <strong style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">L'équipe {{ config('app.name') }}</strong>
                            </p>
                            
                            <div style="margin: 20px 0;">
                                <span style="display: inline-block; width: 40px; height: 1px; background-color: #cbd5e0;"></span>
                            </div>
                            
                            <p style="color: #a0aec0; font-size: 12px; margin: 0;">
                                © {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
                            </p>
                            
                            <p style="color: #cbd5e0; font-size: 11px; margin: 15px 0 0 0;">
                                Cet email a été envoyé automatiquement. Veuillez ne pas y répondre.
                            </p>
                        </td>
                    </tr>

                </table>
                
                <!-- Extra footer -->
                <table width="600" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
                    <tr>
                        <td align="center" style="padding: 0 20px;">
                            <p style="color: #a0aec0; font-size: 11px; line-height: 1.5; margin: 0;">
                                Vous recevez cet email car une réinitialisation de mot de passe a été demandée pour votre compte.<br>
                                Si vous rencontrez des difficultés, contactez notre support.
                            </p>
                        </td>
                    </tr>
                </table>
                
            </td>
        </tr>
    </table>
</body>
</html>
