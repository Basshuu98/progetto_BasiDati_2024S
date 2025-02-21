<?php

require_once("../scripts/utils.php");

$CUR_PAGE = "@lettore.it";
$fa_icon = "fa-user";
$title = "Profilo Autore";
$subtitle = "";

$class = "is-link";
$help = "";




// options query
$qry = "SELECT _isbn, _titolo FROM unibib.get_books_of_writer($1)";
$res = pg_prepare($con, "", $qry);
$res = pg_execute($con, "", array($_GET["filter"]));

$optionsLib = array();

while ($row = pg_fetch_array($res)) {
  $optionsLib[$row["_isbn"]] = $row["_isbn"] . "-" . $row["_titolo"];
}

$qry = "Select * FROM unibib.get_writer($1);";
$res = pg_prepare($con, "", $qry);
$res = pg_execute($con, "", array($_GET["filter"]));

$row = pg_fetch_assoc($res);

require("../components/head.php");
require("../components/navbar.php");


?>
<div class="container is-fullhd box">


    <span class="icon-text mb-4">
        <span class="icon is-large">
            <i class="fa-solid <?= $fa_icon ?> fa-2xl"></i>
        </span>
        <h1 class="title mt-2"><?= $title ?></h1>
    </span>
    <h2 class="subtitle"><?= $subtitle ?></h2>

    <tbody>
        <div class="field">
            <label class="label">Nome</label>
            <input class="input" type="text" value="<?= $row["_nome"] ?>" readonly>
        </div>
        <div class="field">
            <label class="label">Cognome</label>
            <input class="input" type="text" value="<?= $row["_cognome"] ?>" readonly>
        </div>
        <div class="field">
            <label class="label">Data di nascita</label>
            <input class="input" type="date" value="<?= $row["_d_nascita"] ?>" readonly>
        </div>
        <div class="field">
            <label class="label">Data di morte</label>
            <input class="input" type="date" value="<?= $row["_d_morte"] ?>" readonly>
        </div>
        <div class="field">
            <label class="label">Biografia</label>
            <input class="input" type="text" value="<?= $row["_bio"] ?>" readonly>
        </div>
        <label class="label">Libri</label>
        <?php foreach($optionsLib as $lib): ?>
            <div class="field">
                <a href="ricerca_libro.php?filter=<?= explode("-", $lib)[0] ?>" class="is-link">
                    <input class="input is-link" type="text" value="<?= $lib ?>" readonly>
                </a>
            </div>
        <?php endforeach ?>
    </tbody>
</div>