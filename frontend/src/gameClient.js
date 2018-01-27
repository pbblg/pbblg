export default class GameClient {

    constructor(socket) {
        this.socket = socket;
    }

    startGame() {
        this.socket.emit("startGame", {'test':'test2'});
    }
}