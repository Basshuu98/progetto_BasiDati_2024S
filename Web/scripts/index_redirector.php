<?php
// redirectot for root level files (only index.php)

require_once("utils.php");

if (isset($_SESSION["userid"])) {
  switch ($_SESSION["usertype"]) {
    case "@lettore.it":
      Redirect("lettore/home.php");
    case "@bibliotecario.it":
      Redirect("bibliotecario/home.php");
  }
}

?>