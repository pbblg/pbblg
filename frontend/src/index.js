import './index.css'
import React from 'react'
import { render } from 'react-dom'
import { Provider } from 'react-redux'
import { createLogger } from 'redux-logger'
import { createStore, applyMiddleware } from 'redux'
import { BrowserRouter as Router, Route } from 'react-router-dom'
import app from './reducers/index'
import GameWelcomeComponent from './components/GameWelcomeComponent'
import GamePlayContainer from './containers/GamePlayContainer'
import ioClient from "socket.io-client";
import {newGameWasCreatedAction, receiveGameWelcomeState} from "./actions/index";
import remoteActionMiddleware from "./middlewares/remoteAction";



const socket = ioClient.connect('http://172.17.0.2:8008');
socket.on('connecting', function () {
    console.log('Соединение...');
});
socket.on('connect', function () {
    console.log('Соединение установлено!');
});




const loggerMiddleware = createLogger()

let store = createStore(
    app,
    applyMiddleware(
        loggerMiddleware,
        remoteActionMiddleware(socket)
    )
)

socket.on('newGame', function (data) {
    store.dispatch(newGameWasCreatedAction(data))
});
socket.on('gameWelcomeState', function (data) {
    store.dispatch(receiveGameWelcomeState(data))
});

render(
    <Provider store={store}>
        <Router>
            <div>
                <Route exact path="/" component={GameWelcomeComponent}/>
                <Route exact path="/:gameId" component={GamePlayContainer}/>
            </div>
        </Router>
    </Provider>,
    document.getElementById('root')
)


/*
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

*/