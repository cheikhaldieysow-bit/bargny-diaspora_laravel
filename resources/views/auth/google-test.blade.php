<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Test Google Login</title>

    <!-- Google Identity Services -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>

<h2>Connexion avec Google (TEST)</h2>

<div id="g_id_onload"
     data-client_id="{{ env('GOOGLE_CLIENT_ID') }}"
     data-callback="handleGoogleLogin">
</div>

<div class="g_id_signin"
     data-type="standard">
</div>

<script>
function handleGoogleLogin(response) {
    console.log("ID TOKEN :", response.credential);

    fetch('/api/auth/google/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            id_token: response.credential
        })
    })
    .then(res => res.json())
    .then(data => {
        console.log('Réponse API :', data);
        alert('Connexion réussie');
    })
    .catch(err => {
        console.error(err);
        alert('Erreur');
    });
}
</script>

</body>
</html>
