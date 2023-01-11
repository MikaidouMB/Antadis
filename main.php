<?php
require_once __DIR__ . '/database/database.php';
$authDB = require_once __DIR__ . '/database/security.php';

$currentUser = $authDB->isLoggedin();
var_dump($currentUser);die;
if (!$currentUser) {
	header('Location: /');
}

?>
<!DOCTYPE html>
<?php require_once 'includes/head.php'; ?>

<html>

<body>
	<?php require_once 'includes/navbar.php'; ?>
	<div class="container">
		<h1>Bienvenue <?= htmlspecialchars($currentUser['username'], ENT_QUOTES, 'UTF-8'); ?></h1>
		<p>Cliquez sur la phrase pour la corriger</p>
		<div id="clickblock" class="text-center">
			<a onclick="autoCorrect(); return false;">
				Il y a des fotes dan sete fraz. Cliké ici pour lé corrigés.
			</a>
		</div>
	</div>
</body>

</html>