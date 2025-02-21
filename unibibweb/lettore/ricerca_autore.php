<?php

    require_once("../scripts/utils.php");

    // Variabile per contenere il risultato della ricerca
    $result = null;

    $CUR_PAGE = "@lettore.it";
    $fa_icon = "fa-user";
    $title = "Lista Autori";
    $subtitle = "";

    $table_headers = array("Nome", "Cognome", "Profilo");

    $qry = "SELECT _id, _nome, _cognome FROM unibib.get_all_writers();";
    $res = pg_prepare($con, "", $qry);
    $res = pg_execute($con, "", array());

    // Prepariamo i risultati
    $rows = array();
    while ($row = pg_fetch_assoc($res)) {
        array_push($rows,
        array(
            "separator" => "",
            "separator_text" => "",
            "class" => "",
            "cols" => array(
            array("type" => "text", "val" => $row["_nome"]),
            array("type" => "text", "val" => $row["_cognome"]),
            array(
                "type" => "button",
                "target" => "profilo_autore.php?filter=".$row["_id"]."&highlight=false&hide=true",
                "submit" => "Profilo",
                "class" => "is-link",
                "params" => array()
            )
            )
        )
        );
    }

    // Mostra la tabella con i risultati
    require("../components/table.php"); 
    require("../components/head.php"); 
    require("../components/navbar.php");
    require("../components/footer.php"); 
?>