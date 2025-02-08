<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.3/css/bulma.min.css">
  <link rel="stylesheet" href="../styles/index.css">
  <script src="https://kit.fontawesome.com/eb793f993c.js" crossorigin="anonymous"></script>
  <title>Home bibliotecario UniBiblio</title>
</head>
<body class="has-background-dark has-text-light">
   

   <?php 

      require_once("../scripts/utils.php");

      $CUR_PAGE = "@bibliotecario.it";

      require("../scripts/redirector.php");
      require("../components/navbar.php");
   ?>
   
   <div class="container is-max-desktop box">
      
      <?php if (isset($_SESSION["feedback"])): ?>
       <div class="notification is-success is-light mt-6">
         <strong><?= $_SESSION["feedback"]; unset($_SESSION["feedback"]) ?></strong>
       </div>
      <?php endif; ?>

      <div class="block">
         <p class="title is-2 is-link">Buongiorno, <?= $_SESSION["username"]; ?>.</p>
      </div>

      <a href="profilo.php" class="block button is-link">
         <span class="icon is-small"><i class="fa-solid fa-address-card fa-xl"></i></span>
         <strong>Profilo</strong>
      </a><br/>

      <a href="gestione_lettori.php" class="block button is-link">
         <span class="icon is-small"><i class="fa-solid fa-user fa-xl"></i></span>
         <strong>Gestione lettori</strong>
      </a><br/>

      <a href="gestione_sedi.php" class="block button is-link">
         <span class="icon is-small"><i class="fa-solid fa-building fa-xl"></i></span>
         <strong>Gestione sedi</strong>
      </a><br/>

      <a href="gestione_libri.php" class="block button is-link">
         <span class="icon is-small"><i class="fa-solid fa-book fa-xl"></i></span>
         <strong>Gestione libri</strong>
      </a><br/>

      <a href="gestione_autori.php" class="block button is-link">
         <span class="icon is-small"><i class="fa-solid fa-user fa-xl"></i></span>
         <strong>Gestione autori</strong>
      </a><br/>

   </div>

   <footer class="footer">
      <div class="content has-text-centered has-text-dark	">
         <p>Built by <a target="_blank" href="https://github.com/Basshuu98"><u>Kevin Softic</u></a>.</p>
      </div>
   </footer>

   </body>
</html>