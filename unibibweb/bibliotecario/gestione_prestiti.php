<?php

require_once("../scripts/utils.php");

$CUR_PAGE = "@bibliotecario.it";
$fa_icon = "fa-book";
$title = "Gestione prestiti";
$subtitle = "";

$table_headers = array("Cod. Libro", "C. Fiscale",  "Periodo Ammesso", array("colspan"=>"2", "text"=>"Controlli"));
$cod_fisc = $_GET["filter"];

$qry = "SELECT * FROM unibib.get_active_rent_reader($1)";
$res = pg_prepare($con, "", $qry);
$res = pg_execute($con, "", array($cod_fisc));

$rows = array();

while($row = pg_fetch_assoc($res)) {
  array_push($rows,
    array(
      "class"=>"",
      "cols"=> array(
        array("type"=>"text", "val"=>$row["_isbn"]),
        array("type"=>"text", "val"=>$row["_c_fisc"]),
        array("type"=>"text", "val"=>$row["_d_inizio"]." - ".$row["_d_fine"]),
        array(
          "type"=>"button",
          "target"=>"modifica_prestito.php",
          "submit"=>"Modifica",
          "class"=>"is-link",
          "params"=>array(
            "id"=>$row["_id"]
          )
        ),
        array(
          "type"=>"button",
          "target"=>"consegna_libro.php",
          "submit"=>"Consegna",
          "class"=>"is-success",
          "params"=>array(
            "id"=>$row["_id"],
            "d_fine"=>$row["_d_fine"]
          )
        )
      )
    )
  );
}

require("../components/table.php");

?>