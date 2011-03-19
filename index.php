<?php
// index.php
// Code by Pauli Suuraho 2011
// Pääsivu

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once 'includes/config.php';
require_once 'includes/database.php';


echo "Trying to connect database..";

$SQL = new Database();

$SQL->Connect();

echo " (done)<br />";

echo "Fetching data sample .. ";

$kysely = $SQL->Query("SELECT * FROM users;");

echo " (done)<br />";

echo "Printing full table .. <br />";
$SQL->PrintTable($kysely);
echo " (done)<br />";


$SQL->Disconnect();
?>