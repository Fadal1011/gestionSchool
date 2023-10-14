<!DOCTYPE html>
<html>
<head>
    <title>Changer le mot de passe</title>
</head>
<body>
    <h1>Changer le mot de passe</h1>
    <p>Bonjour {{ $username }},</p>
    <p>Veuillez changer votre mot de passe par défaut.</p>
    <p>Accédez à la page de modification du mot de passe :</p>
    <a href="http://localhost:4200/changePassword?email={{ $username }}">Modifier le mot de passe</a>
</body>
</html>
