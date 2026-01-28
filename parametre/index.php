<?php
include('../connexion/test-connexion.php')
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Paramètres - Jardin Connecté</title>
	<link rel="icon" href="../image/svg/logo-jc.svg" />
	<link rel="stylesheet" href="../style.css">
	<link rel="stylesheet" href="s-parametre.css">
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
			<h3>Paramètres</h3>
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
			<div class="parametre-box">
				<div class="zone-pp">
					<div class="titre-zone-pp">
						<div class="annuler-ico" id="x-pp"> <?php include('../image/svg/annuler-ico.svg'); ?> </div>
						<div class="titre-cel-pp">Changer de photo de profil</div>
						<div class="enregistrer" type="submit" id="v-pp">
							<button type="edit-ico">
								<?php include('../image/svg/edit-ico.svg'); ?>
							</button>
						</div>
					</div>
					<div class="image-pp">
						<img src="../image/pp/pp1.png" class="profile-pic" value="1">
						<img src="../image/pp/pp2.png" class="profile-pic" value="2">
						<img src="../image/pp/pp5.png" class="profile-pic" value="5">
						<img src="../image/pp/pp6.png" class="profile-pic" value="6">
						<img src="../image/pp/pp3.png" class="profile-pic" value="3">
						<img src="../image/pp/pp4.png" class="profile-pic" value="4">
						<img src="../image/pp/pp7.png" class="profile-pic" value="7">
						<img src="../image/pp/pp8.png" class="profile-pic" value="8">
						<img src="../image/pp/pp9.png" class="profile-pic" value="9">
						<img src="../image/pp/pp10.png" class="profile-pic" value="10">
						<img src="../image/pp/pp11.png" class="profile-pic" value="11">
						<img src="../image/pp/pp12.png" class="profile-pic" value="12">
					</div>
				</div>

				<div class="zone-theme">
					<div class="titre-zone-theme">
						<div class="annuler-ico" id="x-theme"> <?php include('../image/svg/annuler-ico.svg'); ?> </div>
						<div class="titre-cel-theme">Changer le thème du site</div>
						<div class="enregistrer" type="submit" id="v-theme">
							<button type="edit-ico">
								<?php include('../image/svg/edit-ico.svg'); ?>
							</button>
						</div>
					</div>
					<div class="image-theme">
						<img src="../image/theme/theme-clair.png" class="theme-pic" value="0">
						<img src="../image/theme/theme-grie.png" class="theme-pic" value="4">
						<img src="../image/theme/theme-orange.png" class="theme-pic" value="5">
						<img src="../image/theme/theme-noir.png" class="theme-pic" value="2">
						<img src="../image/theme/theme-sombre.png" class="theme-pic" value="1">
						<img src="../image/theme/theme-neon.png" class="theme-pic" value="3">
					</div>
				</div>

				<div class="zone-exporter">
					<div class="titre-zone-export">
						<div class="titre-cel-export">Exporter les données</div>
					</div>

					<div class="zone2-exporter">
						<div class="nom-cel-export">Exporter toutes les données dans un fichier CSV.</div>
						<div class="exporter" type="submit" id="v-export1">
							<a href="../script/LectureBDD/ExporterCSV.php"><?php include('../image/svg/export-ico.svg'); ?></a>
						</div>
					</div>
					<div class="zone2-exporter">
						<div class="nom-cel-export">Exporter toutes les données dans plusieurs fichiers CSV, regroupés dans une archive ZIP.</div>
						<div class="exporter" type="submit" id="v-export2">
							<a href="../script/LectureBDD/ExporterCSVzip.php"><?php include('../image/svg/export-ico.svg'); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<script type="text/javascript">
	var UserID = <?php echo $UserID; ?>;
	const defaultThemeValue = "<?php echo $Theme; ?>";
</script>
<script type="text/javascript" src="j-parametre.js"></script>
</body>
</html>
