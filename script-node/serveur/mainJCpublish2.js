// Bibliothèque pour mqtt et mysql
const mqtt = require('mqtt');
const mysql = require('mysql2');

// Variables pour gérer la communication TTN
const url = ''
const topicSub = ""
const topicPub = ""
const options = {
    clean: true,
    connectTimeout: 4000,
    keepalive: 60,
    clientId: '',
    username: '',
    password: '',
}

// Connexion à TTN
const client = mqtt.connect(url, options)

// Connexion à la base de données
const db = mysql.createConnection({
    host: "",
    user: "",
    password: "",
    database: "",
    dateString: true
});

db.connect(function (err) {
    if (err) throw err;
    console.log("Connecté à la base de données MySQL!");
});

// Main du publi quand message reçu
client.on('message', function (topicSub, message) {
    connectMySql();
})

client.on('connect', function () {
    console.log('Connected')
});

// S'abonne en attente de l'ouverture du module
client.subscribe(topicSub, function (err) {
    if (!err) {
        console.log(`client was subscribed`)
    }
});

// Fonction pour la publication des données
function publisher(message) {
    console.log('🛜 publisher: ', message);

    const prototypeRep = `{
            "downlinks": [{
                "f_port": 1,
                "frm_payload": "${Buffer.from(message).toString('base64')}",
                "priority": "NORMAL"
            }]
        }`;

    client.publish(topicPub, prototypeRep, { qos: 0, retain: false }, function (error) {
        if (error) {
            console.log(error)
        } else {
            console.log('Published')
        }
    })
}

// Récupère l'information de si on doit arroser ou non
function connectMySql() {
    console.log('▶️ connectMySql() déclenchée');

    // Promesse pour récupérer l'état Fleurs
    const getFleurState = new Promise((resolve, reject) => {
        db.query("SELECT etat FROM f_arrosage ORDER BY date DESC, time DESC LIMIT 1;", (err, result) => {
            if (err) return reject(err);
            if (result.length === 0) return resolve(0);
            resolve(result[0].etat ? 1 : 0);
        });
    });

    // Promesse pour récupérer l'état Potager
    const getPotagerState = new Promise((resolve, reject) => {
        db.query("SELECT etat FROM p_arrosage ORDER BY date DESC, time DESC LIMIT 1;", (err, result) => {
            if (err) return reject(err);
            if (result.length === 0) return resolve(0);
            resolve(result[0].etat ? 1 : 0);
        });
    });

    Promise.all([getFleurState, getPotagerState])
        .then(([fleurState, potagerState]) => {
            console.log(`États récupérés -> Fleurs: ${fleurState}, Potager: ${potagerState}`);

            let codeToSend;
            if (potagerState === 0) {
                if (fleurState === 1) {
                    codeToSend = 1;
                } else {
                    codeToSend = 0;
                }
            } else {
                if (fleurState === 1) {
                    codeToSend = 3;
                } else {
                    codeToSend = 2;
                }
            }

            publisher(String.fromCharCode(codeToSend));
        })
        .catch(err => {
            console.error("Erreur SQL:", err);
        });
}
