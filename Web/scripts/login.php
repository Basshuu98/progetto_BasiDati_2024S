<?php

require_once("utils.php");

if (isset($_SESSION["userid"])) {
  require("redirector.php");
}

$email = $_POST["email"] . $_POST["type"];
$type = $_POST["type"];
$password = $_POST["password"];


$qry = "SELECT _id FROM unibib.login($1, $2)";
$res = pg_prepare($con, "", $qry);
$res = pg_execute($con, "", array($email, $password));


$row = pg_fetch_assoc($res);


if (is_null($row["_id"])) {
  $_SESSION["feedback"] = "Email o password errati.";
  Redirect("../index.php");
}

//echo "PASWORD CORRETTa"; 

$_SESSION["userid"] = $row["_id"];
$_SESSION["usertype"] = $type;
$_SESSION["username"] = ucfirst(explode(".", $_POST["email"])[0]);

//echo $_SESSION["userid"];
//echo $_SESSION["usertype"];
//echo $_SESSION["username"];

require("redirector.php");

?>
