<?php
require_once __DIR__ . '/database/database.php';
$authDB = require_once './database/security.php';


/*******************************************************************************
Le sujet est assez basique :

- Page de connexion
- Lors d'une connexion réussie, la date de dernière connexion est mise à jour et
on est redirigé sur la page principale si le mot de passe dans la base
correspond au mot de passe entré et si l'utilisateur fait partie du groupe 2.
Si l'authentification échoue, on retourne sur la page de connexion et un message
d'erreur s'affiche.
- Une fois connecté, une phrase mal orthographiée est affichée. Cliquer dessus la
corrige.
- On peut ensuite se déconnecter, on est alors redirigé vers la page de connexion.

Tu es libre de faire le test à ta manière le but étant de nous montrer ce que tu sais faire

Informations de connexion à la DB

Host : localhost
Login : mikaidoumbo_lVh35
Password : ck2jCWN4i8g7
Db name : mikaidoumbo_lVh35

 *******************************************************************************/
const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_PROFIL_NOT_EXISTING = "L'utilisateur et/ou le mot de passe est incorrect";
const ERROR_PASSWORD_MISMATCH = 'Le mot de passe n\'est pas valide';
const ERROR_EMAIL_INVALID = 'L\'email n\'est pas valide';
const ERROR_EMAIL_UNKOWN = 'L\'email n\'est pas enregistrée';
const ERROR_PASSWORD_TOO_SHORT = 'Le mot de passe est trop court';
const ERROR_GROUP_MEMBERSHIP = 'Vous ne faites pas parti du groupe 2, vous ne pourrez pas acceder à la phrase mal orthographiée';
$errors = [
    'email' => '',
    'password' => '',
    'group' => ''
];

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = filter_input_array(INPUT_POST, [
        'email' => FILTER_SANITIZE_EMAIL,
    ]);
    $email = $input['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = $authDB->getUserFromEmail($email) ?? '';

    if ($email === FALSE) {
        $errors['email'] = ERROR_REQUIRED;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = ERROR_EMAIL_INVALID;
    } else if ($user === false && $email != '') {
        $errors['email'] = ERROR_EMAIL_UNKOWN;
    }

    if ($password === FALSE) {
        $errors['password'] = ERROR_REQUIRED;
    } else if (mb_strlen($password) < 3) {
        $errors['password'] = ERROR_PASSWORD_TOO_SHORT;
    }

    if ($user) {
        $isMember = $authDB->userIsInGroup($user['id_user'], 2) ?? '';
        if ($isMember === FALSE) {
            $errors['group'] = ERROR_GROUP_MEMBERSHIP;
        }

        if (!password_verify($password, $user['password'])) {
            $errors['password'] = ERROR_PASSWORD_MISMATCH;
        }
    }

    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
        //var_dump($user);die;
        $authDB->login($user['id_user']);
        $authDB->setLastLogin($user['id_user']);
        header('Location: main.php');
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php require_once 'includes/head.php'; ?>

<body>
    <?php require_once 'includes/navbar.php'; ?>
    <div class="container-sm w-25 mt-5 px-4">
        <h1>Identification</h1>
        <form method="post" action="index.php">
            <div class="mb-3">
                <label for="email" class="form-label">Email / login</label>
                <input type="text" class="form-control" name="email" id="email" placeholder="Email / login" />
                <?php if ($errors['email']) : ?>
                    <p class="text-danger"><?= $errors['email'] ?></p>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="mot de passe" />
                <?php if ($errors['password']) : ?>
                    <p class="text-danger"><?= $errors['password'] ?></p>
                <?php endif; ?>
            </div>
            <input type="submit" class="btn btn-primary" id="submit" value="Connexion" />
            <?php if ($errors['group']) : ?>
                <p class="text-danger"><?= $errors['group'] ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>