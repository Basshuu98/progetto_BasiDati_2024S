<?php


    require_once("../scripts/utils.php");
    if (isset($_POST["submit"])) {
        $qry = "CALL unibib.delete_book($1);";
        $res = pg_prepare($con, "", $qry);
        $res = pg_execute($con, "", array($_POST["isbn"]));

        if (!$res) {
            $error = ParseError(pg_last_error());
        }
        else {
            unset($error);
            $_SESSION["feedback"] = "Libro eliminato con successo.";
            Redirect("gestione_libri.php");
        }
    }

 
$CUR_PAGE = "@bibliotecario.it";
$fa_icon="fa-user-graduate";
$title = "Elimina Libro";
$subtitle = "";

$class = "is-danger";
$help = $subtitle;

$inputs = array(
    array(
        "type"=>"hidden",
        "name"=>"isbn",
        "value"=>$_POST["isbn"]
    )
);

require("../components/form.php");
?>