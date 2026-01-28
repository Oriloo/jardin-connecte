<?php
include('../variablesBdd.php');
include('../variablesTable.php');

$conn = new mysqli($serveur, $utilisateur, $motDePasse, $nomBdd);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Définir le fuseau horaire sur Paris (UTC+1)
date_default_timezone_set('Europe/Paris');

$date = date("Y-m-d");
$heure = date("H:i:s");
$UserID = $_GET['UserID'];
$selectedTable = $_GET['selectedTable'];

if ($selectedTable == "potager") {
    $Table_Arrosage = "p_arrosage";
} elseif ($selectedTable == "fleurs") {
    $Table_Arrosage = "f_arrosage";
}

$sql = "INSERT INTO $Table_Arrosage (date, time, declencher_par, etat) VALUES ('$date', '$heure', '$UserID', 1)";

if ($conn->query($sql) === TRUE) {
    // echo "Enregistrement réussi!";
} else {
    // echo "Erreur d'enregistrement. " . $conn->error;
}

$conn->close();

// Redirection vers ../../index.php
header("Location: ../../controle/?profil=$selectedTable");
exit(); // Assure que le script s'arrête après la redirection
?>