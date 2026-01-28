<?php
include('../connexion/test-connexion.php')
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Historiques - Jardin Connecté</title>
	<link rel="icon" href="../image/svg/logo-jc.svg" />
	<link rel="stylesheet" href="../style.css">
	<link rel="stylesheet" href="s-historique.css">
    <!-- Bibliothèques -->
    <script type="text/javascript" src="../bibliotheque/jquery.min.js"></script>
</head>

<body>
	<?php
	include('../script/variablesStyle.php');
	include('../script/SelectionTable.php');
	include('../menu.php');
	include('../script/variablesBdd.php');
	include('../script/variablesTable.php');

	include('../script/LectureBDD/LectureBddDay.php');
	include('../script/LectureBDD/LectureUtilisateurID.php');
	?>

	<div class="page">
		<div class="titre-page">
			<h3>Historiques</h3>
			<div class="profil-PF">
				<label for="select-profil-text">Données : </label>
				<select class="select-profil" id="select-profil">
					<option value="potager" selected>Potager</option>
					<option value="fleurs">Fleurs</option>
				</select>
				<script type="text/javascript" src="../script/profil.js"></script>
			</div>
		</div>

		<div class="historique">
			<div class="histo">
				<form method="post">
					<div class="onglets">
						<div class="onglets-bar">
							<div class="scroll scroll-left">&lt;</div>
							<div class="onglets-nav">
								<div class="onglet" id="onglet1">Mesures</div>
								<div class="onglet" id="onglet2">Arrosages</div>
								<div class="onglet" id="onglet3">Alertes</div>
								<div class="onglet" id="onglet4">Seuils d'arrosage</div>
								<div class="onglet" id="onglet5">Seuils d'alerte</div>
								<div class="onglet" id="onglet6">Tolérance des seuils</div>
								<div class="onglet" id="onglet7">Horaires d'arrosage</div>
							</div>
							<div class="scroll scroll-right">&gt;</div>
						</div>
						<div class="selecteurDate">
						    <div class="dateDay_onglet">
						        <label class="dateDay_label" for="dateDay">Jour</label>
						        <div class="inputContainer">
						        	<?php
			                        // Utilisez la première date du tableau $DAY1_date
			                        $defaultDate = !empty($DAY1_date) ? $DAY1_date[0] : date('Y-m-d');
			                        ?>
						            <input type="date" id="dateDay" name="dateDay" value="<?= $defaultDate ?>" onchange="enableButton()">
						            <button type="submit" id="ButtonSearch" disabled>
						                <?php include('../image/svg/search-ico.svg') ?>
						            </button>
						        </div>
						    </div>
						</div>
					</div>
				</form>

				<div class="pageHisto">
					<div class="mesuresHisto histo-vide">
						<table class="tablesHisto">
							<thead>
								<tr>
									<th>Température de l'Air</th>
									<th>Humidité de l'Air</th>
									<th>Humidité du Sol</th>
									<th>Niv. Luminosité</th>
									<th>Date et heure</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($DAY1_date)) {
									$nb1_histo = count($DAY1_date);

									for ($i = $nb1_histo - 1; $i >= 0; $i--) {
									    echo "<tr>
									        <td>".number_format($DAY1_temp[$i], 2)." °C</td>
									        <td>".number_format($DAY1_huma[$i], 2)." %</td>
									        <td>".number_format($DAY1_hums[$i], 2)." %</td>
									        <td>niv. ".$DAY1_lumi[$i]."</td>
									        <td>".$DAY1_date[$i]." à ".substr($DAY1_time[$i], 0, 5)."</td>
									    </tr>";
									}
									echo "</tbody></table>";
								} else {
									echo "</tbody></table>";
									include('../image/svg/logo-jc-nom.svg');
									echo "<br><div class='pas-trouver'>
										Il n'y a pas de donnée pour le ". $defaultDate ."
									</div>";
								}
								?>
					</div>

					<div class="arrosagesHisto histo-vide">
						<table class="tablesHisto">
							<thead>
								<tr>
									<th>Déclencher Par</th>
									<th>Date et heure</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($DAY4_date)) {
									$nb4_histo = count($DAY4_date);

									for ($i = 0; $i < $nb4_histo; $i++) {
										$id = $DAY4_modif[$i];

										echo "<tr>";
											// Vérifier si l'ID existe dans le tableau $loginById
											if (isset($loginById[$id])) {
											    // Si l'ID existe, afficher le login associé
											    echo "<td>".$loginById[$id]."</td>";
											} else {
											    // Si l'ID n'existe pas, afficher un message d'erreur ou une valeur par défaut
											    echo "<td>ID non trouvé</td>";
											}
											echo "<td>".$DAY4_date[$i]." à ".substr($DAY4_time[$i], 0, 5)."</td>
										</tr>";
									}
									echo "</tbody></table>";
								} else {
									echo "</tbody></table>";
									include('../image/svg/logo-jc-nom.svg');
									echo "<br><div class='pas-trouver'>
										Il n'y a pas de donnée pour le ". $defaultDate ."
									</div>";
								}
								?>
					</div>

					<div class="alertesHisto histo-vide">
						<table class="tablesHisto">
							<thead>
								<tr>
									<th>Température de l'Air</th>
									<th>Humidité de l'Air</th>
									<th>Humidité du Sol</th>
									<th>Niv. Luminosité</th>
									<th>Date et heure</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($DAY2_date)) {
									$nb2_histo = count($DAY2_date);

									for ($i = 0; $i < $nb2_histo; $i++) {
										echo "<tr>
											<td>".$DAY2_temp[$i]."</td>
											<td>".$DAY2_huma[$i]."</td>
											<td>".$DAY2_hums[$i]."</td>
											<td>".$DAY2_lumi[$i]."</td>
											<td>".$DAY2_date[$i]." à ".substr($DAY2_time[$i], 0, 5)."</td>
										</tr>";
									}
									echo "</tbody></table>";
								} else {
									echo "</tbody></table>";
									include('../image/svg/logo-jc-nom.svg');
									echo "<br><div class='pas-trouver'>
										Il n'y a pas de donnée pour le ". $defaultDate ."
									</div>";
								}
								?>
					</div>

					<div class="modifArrosagesHisto histo-vide">
						<table class="tablesHisto">
							<thead>
								<tr>
									<th>Température de l'Air</th>
									<th>Humidité de l'Air</th>
									<th>Humidité du Sol</th>
									<th>Niv. Luminosité</th>
									<th>Modifier Par</th>
									<th>Date et heure</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($DAY6_date)) {
									$nb6_histo = count($DAY6_date);

									for ($i = 0; $i < $nb6_histo; $i++) {
										$id = $DAY6_modif[$i];

										echo "<tr>
											<td style='display: flex; flex-wrap: wrap; text-align: center;'>
												<div style='width: 50px; margin-left: calc((100% - 120px)/2);'>
													".$DAY6_tempMin[$i]." °C
												</div> <div style='width: 20px;'> à </div>
												<div style='width: 50px;'>".$DAY6_tempMax[$i]." °C</div>
											</td>
											<td>".$DAY6_huma[$i]." %</td>
											<td>".$DAY6_hums[$i]." %</td>
											<td>Niv. ".$DAY6_lumi[$i]."</td>";
											// Vérifier si l'ID existe dans le tableau $loginById
											if (isset($loginById[$id])) {
											    // Si l'ID existe, afficher le login associé
											    echo "<td>".$loginById[$id]."</td>";
											} else {
											    // Si l'ID n'existe pas, afficher un message d'erreur ou une valeur par défaut
											    echo "<td>ID non trouvé</td>";
											}
											echo "<td>".$DAY6_date[$i]." à ".substr($DAY6_time[$i], 0, 5)."</td>
										</tr>";
									}
									echo "</tbody></table>";
								} else {
									echo "</tbody></table>";
									include('../image/svg/logo-jc-nom.svg');
									echo "<br><div class='pas-trouver'>
										Il n'y a pas de donnée pour le ". $defaultDate ."
									</div>";
								}
								?>
					</div>

					<div class="modifAlertesHisto histo-vide">
						<table class="tablesHisto">
							<thead>
								<tr>
									<th colspan="2">Température de l'Air</th>
									<th colspan="2">Humidité de l'Air</th>
									<th colspan="2">Humidité du Sol</th>
									<th colspan="2">Niv. Luminosité</th>
									<th>Modifier Par</th>
									<th>Date et heure</th>
								</tr>
								<tr>
									<td>min</td><td>max</td>
									<td>min</td><td>max</td>
									<td>min</td><td>max</td>
									<td>min</td><td>max</td>
									<td colspan="2"></td>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($DAY3_date)) {
									$nb3_histo = count($DAY3_date);

									for ($i = 0; $i < $nb3_histo; $i++) {
										$id = $DAY3_modif[$i];

										echo "<tr>
											<td>".$DAY3_tempMin[$i]." °C</td>
											<td>".$DAY3_tempMax[$i]." °C</td>
											<td>".$DAY3_humaMin[$i]." %</td>
											<td>".$DAY3_humaMax[$i]." %</td>
											<td>".$DAY3_humsMin[$i]." %</td>
											<td>".$DAY3_humsMax[$i]." %</td>
											<td>".$DAY3_lumiMin[$i]."</td>
											<td>".$DAY3_lumiMax[$i]."</td>";
											// Vérifier si l'ID existe dans le tableau $loginById
											if (isset($loginById[$id])) {
											    // Si l'ID existe, afficher le login associé
											    echo "<td>".$loginById[$id]."</td>";
											} else {
											    // Si l'ID n'existe pas, afficher un message d'erreur ou une valeur par défaut
											    echo "<td>ID non trouvé</td>";
											}
											echo "<td>".$DAY3_date[$i]." à ".substr($DAY3_time[$i], 0, 5)."</td>
										</tr>";
									}
									echo "</tbody></table>";
								} else {
									echo "</tbody></table>";
									include('../image/svg/logo-jc-nom.svg');
									echo "<br><div class='pas-trouver'>
										Il n'y a pas de donnée pour le ". $defaultDate ."
									</div>";
								}
								?>
					</div>

					<div class="modifTolerancesHisto histo-vide">
						<table class="tablesHisto">
							<thead>
								<tr>
									<th>Tolérance</th>
									<th>Modifier Par</th>
									<th>Date et heure</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($DAY7_date)) {
									$nb7_histo = count($DAY7_date);

									for ($i = 0; $i < $nb7_histo; $i++) {
										$id = $DAY7_modif[$i];
										$DAY7_tole_h = floor(($DAY7_tole[$i] * 15) / 60);
										$DAY7_tole_m = ($DAY7_tole[$i] * 15) % 60;

										echo "<tr>
											<td>".$DAY7_tole_h."h ".$DAY7_tole_m."</td>";
											// Vérifier si l'ID existe dans le tableau $loginById
											if (isset($loginById[$id])) {
											    // Si l'ID existe, afficher le login associé
											    echo "<td>".$loginById[$id]."</td>";
											} else {
											    // Si l'ID n'existe pas, afficher un message d'erreur ou une valeur par défaut
											    echo "<td>ID non trouvé</td>";
											}
											echo "<td>".$DAY7_date[$i]." à ".substr($DAY7_time[$i], 0, 5)."</td>
										</tr>";
									}
									echo "</tbody></table>";
								} else {
									echo "</tbody></table>";
									include('../image/svg/logo-jc-nom.svg');
									echo "<br><div class='pas-trouver'>
										Il n'y a pas de donnée pour le ". $defaultDate ."
									</div>";
								}
								?>
					</div>

					<div class="modifHorairesHisto histo-vide">
						<table class="tablesHisto">
							<thead>
								<tr>
									<th>Heure de début</th>
									<th>Heure de fin</th>
									<th>Modifier Par</th>
									<th>Date et heure</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($DAY5_date) ) {
									$nb5_histo = count($DAY5_date);

									for ($i = 0; $i < $nb5_histo; $i++) {
										$id = $DAY5_modif[$i];

										echo "<tr>
											<td>".$DAY5_maxh[$i]." h</td>
											<td>".$DAY5_minh[$i]." h</td>";
											// Vérifier si l'ID existe dans le tableau $loginById
											if (isset($loginById[$id])) {
											    // Si l'ID existe, afficher le login associé
											    echo "<td>".$loginById[$id]."</td>";
											} else {
											    // Si l'ID n'existe pas, afficher un message d'erreur ou une valeur par défaut
											    echo "<td>ID non trouvé</td>";
											}
											echo "<td>".$DAY5_date[$i]." à ".substr($DAY5_time[$i], 0, 5)."</td>
										</tr>";
									}
									echo "</tbody></table>";
								} else {
									echo "</tbody></table>";
									include('../image/svg/logo-jc-nom.svg');
									echo "<br><div class='pas-trouver'>
										Il n'y a pas de donnée pour le ". $defaultDate ."
									</div>";
								}
								?>
					</div>
				</div>
			</div>
		</div>
	</div>

<script type="text/javascript" src="j-historique.js"></script>
</body>
</html>
