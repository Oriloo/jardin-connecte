<?php
include('../connexion/test-connexion.php')
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Graphique - Jardin Connecté</title>
	<link rel="icon" href="../image/svg/logo-jc.svg" />
	<link rel="stylesheet" href="../style.css">
	<link rel="stylesheet" href="s-graphique.css">
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
	?>

	<div class="page">
		<div class="titre-page">
			<h3>Graphique</h3>
			<div class="profil-PF">
				<label for="select-profil-text">Données : </label>
				<select class="select-profil" id="select-profil">
					<option value="potager" selected>Potager</option>
					<option value="fleurs">Fleurs</option>
				</select>
				<script type="text/javascript" src="../script/profil.js"></script>
			</div>
		</div>

		<div class="graphiques">

			<div id="navigation">
				<a onclick="showContainer('container1')" class="clicked">
					Température <br>
					<span id="TemA_Moy"></span>°C
				</a>
				<a onclick="showContainer('container2')">
					Humidité Air <br>
					<span id="HumA_Moy"></span>%
				</a>
				<a onclick="showContainer('container3')">
					Humidité Sol <br>
					<span id="HumS_Moy"></span>%
				</a>
				<a onclick="showContainer('container4')">
					Luminosité <br>
					Niveau <span id="Lumi_Moy"></span>
				</a>

				<form method="post">
					<div class="select_date">
						<div class="dates-personnalisees" id="dates-personnalisees">
							<label for="date-debut">Début</label>
							<input type="date" id="date-debut" name="date-debut" oninput="PeriodeUpdate()">
							<br><label for="date-fin">Fin</label>
							<input type="date" id="date-fin" name="date-fin" oninput="PeriodeUpdate()">
						</div>

						<div class="triangle triangle-open"> <?php include('../image/svg/triangle-ico.svg'); ?> </div>
						<select name="periode-horaire" id="periode-horaire" onchange="PeriodeUpdate()">
							<option value="jour">Le dernier jour</option>
							<option value="semaine">7 derniers jours</option>
							<option value="mois">30 derniers jours</option>
							<option value="trimestre">90 derniers jours</option>
							<option value="annee">365 derniers jours</option>
							<option value="toujours">Depuis toujours</option>
							<option value="periode">Periode personnalisée</option>
						</select>
						
						<button type="submit" id="ButtonUpdate" disabled>
							<div id="Update"> <?php include('../image/svg/update-ico.svg'); ?> </div>
						</button>
					</div>
				</form>
			</div>

			<?php
			include('../script/SelectionDate.php');
			include('../script/LectureBDD/LectureMesure.php');
			include('../script/Calcule.php');
			include('../script/GraphePrincipal.php');
			?>

			<div class="visualiser">
				<div id="container1" class="container active"></div>
				<div id="container2" class="container"></div>
				<div id="container3" class="container"></div>
				<div id="container4" class="container"></div>
			</div>

			<div class="containerlegende">
				<div class="legende">
					<div class="colonneLegende">
						Niveau 1 : Eclairage public (20 lux)<br>
						Niveau 2 : Jour très couvert (100 lux)<br>
						Niveau 3 : Jour couvert (1 000 lux)<br>
						Niveau 4 : Jour typique (10 000 lux)<br>
						Niveau 5 : Eté ensoleillée (50 000 lux)<br>
					</div>
				</div>
			</div>
		</div>
	</div>

<script type="text/javascript">
	// afficher les valeurs moyenne de la nav
	var temAMoy = <?php echo json_encode($TemA_Moy); ?>;
	var humAMoy = <?php echo json_encode($HumA_Moy); ?>;
	var humSMoy = <?php echo json_encode($HumS_Moy); ?>;
	var lumiMoy = <?php echo json_encode($Lumi_Moy); ?>;
	document.getElementById('TemA_Moy').innerText = temAMoy;
	document.getElementById('HumA_Moy').innerText = humAMoy;
	document.getElementById('HumS_Moy').innerText = humSMoy;
	document.getElementById('Lumi_Moy').innerText = lumiMoy;

	// Récupérer les dates du tableau $Date
	var dates = <?php echo json_encode($Date); ?>;
</script>
<script type="text/javascript" src="j-graphique.js"></script>
</body>
</html>
