<?php


    require_once("../scripts/utils.php");
    if (isset($_POST["submit"])) {
        $qry = "CALL unibib.delete_copy($1, $2);";
        $res = pg_prepare($con, "", $qry);
        $res = pg_execute($con, "", array($_POST["isbn"], $_POST["id"]));

        if (!$res) {
            $error = ParseError(pg_last_error());
        }
        else {
            unset($error);
            $_SESSION["feedback"] = "Copia eliminata con successo.";
            Redirect("gestione_libri.php");
        }
    }

 
$CUR_PAGE = "@bibliotecario.it";
$fa_icon="fa-user-graduate";
$title = "Elimina copia";
$subtitle = "";

$class = "is-danger";
$help = $subtitle;

$inputs = array(
    array(
        "type"=>"hidden",
        "name"=>"isbn",
        "value"=>$_POST["isbn"]
    ),
    array(
        "type"=>"hidden",
        "name"=>"id",
        "value"=>$_POST["id"]  //09b925de-1520-4477-a6a0-59c6feabf79a
        )
);

require("../components/form.php");
?> 