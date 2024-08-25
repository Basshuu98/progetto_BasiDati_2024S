<?php


require_once("../scripts/utils.php");

$CUR_PAGE="@bibliotecario.it";
$fa_icon="fa-graduation-cap";
$title = "Gestione Autori";
$subtitle = "";


$create = array(
  "target"=>"nuovo_autore.php",
  "text"=>"Aggiungi nuovo autore"
);

$table_headers = array("Nome", "Cognome", "Data Nascita", "Data Morte", array("colspan"=>"2", "text"=>"Controlli"));

$qry = "SELECT _id, _nome, _cognome, _d_nascita, _d_morte, _bio FROM unibib.get_all_writers();";
$res = pg_prepare($con, "", $qry);
$res = pg_execute($con, "", array());

$rows = array();

while($row = pg_fetch_assoc($res)) {  
  $date = new DateTime($row["_d_morte"]);
  $dateStr = $date -> format("Y-m-d");
  array_push($rows,
    array(
       "separator"=>"",
       "separator_text"=>"",
       "class"=>"",
       "cols"=> array(
         array("type"=>"text", "val"=>$row["_nome"]),
         array("type"=>"text", "val"=>$row["_cognome"]),
         array("type"=>"text", "val"=>$row["_d_nascita"]),
         array("type"=>"text", "val"=>$row["_d_morte"]),
         array(
            "type"=>"button",
            "target"=>"modifica_autore.php",
            "submit"=>"Modifica",
            "class"=>"is-link",
            "params"=>array(
               "id"=> $row["_id"],
               "nome"=>$row["_nome"],
               "cognome" => $row["_cognome"],
               "d_nascita" => $row["_d_nascita"],
               "d_morte" => $dateStr,
               "bio" => $row["_bio"],
               "libri" => $row["_libri"],
            )
         ),
         array(
            "type"=>"button",
            "target"=>"elimina_autore.php",
            "submit"=>"Elimina",
            "class"=>"is-danger",
            "params"=>array(
               "id"=> $row["_id"]
            )
         )

        )
    )
  );
}
require("../components/table.php");
?>