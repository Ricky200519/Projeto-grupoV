const mqtt = require('mqtt');
const WebSocket = require('ws');

console.log('A iniciar servidor...');

const client = mqtt.connect('mqtt://localhost:1883');

const wss = new WebSocket.Server({ port: 8080 });

wss.on('connection', ws => {
    console.log('Novo cliente WebSocket conectado');
});

client.on('connect', () => {
    console.log('Ligado ao Mosquitto');
    client.subscribe('games/updates', (err) => {
        if (!err) console.log('Inscrito no tópico jogo/update');
    });
});

client.on('message', (topic, message) => {
    const msg = message.toString();
    console.log('Mensagem MQTT recebida:', msg);

    wss.clients.forEach(ws => {
        if (ws.readyState === WebSocket.OPEN) {
            ws.send(msg);
        }
    });
});

console.log('WebSocket ativo na porta 8080');
