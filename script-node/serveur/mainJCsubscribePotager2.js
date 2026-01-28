const mqtt = require('mqtt');
const mysql = require('mysql2');

console.log("Potager");

// Variables pour la connexion
const topicSub = "";
const url = '';
const options = {
  clean: true,
  connectTimeout: 4000,
  keepalive: 60,
  clientId: '',
  username: '',
  password: '',
};

// Connexion à TTN
const client = mqtt.connect(url, options);

// Connexion et définition BDD
const db = mysql.createConnection({
  host: "",
  user: "",
  password: "",
  database: ""
});
db.connect(function (err) {
  if (err) throw err;
  console.log("Connecté à la base de données MySQL!");
});

// Message de confirmation de connexion
client.on('connect', function () {
  console.log('Connected');
});

// Subscribe to a topic
client.subscribe(topicSub, function (err) {
  if (!err) {
    console.log(`client was subscribed`);
  }
});

/*----------------------------------------------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------- Def Pol ----------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------------------------------------------------------------*/

// Configurer les informations de connexion
const connection = mysql.createConnection({
  host: '', // Adresse du serveur MySQL
  user: '', // Nom d'utilisateur MySQL
  password: '', // Mot de passe MySQL
  database: '' // Nom de la base de données
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
    // Remplacement du ternaire pour "huma"
    let strHuma;
    if (T2_humaSI) {
      strHuma = '>';
    } else {
      strHuma = '<';
    }
    console.log(`huma : ${strHuma}${T2_huma}`);

    // Remplacement du ternaire pour "hums"
    let strHums;
    if (T2_humsSI) {
      strHums = '>';
    } else {
      strHums = '<';
    }
    console.log(`hums : ${strHums}${T2_hums}`);

    // Remplacement du ternaire pour "lumi"
    let strLumi;
    if (T2_lumiSI) {
      strLumi = '>';
    } else {
      strLumi = '<';
    }
    console.log(`lumi : ${strLumi}${T2_lumi}`);

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

      // Remplacement des ternaires pour l'affichage
      let strAlerte1, strAlerte2, strAlerte3, strAlerte4;
      if (testAlerte1) {
        strAlerte1 = '1';
      } else {
        strAlerte1 = '0';
      }
      if (testAlerte2) {
        strAlerte2 = '1';
      } else {
        strAlerte2 = '0';
      }
      if (testAlerte3) {
        strAlerte3 = '1';
      } else {
        strAlerte3 = '0';
      }
      if (testAlerte4) {
        strAlerte4 = '1';
      } else {
        strAlerte4 = '0';
      }
      console.log(`ligne ${i + 1} : ${strAlerte1}; ${strAlerte2}; ${strAlerte3}; ${strAlerte4}`);

      if (testAlerte1) { testAl1 += 1; }
      if (testAlerte2) { testAl2 += 1; }
      if (testAlerte3) { testAl3 += 1; }
      if (testAlerte4) { testAl4 += 1; }
    }

    console.log('ARROSAGES :');
    for (let i = 0; i < T4_tole; i++) {
      const testArrosage1 = T5_temp[i] > T2_temp0 && T5_temp[i] < T2_temp1;
      let testArrosage2;
      if (T2_humaSI) {
        testArrosage2 = T5_huma[i] > T2_huma;
      } else {
        testArrosage2 = T5_huma[i] < T2_huma;
      }
      let testArrosage3;
      if (T2_humsSI) {
        testArrosage3 = T5_hums[i] > T2_hums;
      } else {
        testArrosage3 = T5_hums[i] < T2_hums;
      }
      let testArrosage4;
      if (T2_lumiSI) {
        testArrosage4 = T5_lumi[i] > T2_lumi;
      } else {
        testArrosage4 = T5_lumi[i] < T2_lumi;
      }

      let strArrosage1, strArrosage2, strArrosage3, strArrosage4;
      if (testArrosage1) {
        strArrosage1 = '1';
      } else {
        strArrosage1 = '0';
      }
      if (testArrosage2) {
        strArrosage2 = '1';
      } else {
        strArrosage2 = '0';
      }
      if (testArrosage3) {
        strArrosage3 = '1';
      } else {
        strArrosage3 = '0';
      }
      if (testArrosage4) {
        strArrosage4 = '1';
      } else {
        strArrosage4 = '0';
      }
      console.log(`ligne ${i + 1} : ${strArrosage1}; ${strArrosage2}; ${strArrosage3}; ${strArrosage4}`);

      HumiSol += T5_hums[i];

      if (testArrosage1) { testAr1 += 1; }
      if (testArrosage2) { testAr2 += 1; }
      if (testArrosage3) { testAr3 += 1; }
      if (testArrosage4) { testAr4 += 1; }
    }

    const testHoraire = (time <= T3_heur0 || time >= T3_heur1) ? 1 : 0;

    console.log(`NIVEAU D'ALERTE : ${testAl1}; ${testAl2}; ${testAl3}; ${testAl4}`);
    console.log(`NIVEAU ARROSAGE : ${testAr1}; ${testAr2}; ${testAr3}; ${testAr4}`);
    console.log(`HORAIRE ARROSAGE : ${testHoraire}`);
    console.log('[Notifications test1]');

    let alerteN;
    if (testAl1 || testAl2 || testAl3 || testAl4) {
      alerteN = 1;
    } else {
      alerteN = 0;
    }
    console.log('Notif d\'alerte = ' + alerteN);

    let arrosageN;
    if (
      testHoraire &&
      testAr4 === T4_tole &&
      testAr1 === T4_tole &&
      (testAr2 === T4_tole || testAr3 === T4_tole)
    ) {
      arrosageN = 1;
    } else {
      arrosageN = 0;
    }
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
        const oneHourAgo1 = new Date();
        const oneHourAgo = new Date(oneHourAgo1.getTime() + 2 * 60 * 60 * 1000);
        const formattedOneHourAgo = oneHourAgo.toISOString();
        const dateform1 = new Date(formattedOneHourAgo).getTime();
        const dateform2 = new Date(lastAlerteTime).getTime();
        const dateformDif = dateform1 - dateform2;

        let testLastHTimeDif;
        if (dateformDif < 60 * 60 * 1000) {
          testLastHTimeDif = 1;
        } else {
          testLastHTimeDif = 0;
        }

        let isIdenticalAlerte;
        if (
          lastAlerte.temp_alerte == testAl1 &&
          lastAlerte.huma_alerte == testAl2 &&
          lastAlerte.hums_alerte == testAl3 &&
          lastAlerte.lumi_alerte == testAl4
        ) {
          isIdenticalAlerte = 1;
        } else {
          isIdenticalAlerte = 0;
        }

        if (testLastHTimeDif && isIdenticalAlerte) {
          console.log("L'alerte a déjà été envoyée il y a moins d'une heure");
        } else {
          await query(
            `INSERT INTO ${Table_Alertes} (date, time, temp_alerte, huma_alerte, hums_alerte, lumi_alerte)
             VALUES (?, ?, ?, ?, ?, ?)`,
            [
              currentDateTime.split(' ')[0],
              currentDateTime.split(' ')[1],
              testAl1 > 0,
              testAl2 > 0,
              testAl3 > 0,
              testAl4 > 0
            ]
          );
          console.log("L'alerte a été envoyée");
        }
      } else {
        await query(
          `INSERT INTO ${Table_Alertes} (date, time, temp_alerte, huma_alerte, hums_alerte, lumi_alerte)
           VALUES (?, ?, ?, ?, ?, ?)`,
          [
            currentDateTime.split(' ')[0],
            currentDateTime.split(' ')[1],
            testAl1 > 0,
            testAl2 > 0,
            testAl3 > 0,
            testAl4 > 0
          ]
        );
        console.log("L'alerte a été envoyée");
      }
    } else {
      console.log("Il n'y a pas d'alerte");
    }

    // Vérifier les arrosages
    const currentDateTime = formatDate(new Date());
    const [currDate, currTime] = currentDateTime.split(' ');

    // 1. Récupérer le dernier état valide (0 par défaut si vide)
    const lastArrosageResults = await query(`SELECT etat FROM ${Table_Arrosage} ORDER BY date DESC, time DESC LIMIT 1`);
    const lastEtat = (lastArrosageResults.length > 0) ? lastArrosageResults[0].etat : 0;

    // 2. Déterminer l'état cible
    const targetEtat = arrosageN;

    // 3. Appliquer la transition si nécessaire
    if (targetEtat !== lastEtat) {
      console.log(`Changement d'état détecté : ${lastEtat} -> ${targetEtat}`);
      await query(
        `INSERT INTO ${Table_Arrosage} (date, time, declencher_par, etat) VALUES (?, ?, ?, ?)`,
        [currDate, currTime, 0, targetEtat]
      );
      console.log(`Nouvel état ${targetEtat} inséré en base.`);
    } else {
      console.log(`Pas de changement d'état (Actuel : ${lastEtat}, Demandé : ${targetEtat})`);
    }

  } catch (error) {
    console.error('Erreur lors de l\'exécution de la requête :', error.message);
  }
};

