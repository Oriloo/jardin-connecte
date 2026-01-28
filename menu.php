<!-- Barres de navigation et de menu du site -->
<div class="Barres">
	<!-- Barres supérieures du site -->
	<nav class="nav-bar">
		<div class="logo-jc"> <?php include('image/svg/logo-jc-nom.svg'); ?></div>
		<div class="ico-notife"> <?php include('image/svg/notife-ico.svg'); ?> </div>
	</nav>

	<!-- Barre latérale de menu -->
	<div class="BarreMenu">
		<!-- Informations sur l'utilisateur -->
		<div class="user-info">
			<?php
			// Variable $Avatar contenant le nom de l'avatar avec extension
			$avatarFile = '../image/pp/pp' . $Avatar;
			// Vérifie l'extension du fichier
			if (file_exists($avatarFile . '.png')) {
				$avatarFile .= '.png';
			} elseif (file_exists($avatarFile . '.gif')) {
				$avatarFile .= '.gif';
			} else {
				// Si le fichier n'existe pas, utilisez un avatar par défaut ou un chemin vers une image d'erreur
				$avatarFile = '../image/pp/pp0.png';
			}
			?>
			<img class="pp-user" src="<?php echo $avatarFile; ?>" alt="Avatar"> <br>
			<strong class="grade">
				<?php echo ($isAdmin == 1) ? "Administrateur" : "Utilisateur"; ?>
			</strong>
			<div class="nom"></div>
		</div>

		<!-- Liste des sections avec icônes -->
		<menu id="menu-liste">
			<a href="../tableau-bord/?profil=<?php echo $selectedTable; ?>">
				<li id="liste-table">
					<div class="ico-menu">
						<?php include('image/svg/tableau-ico.svg'); ?>
						Tableau de bord
					</div>
				</li>
			</a>
			<a href="../donnee-direct/?profil=<?php echo $selectedTable; ?>">
				<li id="liste-direc">
					<div class="ico-menu direct-pulse">
						<div class="pulse"></div>
						<p>Données en direct</p>
					</div>
				</li>
			</a>
			<a href="../graphique/?profil=<?php echo $selectedTable; ?>">
				<li id="liste-grphe">
					<div class="ico-menu">
						<?php include('image/svg/graph-ico.svg'); ?>
						Graphiques
					</div>
				</li>
			</a>
			<a href="../controle/?profil=<?php echo $selectedTable; ?>">
				<li id="liste-contr">
					<div class="ico-menu">
						<?php include('image/svg/sliders-ico.svg'); ?>
						Contrôles
					</div>
				</li>
			</a>
			<a href="../historique/?profil=<?php echo $selectedTable; ?>">
				<li id="liste-histo">
					<div class="ico-menu">
						<?php include('image/svg/hitorique-ico.svg'); ?>
						Historiques
					</div>
				</li>
			</a>
			<a href="../parametre/?profil=<?php echo $selectedTable; ?>">
				<li id="liste-param">
					<div class="ico-menu">
						<?php include('image/svg/param-ico.svg'); ?>
						Paramètres
					</div>
				</li>
			</a>

			<div class="autre-option">
				<!-- Bouton de déconnexion -->
				<li id="liste-decon" onclick="showLogoutConfirmation()">
					<div class="ico-menu">
						<?php include('image/svg/deco-ico.svg'); ?>
						Déconnexion
					</div>
				</li>

				<!-- Bouton d'aides -->
				<li id="liste-aides">
					<div class="ico-menu">
						<?php include('image/svg/aides-ico.svg'); ?>
						Prise en main
					</div>
				</li>
			</div>
		</menu>
	</div>
</div>

<!-- Zone de Notif
<div class="notif"></div>
-->

<script type="text/javascript">
// Fonction pour afficher la confirmation de déconnexion
function showLogoutConfirmation() {
	var isConfirmed = confirm("Êtes-vous sûr de vouloir vous déconnecter?");
	if (isConfirmed) {
		// Redirige vers la page de déconnexion
		window.location.href = "../connexion/logout.php";
	}
}

document.addEventListener("DOMContentLoaded", function() {
    const theme = <?php echo $Theme; ?>;

    // Supprimer toutes les classes de thème existantes
    document.documentElement.classList.remove('theme-clair', 'theme-sombre', 'theme-noir', 'theme-neon', 'theme-grie', 'theme-orange');

    // Appliquer le thème correspondant
    if (theme == 0) {
        document.documentElement.classList.add('theme-clair');
    } else if (theme == 1) {
        document.documentElement.classList.add('theme-sombre');
    } else if (theme == 2) {
        document.documentElement.classList.add('theme-noir');
    } else if (theme == 3) {
        document.documentElement.classList.add('theme-neon');
    } else if (theme == 4) {
        document.documentElement.classList.add('theme-grie');
    } else if (theme == 5) {
        document.documentElement.classList.add('theme-orange');
    }
});
</script>
