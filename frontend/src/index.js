import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';
import App from './App';
import registerServiceWorker from './registerServiceWorker';
import ioClient from 'socket.io-client';
import GameClient from './gameClient';



const socket = ioClient.connect('http://172.17.0.2:8008');
socket.on('connecting', function () {
    console.log('Соединение...');
});
socket.on('connect', function () {
    console.log('Соединение установлено!');
});

const gameClient = new GameClient(socket);



const rootEl = document.getElementById('root')

ReactDOM.render(
    <App gameClient={gameClient} />,
    rootEl
)

registerServiceWorker();

if (module.hot) {
    module.hot.accept('./App', () => {
        const NextApp = require('./App').default
        ReactDOM.render(
            <NextApp />,
            rootEl
        )
    })
}

