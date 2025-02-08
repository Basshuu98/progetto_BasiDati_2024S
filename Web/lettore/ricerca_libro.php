<?php

  require_once("../scripts/utils.php");

  // Variabile per contenere il risultato della ricerca
  $result = null;

  $CUR_PAGE = "@lettore.it";
  $fa_icon = "fa-magnifying-glass";
  $title = "Ricerca Libro";
  $subtitle = "";

  $table_headers = array("ISBN", "Titolo", "Città", "Indirizzo", "Disponibilità", "Richiedi prestito");

  // options query per selezione sede
  $qry = "SELECT _id, _citta, _indirizzo FROM unibib.get_all_libraries()";
  $res = pg_prepare($con, "", $qry);
  $res = pg_execute($con, "", array());

  $optionsLib = array();
  while ($row = pg_fetch_array($res)) {
    $optionsLib[$row["_id"]] = $row["_id"] . "-" . $row["_citta"] . " , " . $row["_indirizzo"];
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Otteniamo l'input dal form
    $searchOption = $_POST["search_option"];
    $searchValue = $_POST["search_value"];
    $searchPlace = $_POST["search_place"];

    if ($searchOption == "isbn") {
      // Ricerca tramite ISBN
      $qry = "SELECT * FROM unibib.get_isbn_book($1, $2)";
      $res = pg_prepare($con, "", $qry);
      $res = pg_execute($con, "", array($searchValue, $searchPlace ? $searchPlace : NULL));

    } else if ($searchOption == "title") {
      // Ricerca tramite titolo
      $qry = "SELECT * FROM unibib.get_title_book($1, $2)";
      $res = pg_prepare($con, "", $qry);
      $res = pg_execute($con, "", array($searchValue, $searchPlace ? $searchPlace : NULL));
    }

    // Verifichiamo se ci sono risultati
    if (pg_num_rows($res) == 0 && $searchPlace != NULL) {
      echo '<div class="container is-max-desktop box">
            <label class="label">
            Nessun risultato trovato per la sede selezionata... Ecco una lista di risultati senza una sede specificata
            </label>
            </div>';

      // Esegui una nuova ricerca senza il filtro della sede
      if ($searchOption == "isbn") {
        $qry = "SELECT * FROM unibib.get_isbn_book($1, $2)";
        $res = pg_prepare($con, "", $qry);
        $res = pg_execute($con, "", array($searchValue, NULL));

      } else if ($searchOption == "title") {
        $qry = "SELECT * FROM unibib.get_title_book($1, $2)";
        $res = pg_prepare($con, "", $qry);
        $res = pg_execute($con, "", array($searchValue, NULL));
      }
    }

    $date = date('Y-m-d');
    $endDate = new DateTime('now');
    $endDate->add(new DateInterval('P1M'));
    $endDate = $endDate->format('Y-m-d');

    // Prepariamo i risultati
    $rows = array();
    while ($row = pg_fetch_assoc($res)) {
      array_push($rows,
        array(
          "separator" => "",
          "separator_text" => "",
          "class" => "",
          "cols" => array(
            array("type" => "text", "val" => $row["__isbn"]),
            array("type" => "text", "val" => $row["__titolo"]),
            array("type" => "text", "val" => $row["_citta"]),
            array("type" => "text", "val" => $row["_indirizzo"]),
            array("type" => "bool", "val" => $row["_disponibilita"]),
            array(
              "type" => "button",
              "target" => "richiesta_prestito.php",
              "submit" => $row["_disponibilita"] == "t" ? "Prestito" : "Non disponibile",
              "class" => $row["_disponibilita"] == "t" ? "is-link" : "is-danger disabled",
              "params" => array(
                "isbn" => $row["__isbn"],
                "copia" => $row["_copia"],
                "cod_fiscale" => $_SESSION["cod_fisc"],
                "data_inizio" => $date,
                "data_fine" => $endDate
              )
            )
          )
        )
      );
    }

    // Mostra la tabella con i risultati
    require("../components/table.php");
  }
?>

<?php 
require("../components/head.php"); 
require("../components/navbar.php");
?>

<div class="container is-max-desktop box">
  <h1 class="title is-3">Cerca un libro</h1>
  
  <!-- Form di ricerca -->
  <form action="ricerca_libro.php" method="POST" class="block">
    <div class="field">
      <label class="label">Cerca per</label>
      <div class="control">
        <div class="select">
          <select name="search_option">
            <option value="isbn">ISBN</option>
            <option value="title">Titolo</option>
          </select>
        </div>
      </div>
    </div>

    <div class="field">
      <label class="label">Valore</label>
      <div class="control">
        <input class="input" type="text" name="search_value" placeholder="Inserisci ISBN o titolo" required>
      </div>
    </div>

    <div class="field">
      <label class="label">Sede</label>
      <div class="control">
        <div class="select">
          <select name="search_place">
            <option value="">Seleziona una sede (opzionale)</option>
            <?php foreach ($optionsLib as $id => $text): ?>
              <option value="<?= $id ?>"><?= $text ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>

    <div class="field">
      <div class="control">
        <button class="button is-link" type="submit">Cerca</button>
      </div>
    </div>
  </form>

</div>

<?php require("../components/footer.php"); ?>