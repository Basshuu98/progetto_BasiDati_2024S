<?php

require_once("../scripts/utils.php");

$CUR_PAGE = "@bibliotecario.it";
$fa_icon = "fa-user-graduate";
$title = "Gestione lettori";
$subtitle = "";

$create = array(
  "target"=>"nuovo_lettore.php",
  "text"=>"Crea nuovo lettore"
);

$table_headers = array("Nome", "Cognome", "C. Fiscale", "Email", "Categoria", "N. Prestiti", "N. Ritardi", array("colspan"=>"4", "text"=>"Controlli"));

$qry = "SELECT * FROM unibib.get_all_readers()";
$res = pg_prepare($con, "", $qry);
$res = pg_execute($con, "", array());

$rows = array();

while($row = pg_fetch_assoc($res)) {
  array_push($rows,
    array(
      "class"=>"",
      "cols"=> array(
        array("type"=>"text", "val"=>$row["_nome"]),
        array("type"=>"text", "val"=>$row["_cognome"]),
        array("type"=>"text", "val"=>$row["_cod_fisc"]),
        array("type"=>"text", "val"=>$row["_email"]),
        array("type"=>"text", "val"=>$row["_categoria"]),
        array("type"=>"text", "val"=>$row["_prestiti"]),
        array("type"=>"text", "val"=>$row["_ritardi"]),
        array(
          "type"=>"button",
          "target"=>"gestione_prestiti.php?filter=".$row["_cod_fisc"]."&highlight=false&hide=true",
          "submit"=>"Prestiti",
          "class"=>"is-link",
          "params"=>array()
        ),
        array(
          "type"=>"button",
          "target"=>"modifica_lettore.php",
          "submit"=>"Modifica",
          "class"=>"is-link",
          "params"=>array(
            "nome"=>$row["_nome"],
            "cognome"=>$row["_cognome"],
            "codice_fiscale"=>$row["_cod_fisc"],
            "email"=>$row["_email"],
            "categoria"=>$row["_categoria"],
            "numero_ritardi"=>$row["_ritardi"]
          )
        ),
        array(
          "type"=>"button",
          "target"=>"modifica_password.php",
          "submit"=>"Modifica password",
          "class"=>"is-link",
          "params"=>array(
            "email"=>$row["_email"]
          )
        ),
        array(
          "type"=>"button",
          "target"=>"elimina_lettore.php",
          "submit"=>"Elimina",
          "class"=>"is-danger",
          "params"=>array(
            "codice_fiscale"=>$row["_cod_fisc"]
          )
        )
      )
    )
  );
}

require("../components/table.php");

?>