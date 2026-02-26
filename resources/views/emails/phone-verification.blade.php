<!DOCTYPE html>
<html>
<head>
    <title>Code de vérification pour changement de numéro de téléphone</title>
</head>
<body>
    <h2>Code de vérification pour changement de numéro de téléphone</h2>
    <p>Bonjour,</p>
    <p>Vous avez demandé à changer votre numéro de téléphone pour {{ $newPhone }}.</p>
    <p>Votre code de vérification est : <strong>{{ $verificationCode }}</strong></p>
    <p>Ce code expire dans 5 minutes.</p>
    <p>Si vous n'avez pas demandé ce changement, veuillez ignorer cet email.</p>
    <p>Cordialement,<br>{{ config('app.name') }}</p>
</body>
</html>