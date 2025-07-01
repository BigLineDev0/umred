<h2>Bienvenue {{ $user->prenom }} {{ $user->name }},</h2>

<p>Un compte vient d’être créé pour vous sur la plateforme UMRED.</p>

<p>Voici vos identifiants :</p>
<ul>
    <li><strong>Email :</strong> {{ $user->email }}</li>
    <li><strong>Mot de passe :</strong> {{ $plainPassword }}</li>
</ul>

<p>Nous vous recommandons de changer votre mot de passe après votre première connexion.</p>

<p>Bien cordialement,<br>L’équipe UMRED</p>
