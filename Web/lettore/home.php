<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="stylesheet" href="../styles/index.css">
  <script src="https://kit.fontawesome.com/eb793f993c.js" crossorigin="anonymous"></script>
  <title>Home lettore UniBiblio</title>
</head>
<body class="has-background-dark has-text-light">
   

   <?php 

      require_once("../scripts/utils.php");

      $CUR_PAGE = "@lettore.it";

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

      <a href="ricerca_libro.php" class="block button is-link">
         <span class="icon is-small"><i class="fa-solid fa-user-graduate fa-xl"></i></span>
         <strong>Ricerca Libro</strong>
      </a><br/>

      <a href="gestione_sedi.php" class="block button is-link">
         <span class="icon is-small"><i class="fa-solid fa-user-graduate fa-xl"></i></span>
         <strong>Richiedi prestito</strong>
      </a><br/>

      <a href="gestione_libri.php" class="block button is-link">
         <span class="icon is-small"><i class="fa-solid fa-user-graduate fa-xl"></i></span>
         <strong>Archivio prestiti</strong>
      </a><br/>

   </div>


   </body>
</html>