<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.3/css/bulma.min.css">
  <link rel="stylesheet" href="../styles/index.css">
  <script src="https://kit.fontawesome.com/eb793f993c.js" crossorigin="anonymous"></script>
  <title>Home lettore UniBib</title>
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

   <?php

     $qry = "SELECT _cod_fisc FROM unibib.get_reader($1)";
     $res = pg_prepare($con, "", $qry);
     $res = pg_execute($con, "", array($_SESSION["userid"]));

     $c_f = pg_fetch_assoc($res);
     $_SESSION["cod_fisc"] = $c_f["_cod_fisc"];

   ?>

      <div class="block">
         <p class="title is-2 is-link">Buongiorno, <?= $_SESSION["username"]; ?>.</p>
      </div>

      <a href="profilo.php" class="block button is-link">
         <span class="icon is-small"><i class="fa-solid fa-address-card fa-xl"></i></span>
         <strong>Profilo</strong>
      </a><br/>

      <a href="ricerca_libro.php" class="block button is-link">
         <span class="icon is-small"><i class="fa-solid fa-magnifying-glass fa-xl"></i></span>
         <strong>Ricerca Libro</strong>
      </a><br/>

      <a href="ricerca_autore.php" class="block button is-link">
         <span class="icon is-small"><i class="fa-solid fa-magnifying-glass fa-xl"></i></span>
         <strong>Lista Autori</strong>
      </a><br/>

      <a href="archivio_prestiti.php" class="block button is-link">
         <span class="icon is-small"><i class="fa-solid fa-book fa-xl"></i></span>
         <strong>Archivio prestiti</strong>
      </a><br/>

   </div>

   <footer class="footer">
      <div class="content has-text-centered has-text-dark	">
         <p>Built by <a target="_blank" href="https://github.com/Basshuu98"><u>Kevin Softic</u></a>.</p>
      </div>
   </footer>

   </body>
</html>