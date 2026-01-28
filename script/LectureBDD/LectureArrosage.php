<?php
// Connexion à la base de données
$connexion = new mysqli($serveur, $utilisateur, $motDePasse, $nomBdd);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}

// Exécuter une requête SQL pour récupérer toutes les données pour la date actuelle
$sql = "SELECT * FROM $Table_Arrosage";
$resultat = $connexion->query($sql);

if ($resultat === false) {
    // Gérer l'erreur de la requête
    die("Error executing SQL query: " . $connexion->error);
}

$date_arrosage = [];
$time_arrosage = [];
$modif_arrosage = [];

// Traiter les résultats de la requête
while ($row = $resultat->fetch_assoc()) {
    $date_arrosage[] = $row['date'];
    $time_arrosage[] = $row['time'];
    $modif_arrosage[] = $row['declencher_par'];
    $etat_arrosage[] = $row['etat'];
}

// Obtenir les dernières valeurs de chaque tableau
$date_arrosage_F = end($date_arrosage);
$time_arrosage_F = end($time_arrosage);
$modif_arrosage_F = end($modif_arrosage);
$etat_arrosage_F = end($etat_arrosage);

$connexion->close();

// Définir la timezone (remplacez 'Europe/Paris' par votre timezone)
date_default_timezone_set('Europe/Paris');

// Date actuelle
$dateActuelle = new DateTime();

// Date et heure de l'arrosage
$arrosageDateTime = new DateTime("$date_arrosage_F $time_arrosage_F");

// Calcul de la différence
$diffADT = $dateActuelle->diff($arrosageDateTime);

// Affichage du résultat avec années, mois, jours, heures et minutes (si non égales à 0)
$resultADT = '';

if ($diffADT->y !== 0) {
    $resultADT .= $diffADT->y . 'a, ';
}

if ($diffADT->m !== 0) {
    $resultADT .= $diffADT->m . 'm, ';
}

if ($diffADT->d !== 0) {
    $resultADT .= $diffADT->d . 'j, ';
}

$resultADT .= $diffADT->h . 'h ' . $diffADT->i;
?>