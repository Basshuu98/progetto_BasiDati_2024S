<?php


    require_once("../scripts/utils.php");
    if (isset($_POST["submit"])) {
        $qry = "CALL unibib.delete_reader($1);";
        $res = pg_prepare($con, "", $qry);
        $res = pg_execute($con, "", array($_POST["codice_fiscale"]));

        if (!$res) {
            $error = ParseError(pg_last_error());
        }
        else {
            unset($error);
            $_SESSION["feedback"] = "Lettore eliminato con successo.";
            Redirect("gestione_lettori.php");
        }
    }

 
$CUR_PAGE = "@bibliotecario.it";
$fa_icon="fa-user";
$title = "Elimina lettore";
$subtitle = "";

$class = "is-danger";
$help = "ATTENZIONE: Questa operazione è irreversibile!";

$inputs = array(
    array(
    "type"=>"hidden",
    "name"=>"codice_fiscale",
    "value"=>$_POST["codice_fiscale"]
    )
);

require("../components/form.php");
?> 