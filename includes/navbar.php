<?php
$currentUser = $currentUser ?? false;
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <?php if ($currentUser) : ?>
        <li class="nav-item active">
          <a class="nav-link" href="logout.php">Deconnexion</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="main.php">Phrase mal orthographiée</a>
        </li>
      <?php else : ?>
        <?php if (isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME'] === '/index.php') : ?>
          <li class="nav-item active">
            <a class="nav-link" href="register.php">Inscription</a>
          </li>
        <?php endif; ?>
        <?php if (isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME'] === '/register.php') : ?>
          <li class="nav-item active">
            <a class="nav-link" href="index.php">Connexion</a>
          </li>
        <?php endif; ?>
      <?php endif; ?>
    </ul>
  </div>
</nav>