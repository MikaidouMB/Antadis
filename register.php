<?php
require_once(__DIR__.'/database/database.php');
$authDB = require_once './database/security.php';

const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_username_TOO_SHORT = 'Le pseudo doit faire au moins 3 caractères';
const ERROR_PSEUDO_MISMATCH = 'Ce pseudo existe déja';
const ERROR_PASSWORD_TOO_SHORT = 'Le mot de passe doit faire au moins 3 caractères';
const ERROR_PASSWORD_MISMATCH = 'Le mot de passe n\'est pas valide';
const ERROR_EMAIL_INVALID = 'L\'email n\'est pas valide';

$errors = [
    'username' => '',
    'email' => '',
    'password' => '',
    'confirmpassword' => '',

];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = filter_input_array(INPUT_POST, [
        'username' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'email' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    ]);

    $username = $input['username'] ?? '';
    $email = $input['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmpassword = $_POST['confirmpassword'] ?? '';

    if (!$username) {
        $errors['username'] = ERROR_REQUIRED;
    } elseif (mb_strlen($username) < 3) {
        $errors['username'] = ERROR_username_TOO_SHORT;
    }       
    
    if (!$email) {
        $errors['email'] = ERROR_REQUIRED;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = ERROR_EMAIL_INVALID;
    }
    
    if (!$password) {
        $errors['password'] = ERROR_REQUIRED;
    } elseif (mb_strlen($password) < 3) {
        $errors['password'] = ERROR_PASSWORD_TOO_SHORT;
    }       

    if (!$confirmpassword) {
        $errors['confirmpassword'] = ERROR_REQUIRED;
    } elseif ($confirmpassword !== $password ) {
        $errors['confirmpassword'] = ERROR_PASSWORD_MISMATCH;
    }       

    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
       $authDB->register([
        'username' => $username,
        'email' => $email,
        'password' => $password
       ]);
       $userId = $pdo->lastInsertId();

        header('Location: main.php');
    }
  }
?>
<!DOCTYPE html>
<html lang="fr">
  <?php  require_once 'includes/head.php'; ?>
	<body>
    <?php  require_once 'includes/navbar.php'; ?>
		<div class="container-sm w-25 mt-5 px-4">
			<h1>Inscription</h1>
			<form method="post" action="register.php">
            <div class="mb-3">
					<label for="username" class="form-label">Pseudo </label>
					<input type="text" class="form-control" name="username" id="username" placeholder="Identifiant"
                      />
                    <?php if ($errors['username']) : ?>
                        <p class="text-danger"><?= $errors['username'] ?></p>
                    <?php endif; ?>
  				<div class="mb-3">
					<label for="email" class="form-label">Email</label>
					<input type="text" class="form-control" name="email" id="email" placeholder="Email / login"
                    />
                    <?php if ($errors['email']) : ?>
                        <p class="text-danger"><?= $errors['email'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="mot de passe"/>
                    <?php if ($errors['password']) : ?>
                        <p class="text-danger"><?= $errors['password'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="confirmpassword" class="form-label">Confirmation mot de passe</label>
                        <input type="password" class="form-control" name="confirmpassword" id="confirmpassword">
                        <?php if ($errors['confirmpassword']) : ?>
                            <p class="text-danger"><?= $errors['confirmpassword'] ?></p>
                            <?php endif; ?>
                        </div>
                        <button class="btn btn-primary" type="submit">Valider</button>
                    </div>
                </form>
            </div>
		</div>
	</body>
</html>

