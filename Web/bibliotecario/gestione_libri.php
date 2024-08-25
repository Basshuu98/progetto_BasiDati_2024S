<?php


require_once("../scripts/utils.php");

$CUR_PAGE="@bibliotecario.it";
$fa_icon="fa-graduation-cap";
$title = "Gestione Libri";
$subtitle = "";


$create = array(
  "target"=>"nuovo_libro.php",
  "text"=>"Aggiungi nuovo libro"
);

$create2 = array(
  "target"=> "nuova_copia.php",
  "text"=> "Aggiungi nuova copia"
);

$table_headers = array("ISBN", "Id", "Titolo", "Città", "Indirizzo", "Disponibilità", array("colspan"=>"4", "text"=>"Controlli"));

$qry = "SELECT _isbn, _copia, __titolo, _trama, _citta, _indirizzo, _disponibilita, _autori FROM unibib.get_all_books();";
$res = pg_prepare($con, "", $qry);
$res = pg_execute($con, "", array());

$rows = array();

while($row = pg_fetch_assoc($res)) {
  array_push($rows,
    array(
       "separator"=>"",
       "separator_text"=>"",
       "class"=>"",
       "cols"=> array(
         array("type"=>"text", "val"=>$row["_isbn"]),
         array("type"=>"text", "val"=>$row["_copia"]),
         array("type"=>"text", "val"=>$row["__titolo"]),
         array("type"=>"text", "val"=>$row["_citta"]),
         array("type"=>"text", "val"=>$row["_indirizzo"]),
         array("type"=>"text", "val"=>$row["_disponibilita"]),
         array(
            "type"=>"button",
            "target"=>"modifica_libro.php",
            "submit"=>"Modifica",
            "class"=>"is-link",
            "params"=>array(
               "isbn"=> $row["_isbn"],
               "titolo"=>$row["_titolo"],
               "trama"=>$row["_trama"],
               "autori"=>$row["_autori"]
            )
         ),
         array(
            "type"=>"button",
            "target"=>"modifica_copia.php",
            "submit"=>"Modifica Sede",
            "class"=>"is-link",
            "params"=>array(
               "isbn"=> $row["_isbn"],
               "id"=>$row["_copia"],
               "sede"=>$row["_sede"]
            )
         ),
         array(
            "type"=>"button",
            "target"=>"elimina_copia.php",
            "submit"=>"Elimina Copia",
            "class"=>"is-danger",
            "params"=>array(
               "isbn"=> $row["_isbn"],
               "id"=> $row["_copia"]
            )
         ),
         array(
            "type"=>"button",
            "target"=>"elimina_libro.php",
            "submit"=>"Elimina libro",
            "class"=>"is-danger",
            "params"=>array(
               "isbn"=> $row["_isbn"]
            )
         )
        )
    )
  );
}
require("../components/table.php");