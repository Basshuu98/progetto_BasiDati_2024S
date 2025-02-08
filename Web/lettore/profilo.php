<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.3/css/bulma.min.css">
  <link rel="stylesheet" href="../styles/index.css">
  <script src="https://kit.fontawesome.com/eb793f993c.js" crossorigin="anonymous"></script>
  <title><?= $title ?> - SuperUnimia</title>
</head>
<body class="has-background-dark has-text-light">
    <?php

    require_once("../scripts/utils.php");

    require("../components/navbar.php");
    // missing login: redirect to login page
    if (!isset($_SESSION["userid"])) {
    Redirect("index.php");
    }

    ?>

    <div class="container is-max-widescreen box">

        <h1 class="title"><i class="fa-solid fa-address-card"></i> Il tuo profilo:</h1>
    
        <?php

        $qry = "SELECT * FROM unibib.get_reader($1)";
        $res = pg_prepare($con, "", $qry);
        $res = pg_execute($con, "", array($_SESSION["userid"]));

        $row = pg_fetch_assoc($res);

        foreach ($row as $key => $value): ?>

        <label class="label mt-5"><?= ucfirst(str_replace("_", " ", substr($key, 1))) ?>:</label>
        <input class="input" type="text" value="<?= $value ?>" readonly>

        <?php endforeach; ?>

        <a href="modifica_password.php" class="block button is-link is-outlined is-fullwidth">
          <span class="icon is-small"><i class="fa-solid fa-lock fa-xl"></i></span>
          <strong>Modifica password</strong>
        </a>

    </div>

    <footer class="footer">
      <div class="content has-text-centered has-text-dark	">
        <p>Built by <a target="_blank" href="https://github.com/Basshuu98"><u>Kevin Softic</u></a>.</p>  
      </div>
    </footer>
</body>
</html>