<!DOCTYPE html>
<html lang="en">
   <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="css/reset.css">
     <link rel="stylesheet" href="css/main.css">
     <title>Profilo lettore - UniBiblio</title>
   </head>
   <body>
    <?php

    require_once("../scripts/utils.php");

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

    </div>

</body>
</html>