<?php

require_once("../scripts/utils.php");

$CUR_PAGE = "@bibliotecario.it";
$fa_icon = "fa-user-graduate";
$title = "Gestione prestiti";
$subtitle = "";

$table_headers = array("Cod. Libro", "C. Fiscale", "Luogo",  "Periodo Ammesso", "Controlli");

$qry = "SELECT * FROM unibib.get_active_rent()";
$res = pg_prepare($con, "", $qry);
$res = pg_execute($con, "", array());

$rows = array();

while($row = pg_fetch_assoc($res)) {
  array_push($rows,
    array(
      "class"=>"",
      "cols"=> array(
        array("type"=>"text", "val"=>$row["_isbn"]." - ".$row["_copia"]),
        array("type"=>"text", "val"=>$row["_cod_fisc"]),
        array("type"=>"text", "val"=>$row["_citta"]." , ".$row["_indirizzo"]),
        array("type"=>"text", "val"=>$row["_d_inizio"]." - ".$row["_d_fine"]),
        array(
          "type"=>"button",
          "target"=>"modifica_prestito.php",
          "submit"=>"Modifica",
          "class"=>"is-link",
          "params"=>array(
            "id"=>$row["_id"],
            "d_fine"=>$row["_d_fine"],
            "d_consegna"=>$row["_d_consegna"]
          )
        )
      )
    )
  );
}

require("../components/table.php");

?>