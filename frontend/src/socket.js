var conn = null; // Initialize our websocket variable
var isConnected = false; // Is the websocket connected?
var disconnects = 0; // How many times have we disconnected and retried (used for calculating retry wait)
var retryWaitMax = 1000; // Maximum time to wait for a re-connect attempt, ms
var pendingRequests = []; // Any requests coming in before we connect are stored and run when connected

const socket = {
    listeners: {},
    responseHandlers: {},

    connect: function(address) {
        address = address || 'ws://localhost:8088/';
        console.log(`ws: connecting to ${address}..`);
        conn = new WebSocket(address);

        conn.onopen = function(e) {
            console.log('ws: websocket opened.');
            isConnected = true;

            pendingRequests.forEach(function (request) {
                socket.send(request);
            });
        };

        conn.onmessage = function(e) {
            let data = JSON.parse(e.data);
            console.log(`ws: message received`, data);
            console.group();

            // response received
            if (data.hasOwnProperty('id')) {
                if (socket.responseHandlers.hasOwnProperty(data['id'])) {
                    console.log(`ws: call response handler`, socket.responseHandlers[data['id']]);
                    socket.responseHandlers[data['id']](data['result']);
                    delete socket.responseHandlers[data['id']];
                }
            } else {
                socket.listeners.forEach(function (item, index) {
                    if (index === data['event']) {
                        index.forEach(function (listener) {
                            console.log(`ws: call listener`, listener);
                            listener(data);
                        });
                    }
                });
            }
            console.groupEnd();
        };

        conn.close = function(e) {
            console.log('ws: socket has closed.');
            isConnected = false;
            disconnects++;
            let retryWait = disconnects * 50; // 50, 100, 150, 200 ...
            if (retryWait > retryWaitMax) {
                disconnects--;
                retryWait = retryWaitMax;
            }
            console.log(`ws: retrying in ${retryWait}ms...`);
            setTimeout(socket.connect, retryWait);
        };
    },

    on: function(eventName, callback) {
        if (!this.listeners.hasOwnProperty(eventName)) {
            this.listeners[eventName] = [];
        }

        let alreadyHas = false;
        this.listeners[eventName].forEach(function(item, index, array) {
            if (item === callback) {
                alreadyHas = true;
            }
        });

        if (!alreadyHas) {
            console.log(`ws: subscribe listener on ${eventName}`);
            this.listeners[eventName].push(callback);
        }
    },

    emit: function(eventName, params, responseHandler) {
        let id = this.guid();
        let data = {
            id: id,
            method: eventName,
            params: params
        };

        if (responseHandler) {
            this.responseHandlers[id] = responseHandler;
        }

        this.send(data);
    },

    send: function(request) {
        if (!isConnected) {
            pendingRequests.push(request);
            return;
        }

        console.log(`ws: send`, request);
        conn.send(JSON.stringify(request));
    },

    guid: function() {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
        }

        return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
    }
};

export default socket