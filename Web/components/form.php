<?php

/* VARIABLES REQUIRED BY THIS COMPONENT:

$error = "";

$CUR_PAGE = "";
$fa_icon = "";
$title = "";
$subtitle = "";

$class = "";
$help = "";

$inputs = array(
  array(
    type=>"hidden",
    name=>"",
    value=>""
  ),
  array(
    type=>"select",
    multiple=>"multiple",
    ismultiple=>"is-multiple",
    label=>"",
    name=>"",
    options=>array(
      "val"=>"text"
    ),
    options=>array(
      "val"=>"text"
    ),
    onchange=>"",
    icon=>"",
    help=>""
  ),
  array(
    type=>"text",
    label=>"",
    name=>"",
    value=>"",
    placeholder=>"",
    required=>"",
    readonly=>"",
    icon=>"",
    help=>""
  )
)

*/

// authorization
require("../scripts/redirector.php");
  
// head and navbar
require("head.php");
require("navbar.php");

?>

<div class="container is-max-desktop">

  <?php if (isset($error)): ?>
    <div class="notification is-danger is-light mt-6">
      <strong>Errore: </strong><?= $error ?>.
    </div>
  <?php endif ?>

  <form class="box p-6" action="" method="post">

    <span class="icon-text">
      <span class="icon is-large">
        <i class="fa-solid <?= $fa_icon ?> fa-2xl"></i>
      </span>
      <h1 class="title mt-2"><?= $title ?></h1>
    </span>
    <h2 class="subtitle"><?= $subtitle ?></h2>

    <?php foreach ($inputs as $input): ?>
      
      <?php if ($input["type"] == "hidden"): ?>
        <input type="hidden" name="<?= $input["name"] ?>" value="<?= $input["value"] ?>" required readonly>

      <?php elseif ($input["type"] == "select"): ?>
        <div class="field">
          <label class="label mt-5"><?= $input["label"] ?></label>
          <div class="control has-icons-left">
            <div class="select is-fullwidth <?= $input["ismultiple"] ?>">
              <select name="<?= $input["name"] ?>" <?= $input["multiple"] ?> onchange="<?= $input["onchange"] ?>">
                <?php foreach ($input["selected"] as $value=>$text): ?>
                  <option value="<?= $value ?>" selected><?= $text ?></option>
                <?php endforeach ?>
                <?php foreach ($input["options"] as $value=>$text): ?>
                  <option value="<?= $value ?>"><?= $text ?></option>
                <?php endforeach ?>
              </select>
            </div>
            <div class="icon is-small is-left">
              <i class="fa-solid <?= $input["icon"] ?>"></i>
            </div>
          </div>
          <p class="help"><?= $input["help"] ?></p>
        </div>

      <?php else: ?>
        <div class="field">
          <label class="label mt-5"><?= $input["label"] ?></label>
          <p class="control has-icons-left">
            <input
              class="input"
              type="<?= $input["type"] ?>"
              name="<?= $input["name"] ?>"
              value="<?= $input["value"] ?>"
              placeholder="<?= $input["placeholder"] ?>"
              <?= $input["required"] ?>
              <?= $input["readonly"] ?>
            >
            <span class="icon is-small is-left">
              <i class="fa-solid <?= $input["icon"] ?>"></i>
            </span>
          </p>
          <p class="help"><?= $input["help"] ?></p>
        </div>
      <?php endif ?>

    <?php endforeach ?>

    <div class="field mt-5">
      <p class="control">
        <input type="submit" name="submit" value="<?= $title ?>" class="button <?= $class ?> is-fullwidth is-medium">
      </p>
      <p class="help <?= $class ?>"><?= $help ?></p>
    </div>
  
  </form>

</div>
  
<?php require("footer.php"); ?>