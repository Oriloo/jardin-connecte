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
    var message = '0';

    db.query("SELECT duree FROM f_arrosage ORDER BY date DESC, time DESC LIMIT 1;",
        function (err, result) {
            if (err) throw err;
            else console.log(result);

            const dateDuree = result[0].duree;
            console.log("▶️ dateDuree =", dateDuree);
            const maintenant = new Date();

            if (!dateDuree || dateDuree <= maintenant) {
                message = '0';
                console.log("LE MESSAGE &er oui: " + message);
            } else {
                message = '1';
                console.log("LE MESSAGE &er non: " + message);
            }
        });

    db.query("SELECT duree FROM p_arrosage ORDER BY date DESC, time DESC LIMIT 1;",
        function (err, result) {
            if (err) throw err;
            else console.log(result);

            const dateDuree = result[0].duree;
            console.log("▶️ dateDuree =", dateDuree);
            const maintenant = new Date();

            if (!dateDuree || dateDuree <= maintenant) {
                console.log(1);
                if (message == '1') {
                    publisher(String.fromCharCode(1));
                } else {
                    publisher(String.fromCharCode(0));
                }
            } else {
                console.log(2);
                if (message == '1') {
                    publisher(String.fromCharCode(3));
                } else {
                    publisher(String.fromCharCode(2));
                }
            }
        });
}