/*----------------------------------------------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------- /Def Pol ---------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------------------------------------------------------------*/

// Fonction lors de la réception d'un message
client.on('message', function (topicSub, message) {
  var B64Message = message.toString().substring(
    message.toString().search("frm_payload") + 14,
    message.toString().search("frm_payload") + 30
  );
  console.log(B64Message);
  const tab = base64ToHex(B64Message);
  console.log(tab);
  connectMySql(tab);
  PolExecute();
});

// Conversion base64 (format TTN) en hexadecimal
function base64ToHex(str) {
  const raw = atob(str);
  let result = '';
  var tab = [];

  for (let i = 0; i < raw.length; i++) {
    const hex = raw.charCodeAt(i).toString(10);
    tab[i] = hex - 0x30;
    console.log(tab[i]);
  }
  return tab;
}

// Fonction INSERT dans la BDD des valeurs reçues.
function connectMySql(tab = []) {
  const Today = new Date;
  const mois = Today.getMonth() + 1;
  const date = Today.getFullYear() + "-" + mois + "-" + Today.getDate();
  const Time = Today.getHours() + ":" + Today.getMinutes();

  const temp = tab[0] * 100 + tab[1] * 10 + tab[2] - 50;
  console.log(temp);

  console.log(Today);
  console.log("voici mes date et time :");
  console.log(date);
  console.log(Time);
  db.query(
    "INSERT INTO p_mesures (date, time, temperature_air, humidite_air, humidite_sol, lumiere) VALUES ('" +
    date +
    "', '" +
    Time +
    "', '" +
    temp + "." + tab[3] +
    "', '" +
    tab[4] + tab[5] + tab[6] +
    "', '" +
    tab[7] + tab[8] + tab[9] +
    "', '" +
    tab[10] +
    "');",
    function (err, result) {
      if (err) throw err;
      console.log(result);
    }
  );
};

async function PolExecute() {
  try {
    await test('p_alertes', 'p_alertes_seuils', 'p_arrosage', 'p_arrosage_horaire', 'p_arrosage_seuils', 'p_mesures', 'p_tolerance_seuils', 'potager');
    await test('f_alertes', 'f_alertes_seuils', 'f_arrosage', 'f_arrosage_horaire', 'f_arrosage_seuils', 'f_mesures', 'f_tolerance_seuils', 'fleurs');
  } catch (error) {
    console.error('Erreur :', error);
  }
}
