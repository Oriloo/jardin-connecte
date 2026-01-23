const mysql = require('mysql2');

// Configurer les informations de connexion
const connection = mysql.createConnection({
  host: 'my_db', // Adresse du serveur MySQL
  user: 'root', // Nom d'utilisateur MySQL
  password: 'rootpassword', // Mot de passe MySQL
  database: 'jardinconnectetest1' // Nom de la base de données
});

// Promisify the query function to use with async/await
const query = (sql, params) => {
  return new Promise((resolve, reject) => {
    connection.query(sql, params, (error, results) => {
      if (error) return reject(error);
      resolve(results);
    });
  });
};

const main = async () => {
  try {
    await connection.connect();

    await test('p_alertes', 'p_alertes_seuils', 'p_arrosage', 'p_arrosage_horaire', 'p_arrosage_seuils', 'p_mesures', 'p_tolerance_seuils', 'potager');
    await test('f_alertes', 'f_alertes_seuils', 'f_arrosage', 'f_arrosage_horaire', 'f_arrosage_seuils', 'f_mesures', 'f_tolerance_seuils', 'fleurs');
  } catch (error) {
    console.error('Erreur :', error);
  } finally {
    connection.end();
  }
};

const test = async (Table_Alertes, Table_AlertesS, Table_Arrosage, Table_ArrosageH, Table_ArrosageS, Table_Mesures, Table_ToleranceS, nom) => {
  try {
    console.log(`\nFunction test avec les tables ${nom} : `);

    /******************** ALERTES SEUILS ********************/
    const alerteSeuilResults = await query(`SELECT * FROM ${Table_AlertesS} ORDER BY date DESC, time DESC LIMIT 1`);
    const alerteSeuil = alerteSeuilResults[0];
    const T1_temp0 = alerteSeuil.temp_min;
    const T1_temp1 = alerteSeuil.temp_max;
    const T1_huma0 = alerteSeuil.huma_min;
    const T1_huma1 = alerteSeuil.huma_max;
    const T1_hums0 = alerteSeuil.hums_min;
    const T1_hums1 = alerteSeuil.hums_max;
    const T1_lumi0 = alerteSeuil.lumi_min;
    const T1_lumi1 = alerteSeuil.lumi_max;

    console.log('[AlertesS]');
    console.log(`temp : ${T1_temp0} et ${T1_temp1}`);
    console.log(`huma : ${T1_huma0} et ${T1_huma1}`);
    console.log(`hums : ${T1_hums0} et ${T1_hums1}`);
    console.log(`lumi : ${T1_lumi0} et ${T1_lumi1}`);

    /******************* ARROSAGES SEUILS *******************/
    const arrosageSeuilResults = await query(`SELECT * FROM ${Table_ArrosageS} ORDER BY date DESC, time DESC LIMIT 1`);
    const arrosageSeuil = arrosageSeuilResults[0];
    const T2_temp0 = arrosageSeuil.temp_min_d;
    const T2_temp1 = arrosageSeuil.temp_max_d;
    const T2_huma = arrosageSeuil.huma_d;
    const T2_hums = arrosageSeuil.hums_d;
    const T2_lumi = arrosageSeuil.lumi_d;
    const T2_humaSI = arrosageSeuil.huma_si;
    const T2_humsSI = arrosageSeuil.hums_si;
    const T2_lumiSI = arrosageSeuil.lumi_si;

    console.log('[ArrosageS]');
    console.log(`temp : ${T2_temp0} et ${T2_temp1}`);
    console.log(`huma : ${T2_humaSI ? '>' : '<'}${T2_huma}`);
    console.log(`hums : ${T2_humsSI ? '>' : '<'}${T2_hums}`);
    console.log(`lumi : ${T2_lumiSI ? '>' : '<'}${T2_lumi}`);

    /****************** ARROSAGES HORAIRES ******************/
    const arrosageHoraireResults = await query(`SELECT * FROM ${Table_ArrosageH} ORDER BY date DESC, time DESC LIMIT 1`);
    const arrosageHoraire = arrosageHoraireResults[0];
    const T3_heur0 = arrosageHoraire.heure_min;
    const T3_heur1 = arrosageHoraire.heure_max;

    console.log('[Horaire]');
    console.log(`de ${T3_heur1}h à ${T3_heur0}h`);

    /******************* TOLERANCE SEUILS *******************/
    const toleranceSeuilResults = await query(`SELECT * FROM ${Table_ToleranceS} ORDER BY date DESC, time DESC LIMIT 1`);
    const toleranceSeuil = toleranceSeuilResults[0];
    const T4_tole = toleranceSeuil.tolerance;

    console.log('[Tolerance]');
    console.log(`de ${T4_tole}`);

    /******************** TABLES MESURES ********************/
    const mesuresResults = await query(`SELECT * FROM ${Table_Mesures} ORDER BY date DESC, time DESC LIMIT ${T4_tole}`);
    const T5_temp = [];
    const T5_huma = [];
    const T5_hums = [];
    const T5_lumi = [];

    mesuresResults.forEach((row) => {
      T5_temp.push(row.temperature_air);
      T5_huma.push(row.humidite_air);
      T5_hums.push(row.humidite_sol);
      T5_lumi.push(row.lumiere);
    });

    console.log('[Mesures]');
    for (let i = 0; i < T4_tole; i++) {
      console.log(`ligne ${i + 1} : ${T5_temp[i]}; ${T5_huma[i]}; ${T5_hums[i]}; ${T5_lumi[i]}`);
    }

    /*********************** CALCULES ***********************/
    console.log('[ CALCULE ]');

    const date_actuelle = new Date();
    const time = date_actuelle.getHours() + date_actuelle.getMinutes() / 60;

    let testAl1 = 0, testAl2 = 0, testAl3 = 0, testAl4 = 0;
    let testAr1 = 0, testAr2 = 0, testAr3 = 0, testAr4 = 0;
    let HumiSol = 0;

    console.log('ALERTES :');
    for (let i = 0; i < T4_tole; i++) {
      const testAlerte1 = T5_temp[i] <= T1_temp0 || T5_temp[i] >= T1_temp1;
      const testAlerte2 = T5_huma[i] <= T1_huma0 || T5_huma[i] >= T1_huma1;
      const testAlerte3 = T5_hums[i] <= T1_hums0 || T5_hums[i] >= T1_hums1;
      const testAlerte4 = T5_lumi[i] <= T1_lumi0 || T5_lumi[i] >= T1_lumi1;

      console.log(`ligne ${i + 1} : ${testAlerte1 ? '1' : '0'}; ${testAlerte2 ? '1' : '0'}; ${testAlerte3 ? '1' : '0'}; ${testAlerte4 ? '1' : '0'}`);

      testAl1 += testAlerte1 ? 1 : 0;
      testAl2 += testAlerte2 ? 1 : 0;
      testAl3 += testAlerte3 ? 1 : 0;
      testAl4 += testAlerte4 ? 1 : 0;
    }

    console.log('ARROSAGES :');
    for (let i = 0; i < T4_tole; i++) {
      const testArrosage1 = T5_temp[i] > T2_temp0 && T5_temp[i] < T2_temp1;
      const testArrosage2 = T2_humaSI ? T5_huma[i] > T2_huma : T5_huma[i] < T2_huma;
      const testArrosage3 = T2_humsSI ? T5_hums[i] > T2_hums : T5_hums[i] < T2_hums;
      const testArrosage4 = T2_lumiSI ? T5_lumi[i] > T2_lumi : T5_lumi[i] < T2_lumi;

      console.log(`ligne ${i + 1} : ${testArrosage1 ? '1' : '0'}; ${testArrosage2 ? '1' : '0'}; ${testArrosage3 ? '1' : '0'}; ${testArrosage4 ? '1' : '0'}`);

      HumiSol += T5_hums[i];

      testAr1 += testArrosage1 ? 1 : 0;
      testAr2 += testArrosage2 ? 1 : 0;
      testAr3 += testArrosage3 ? 1 : 0;
      testAr4 += testArrosage4 ? 1 : 0;
    }

    const testHoraire = (time <= T3_heur0 || time >= T3_heur1) ? 1 : 0;
    console.log(`NIVEAU D'ALERTE : ${testAl1}; ${testAl2}; ${testAl3}; ${testAl4}`);
    console.log(`NIVEAU ARROSAGE : ${testAr1}; ${testAr2}; ${testAr3}; ${testAr4}`);
    console.log(`HORAIRE ARROSAGE : ${testHoraire}`);

    console.log('[Notifications test1]');
    const alerteN = (testAl1 || testAl2 || testAl3 || testAl4) ? 1 : 0;
    console.log('Notif d\'alerte = ' + alerteN);
    const arrosageN = (testHoraire && (testAr4 == T4_tole) && (testAr1 == T4_tole) && ((testAr2 == T4_tole) || (testAr3 == T4_tole))) ? 1 : 0;
    console.log('Notif d\'arrosage = ' + arrosageN);

    /*********************** ENVOYER ************************/
    console.log('[Notifications test2 + envoyer]');
    const formatDate = (date) => {
      date.setHours(date.getHours() + 2);
      return date.toISOString().slice(0, 19).replace('T', ' ');
    };

    // Vérifier les alertes
    if (alerteN === 1) {
      const currentDateTime = formatDate(new Date());

      // Récupérer la dernière alerte de la table
      const lastAlerteResults = await query(`SELECT * FROM ${Table_Alertes} ORDER BY date DESC, time DESC LIMIT 1`);
      const lastAlerte = lastAlerteResults[0];

      if (lastAlerte) {
        // Construction de lastAlerteTime
        const lastAlerteDate = new Date(lastAlerte.date);
        const [hours, minutes, seconds] = lastAlerte.time.split(':');
        lastAlerteDate.setUTCHours(hours, minutes, seconds, 0);
        const lastAlerteTime = lastAlerteDate.toISOString();
        // Obtenir la date et l'heure actuelles
        const oneHourAgo1 = new Date();
        const oneHourAgo = new Date(oneHourAgo1.getTime() + 2 * 60 * 60 * 1000); // ajoute 2h
        const formattedOneHourAgo = oneHourAgo.toISOString();

        const dateform1 = new Date(formattedOneHourAgo).getTime();
        const dateform2 = new Date(lastAlerteTime).getTime();
        const dateformDif = dateform1 - dateform2;
        const testLastHTimeDif = (dateformDif < 60 * 60 * 1000) ? 1 : 0;

        const isIdenticalAlerte =(lastAlerte.temp_alerte == testAl1 &&
                                  lastAlerte.huma_alerte == testAl2 &&
                                  lastAlerte.hums_alerte == testAl3 &&
                                  lastAlerte.lumi_alerte == testAl4 ) ? 1 : 0;

        if (testLastHTimeDif && isIdenticalAlerte) {
          console.log("L'alerte a déjà été envoyée il y a moins d'une heure");
        } else {
          await query(`INSERT INTO ${Table_Alertes} (date, time, temp_alerte, huma_alerte, hums_alerte, lumi_alerte)
                       VALUES (?, ?, ?, ?, ?, ?)`,
                       [currentDateTime.split(' ')[0], currentDateTime.split(' ')[1], testAl1 > 0, testAl2 > 0, testAl3 > 0, testAl4 > 0]);
          console.log("L'alerte a été envoyée");
        }
      } else {
        await query(`INSERT INTO ${Table_Alertes} (date, time, temp_alerte, huma_alerte, hums_alerte, lumi_alerte)
                     VALUES (?, ?, ?, ?, ?, ?)`,
                     [currentDateTime.split(' ')[0], currentDateTime.split(' ')[1], testAl1 > 0, testAl2 > 0, testAl3 > 0, testAl4 > 0]);
        console.log("L'alerte a été envoyée");
      }
    } else {
      console.log("Il n'y a pas d'alerte");
    }

    // Vérifier les arrosages
    if (arrosageN === 1) {
      const currentDateTime = formatDate(new Date());

      // Récupérer la dernière ligne d'arrosage
      const lastArrosageResults = await query(`SELECT * FROM ${Table_Arrosage} ORDER BY date DESC, time DESC LIMIT 1`);
      const lastArrosage = lastArrosageResults[0];

      if (lastArrosage && lastArrosage.duree === '0000-00-00 00:00:00') {
        console.log("Il y a déjà un arrosage en cours");
      } else {
        await query(`INSERT INTO ${Table_Arrosage} (date, time, declencher_par, duree)
                     VALUES (?, ?, ?, ?)`,
                     [currentDateTime.split(' ')[0], currentDateTime.split(' ')[1], 0, '0000-00-00 00:00:00']);
        console.log("L'arrosage a été envoyé");
      }
    } else {
      console.log("Il n'y a pas d'arrosage");
      
      // Récupérer la dernière ligne d'arrosage
      const lastArrosageResults = await query(`SELECT * FROM ${Table_Arrosage} ORDER BY date DESC, time DESC LIMIT 1`);
      const lastArrosage = lastArrosageResults[0];

      if (lastArrosage && (lastArrosage.duree === '0000-00-00 00:00:00' || lastArrosage.duree === null)) {
        // Récupérer la DATE et le TIME des colonnes "date" et "time"
        const nDateDeDebutAr = lastArrosage.date;
        const DateDeDebutAr = nDateDeDebutAr.toISOString().split('T')[0];
        const TimeDeDebutAr = lastArrosage.time;

        // Mettre la DATE et le TIME sous la forme DATETIME
        const DateTimeDeDebutAr = `${DateDeDebutAr} ${TimeDeDebutAr}`;
        // Récupérer le DATETIME actuelle
        const DateTimeDeFinAr = formatDate(new Date());
        // Calculer le temps écoulé entre ces deux périodes
        const TempsEcouleAr = new Date(DateTimeDeFinAr) - new Date(DateTimeDeDebutAr);
        
        // Fonction pour formater une durée en "yyyy-MM-dd HH:mm:ss"
        function formatDuree(milliseconds) {
          // Calcul des différentes unités
          const seconds = Math.floor(milliseconds / 1000);
          const minutes = Math.floor(seconds / 60);
          const hours = Math.floor(minutes / 60);
          const days = Math.floor(hours / 24);

          // Extraction des années, mois, jours, heures, minutes et secondes
          const years = Math.floor(days / 365);
          const months = Math.floor((days % 365) / 30);
          const remainingDays = Math.floor(days % 30);
          const remainingHours = Math.floor(hours % 24);
          const remainingMinutes = Math.floor(minutes % 60);
          const remainingSeconds = Math.floor(seconds % 60);

          // Formatage de la durée
          return `${years.toString().padStart(4, '0')}-${months.toString().padStart(2, '0')}-${remainingDays.toString().padStart(2, '0')} ${remainingHours.toString().padStart(2, '0')}:${remainingMinutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        }

        // Convertir le temps écoulé en une chaîne de caractères au format "yyyy-MM-dd HH:mm:ss"
        const formattedTempsEcouleAr = formatDuree(new Date(TempsEcouleAr));

        // Modifier la valeur de la colonne "duree"
        await query(`UPDATE ${Table_Arrosage} SET duree = ? WHERE date = ? AND time = ?`,
                    [formattedTempsEcouleAr, nDateDeDebutAr, TimeDeDebutAr]);

        console.log(`L'arrosage s'est terminé. Durée: ${formattedTempsEcouleAr}`);
      } else {
        console.log("Aucun arrosage en cours à terminer.");
      }
    }

  } catch (error) {
    console.error('Erreur lors de l\'exécution de la requête :', error.message);
  }
};

main();
