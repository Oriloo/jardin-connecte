<?php
include('../connexion/test-connexion.php')
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Contrôle - Jardin Connecté</title>
	<link rel="icon" href="../image/svg/logo-jc.svg" />
	<link rel="stylesheet" href="../style.css">
	<link rel="stylesheet" href="s-controle.css">
    <!-- Bibliothèques -->
    <script type="text/javascript" src="../bibliotheque/jquery.min.js"></script>
    <script type="text/javascript" src="../bibliotheque/range-slider/rangeslider.umd.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../bibliotheque/range-slider/style.css">
</head>

<body>
	<?php
	include('../script/variablesStyle.php');
	include('../script/SelectionTable.php');
	include('../menu.php');
	include('../script/variablesBdd.php');
	include('../script/variablesTable.php');

	include('../script/LectureBDD/LectureAlertesS.php');
	include('../script/LectureBDD/LectureDeclencheS.php');
	include('../script/LectureBDD/LectureHoraireP.php');
	include('../script/LectureBDD/LectureToleranceS.php');
	?>

	<div class="page">
		<div class="titre-page">
			<h3>Contrôle</h3>
			<div class="profil-PF">
				<label for="select-profil-text">Données : </label>
				<select class="select-profil" id="select-profil">
					<option value="potager" selected>Potager</option>
					<option value="fleurs">Fleurs</option>
				</select>
				<script type="text/javascript" src="../script/profil.js"></script>
			</div>
		</div>

		<div class="controles">
			<div class="declencher">
				<form method="post" action="../script/EcritureBDD/SaveDeclencheS.php">
					<input type="hidden" id="UserName" name="UserID" value="<?php echo $UserID; ?>">
					<input type="hidden" id="selectedTable" name="selectedTable" value="<?php echo $selectedTable; ?>">

					<div class="titre-cellule cel2">
						<div class="annuler" id="x-1"> <?php include('../image/svg/annuler-ico.svg'); ?> </div>
						<div class="titre-cel"> Seuils de déclenchement de l'arrosage </div>
						<div class="enregistrer" type="submit" id="v-1">
							<button type="submit">
								<?php include('../image/svg/valider-ico.svg'); ?>
							</button>
						</div>
					</div>

					<div class="slider">
						<div class="slider-valeurs">
							<div class="slider-min">0°C</div>
							<div class="slider-info1-1">
								Température Air :
								<span id="minOutput1-1">20</span> < x <
								<span id="maxOutput1-1">30</span> °C
							</div>
							<input type="hidden" id="minInput1-1" name="minInput1-1" value="-20">
							<input type="hidden" id="maxInput1-1" name="maxInput1-1" value="20">
							<div class="slider-max">40°C</div>
						</div>
						<div id="slider1-1"></div>
					</div>

					<div class="slider">
						<div class="slider-valeurs">
							<div class="slider-min">0%</div>
							<div class="slider-info1-2">
								Humidité Air
								<select class="select-symbole" id="select-SI2" name="select-SI2" onchange="RangeSliderSI2()">
									<option value="1">></option>
									<option value="0"><</option>
								</select>
								<span id="minOutput1-2">50</span>
								<span id="maxOutput1-2">50</span> %
							</div>
							<input type="hidden" id="minInput1-2" name="minInput1-2" value="0">
							<input type="hidden" id="maxInput1-2" name="maxInput1-2" value="50">
							<div class="slider-max">100%</div>
						</div>
						<div id="slider1-2"></div>
					</div>

					<div class="slider">
						<div class="slider-valeurs">
							<div class="slider-min">0%</div>
							<div class="slider-info1-3">
								Humidité Sol
								<select class="select-symbole" id="select-SI3" name="select-SI3" onchange="RangeSliderSI3()">
									<option value="1">></option>
									<option value="0"><</option>
								</select>
								<span id="minOutput1-3">50</span>
								<span id="maxOutput1-3">50</span> %
							</div>
							<input type="hidden" id="minInput1-3" name="minInput1-3" value="0">
							<input type="hidden" id="maxInput1-3" name="maxInput1-3" value="50">
							<div class="slider-max">100%</div>
						</div>
						<div id="slider1-3"></div>
					</div>

					<div class="slider">
						<div class="slider-valeurs">
							<div class="slider-min">Niv. 0</div>
							<div class="slider-info1-4">
								Luminosité
								<select class="select-symbole" id="select-SI4" name="select-SI4" onchange="RangeSliderSI4()">
									<option value="1">></option>
									<option value="0"><</option>
								</select>
								Niv. <span id="minOutput1-4">5</span>
								<span id="maxOutput1-4">5</span>
							</div>
							<input type="hidden" id="minInput1-4" name="minInput1-4" value="1">
							<input type="hidden" id="maxInput1-4" name="maxInput1-4" value="5">
							<div class="slider-max">Niv. 5</div>
						</div>
						<div id="slider1-4"></div>
					</div>
					
				</form>
			</div>

			<div class="manu-tole">
				<div class="manuellement">
					<div class="titre-cellule">
						<div class="titre-cel2"> Déclencher manuellement l'arrosage </div>
					</div>
					<div class="bouton-arrosage" onclick="confirmerArrosage('<?php echo $UserID; ?>', '<?php echo $selectedTable; ?>')">
						<?php include('../image/svg/eau-ico.svg'); ?>
					</div>
				</div>
				<div class="tolerance">
					<form method="post" action="../script/EcritureBDD/SaveToleranceS.php">
						<input type="hidden" id="UserName" name="UserID" value="<?php echo $UserID; ?>">
						<input type="hidden" id="selectedTable" name="selectedTable" value="<?php echo $selectedTable; ?>">

						<div class="titre-cellule cel2">
							<div class="annuler" id="x-3"> <?php include('../image/svg/annuler-ico.svg'); ?> </div>
							<div class="titre-cel"> Tolérance des seuils </div>
							<div class="enregistrer" type="submit" id="v-3">
								<button type="submit">
									<?php include('../image/svg/valider-ico.svg'); ?>
								</button>
							</div>
						</div>

						<div class="slider">
							<div class="slider-valeurs">
								<div class="slider-min">0h 15</div>
								<div class="slider-info3-1">
									Tolérance :
									<span id="minOutput3-1">1</span>h 
									<span id="maxOutput3-1">00</span>
								</div>
								<input type="hidden" id="minInput3-1" name="minInput3-1" value="2">
								<input type="hidden" id="maxInput3-1" name="maxInput3-1" value="6">
								<div class="slider-max">1h 30</div>
							</div>
							<div id="slider3-1"></div>
						</div>
					</form>
				</div>
			</div>

			<div class="alerter">
				<form method="post" action="../script/EcritureBDD/SaveAlertesS.php">
					<input type="hidden" id="UserName" name="UserID" value="<?php echo $UserID; ?>">
					<input type="hidden" id="selectedTable" name="selectedTable" value="<?php echo $selectedTable; ?>">

					<div class="titre-cellule cel2">
						<div class="annuler" id="x-2"> <?php include('../image/svg/annuler-ico.svg'); ?> </div>
						<div class="titre-cel"> Seuils de déclenchement de l'alertes </div>
						<div class="enregistrer" id="v-2">
							<button type="submit">
								<?php include('../image/svg/valider-ico.svg'); ?>
							</button>
						</div>
					</div>

					<div class="slider">
						<div class="slider-valeurs">
							<div class="slider-min">-10°C</div>
							<div class="slider-info2-1">
								Température Air :
								<span id="minOutput2-1">0</span> et
								<span id="maxOutput2-1">40</span> °C
							</div>
							<input type="hidden" id="minInput2-1" name="minInput2-1" value="0">
							<input type="hidden" id="maxInput2-1" name="maxInput2-1" value="40">
							<div class="slider-max">50°C</div>
						</div>
						<div id="slider2-1"></div>
					</div>

					<div class="slider">
						<div class="slider-valeurs">
							<div class="slider-min">0%</div>
							<div class="slider-info2-2">
								Humidité Air :
								<span id="minOutput2-2">20</span> et
								<span id="maxOutput2-2">80</span> %
							</div>
							<input type="hidden" id="minInput2-2" name="minInput2-2" value="20">
							<input type="hidden" id="maxInput2-2" name="maxInput2-2" value="80">
							<div class="slider-max">100%</div>
						</div>
						<div id="slider2-2"></div>
					</div>

					<div class="slider">
						<div class="slider-valeurs">
							<div class="slider-min">0%</div>
							<div class="slider-info2-3">
								Humidité Sol :
								<span id="minOutput2-3">20</span> et
								<span id="maxOutput2-3">80</span> %
							</div>
							<input type="hidden" id="minInput2-3" name="minInput2-3" value="20">
							<input type="hidden" id="maxInput2-3" name="maxInput2-3" value="80">
							<div class="slider-max">100%</div>
						</div>
						<div id="slider2-3"></div>
					</div>

					<div class="slider">
						<div class="slider-valeurs">
							<div class="slider-min">Niv. 0</div>
							<div class="slider-info2-4">
								Luminosité : Niv.
								<span id="minOutput2-4">2</span> et
								<span id="maxOutput2-4">8</span>
							</div>
							<input type="hidden" id="minInput2-4" name="minInput2-4" value="2">
							<input type="hidden" id="maxInput2-4" name="maxInput2-4" value="8">
							<div class="slider-max">Niv. 5</div>
						</div>
						<div id="slider2-4"></div>
					</div>

				</form>
			</div>

			<div class="restriction">
				<form method="post" action="../script/EcritureBDD/SaveHoraireP.php">
					<input type="hidden" id="UserName" name="UserID" value="<?php echo $UserID; ?>">
					<input type="hidden" id="selectedTable" name="selectedTable" value="<?php echo $selectedTable; ?>">

					<div class="titre-cellule cel2">
						<div class="annuler" id="x-4"> <?php include('../image/svg/annuler-ico.svg'); ?> </div>
						<div class="titre-cel"> Plage horaire de l'arrosage </div>
						<div class="enregistrer" type="submit" id="v-4">
							<button type="submit">
								<?php include('../image/svg/valider-ico.svg'); ?>
							</button>
						</div>
					</div>

					<div class="slider">
						<div class="slider-valeurs">
							<div class="slider-min">0h</div>
							<div class="slider-info4-1">
								Horaire :
								<span id="maxOutput4-1">20</span>h à
								<span id="minOutput4-1">8</span>h
							</div>
							<input type="hidden" id="minInput4-1" name="minInput4-1" value="8">
							<input type="hidden" id="maxInput4-1" name="maxInput4-1" value="20">
							<div class="slider-max">24h</div>
						</div>
						<div id="slider4-1"></div>
					</div>
				</form>

				<div class="EauSec54">
					<div class="EauSec54-logo" onclick="window.open('https://ssm-ecologie.shinyapps.io/EauSec54/', '_blank')">
						<div class="EauSec54-nom">EauSec54</div>
						<img class="EauSec54-img" src="../image/EauSec54-logo.png">
					</div>
					<div class="EauSec54-div" onclick="window.open('https://ssm-ecologie.shinyapps.io/EauSec54/', '_blank')">
						<a class="EauSec54-a">
							Pour voir quelles sont les restrictions d'eau en Meurthe-et-Moselle, visiter le site de la préfecture
							<u class="EauSec54-u">EauSec54</u>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
	include('../script/RangeSlider.php');
	include('../script/RangeSliderUdate.php');
	?>

<script type="text/javascript" src="j-controle.js"></script>
</body>
</html>
