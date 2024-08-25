<?php

/* VARIABLES REQUIRED BY THIS COMPONENT:

$CUR_PAGE = "";
$fa_icon = "";
$title = "";
$subtitle = "";

$create = array(
  "target"=>"",
  "text"=>""
)

$table_headers = array("", "", array(colspan=>"", text=>""), ...);

$rows = array(
  array(
    "separator"=>"",
    "separator_text"=>"",
    "class"=>"",
    "cols"=> array(
      array("type"=>"text", "val"=>""),
      array("type"=>"button", "target"=>"", "submit"=>"", "class"=>"", "params"=>array())
    ) 
  )
);

*/

// authorization
require("../scripts/redirector.php");
  
// head and navbar
require("head.php");
require("navbar.php");

require("filter.php");

?>

<div class="container is-fullhd box">

  <span class="icon-text mb-4">
    <span class="icon is-large">
      <i class="fa-solid <?= $fa_icon ?> fa-2xl"></i>
    </span>
    <h1 class="title mt-2"><?= $title ?></h1>
  </span>
  <h2 class="subtitle"><?= $subtitle ?></h2>

  <?php if (isset($create)): ?>
    <a href="<?= $create["target"] ?>" class="block button is-link is-outlined ">
      <span class="icon is-small"><i class="fa-solid <?= $fa_icon ?> fa-xl"></i></span>
      <span class="icon is-small"><i class="fa-regular fa-square-plus"></i></span>
      <strong><?= $create["text"] ?></strong>
    </a>
  <?php endif ?>

  <?php if (isset($create2)): ?>
    <a href="<?= $create2["target"] ?>" class="block button is-link is-outlined ">
      <span class="icon is-small"><i class="fa-solid <?= $fa_icon ?> fa-xl"></i></span>
      <span class="icon is-small"><i class="fa-regular fa-square-plus"></i></span>
      <strong><?= $create2["text"] ?></strong>
    </a>
  <?php endif ?>

  <table class="table is-fullwidth is-hoverable has-text-centered">
    <thead>
      <tr>
        <?php foreach($table_headers as $header): ?>
          <?php if (is_array($header)): // table header with colspan ?>
            <th class="has-text-centered" colspan="<?= $header["colspan"] ?>">
              <?= $header["text"] ?>
            </th>
          <?php else: // table header without colspan ?>
            <th class="has-text-centered"><?= $header ?></th>
          <?php endif ?>
        <?php endforeach ?>
      </tr>
    </thead>

    <?php if (count($rows) == 0): ?>
      <tfoot>
        <tr>
          <td colspan="100" class="has-text-centered invalid">
            <strong>Nessun risultato trovato</strong>
          </td>
        </tr>
      </tfoot>
    <?php endif ?>

    <tbody>
      <?php foreach($rows as $row): ?>

        <?php if ($row["separator"] != $cur_separator): // separator tr ?>
          <?php $cur_separator = $row["separator"] ?>
          <tr class="invalid">
            <td colspan="100" class="has-text-centered has-text-weight-bold">
              <?= $row["separator_text"] ?>
            </td>
          </tr>
        <?php endif ?>

        <tr class="<?= $row["class"] ?>">
          <?php foreach($row["cols"] as $td): ?>

            <?php if ($td["type"] == "text"): // text td ?>
              <td><?= $td["val"] ?></td>
            <?php endif ?>

            <?php if ($td["type"] == "button"): // form with link td ?>
              <td>
                <form method="POST" action="<?= $td["target"] ?>">
                  <?php foreach($td["params"] as $name=>$param): ?>
                    <input type="hidden" name="<?= $name ?>" value="<?= $param ?>" readonly>
                  <?php endforeach ?>
                  <button class="button <?= $td["class"] ?> is-small" type="submit" required>
                    <?= $td["submit"] ?>  
                  </button>
                </form>
              </td>
            <?php endif ?>

          <?php endforeach ?>
        </tr>

      <?php endforeach ?>
    </tbody>

  </table>

</div>
  
<?php require("footer.php"); ?>