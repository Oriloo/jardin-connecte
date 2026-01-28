<?php
include('../connexion/test-connexion.php')
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Données en direct - Jardin Connecté</title>
	<link rel="icon" href="../image/svg/logo-jc.svg" />
	<link rel="stylesheet" href="../style.css">
	<link rel="stylesheet" href="s-donnee-direct.css">
    <!-- Bibliothèques -->
    <script type="text/javascript" src="../bibliotheque/jquery.min.js"></script>
    <script type="text/javascript" src="../bibliotheque/highcharts.js"></script>
</head>

<body>
	<?php
	include('../script/variablesStyle.php');
	include('../script/SelectionTable.php');
	include('../menu.php');
	include('../script/variablesBdd.php');
	include('../script/variablesTable.php');

	include('../script/LectureBDD/LectureMesure24h.php');
	include('../script/GrapheDirect.php')
	?>

	<div class="page">
		<div class="titre-page">
			<h3>Données en direct</h3>
			<div class="profil-PF">
				<label for="select-profil-text">Données : </label>
				<select class="select-profil" id="select-profil">
					<option value="potager" selected>Potager</option>
					<option value="fleurs">Fleurs</option>
				</select>
				<script type="text/javascript" src="../script/profil.js"></script>
			</div>
		</div>

		<div class="direct">
			<div class="direct-vign direct-temp"><div id="containerTempTD"></div></div>
			<div class="direct-vign direct-huma"><div id="containerHumATD"></div></div>
			<div class="direct-vign direct-lumi"><div id="containerLumiTD"></div></div>
			<div class="direct-vign direct-hums"><div id="containerHumSTD"></div></div>
			<button class="countHeure" id="countdown"></button>
		</div>
	</div>

<script type="text/javascript"> var TDtimeF = "<?php echo $TDtimeF; ?>"; </script>
<script type="text/javascript" src="j-donnee-direct.js"></script>
</body>
</html>
