export default class GameClient {

    constructor(socket) {
        this.socket = socket;

        socket.on('newGame', function (data) {
            console.log('newGame', data);
        });
    }

    newGame() {
        this.socket.emit("newGame");
    }

    startGame() {
        this.socket.emit("startGame", {'test':'test2'});
    }
}