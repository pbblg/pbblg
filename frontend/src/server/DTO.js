exports.playerDTO = function(player) {
    return {
        id: player.id,
        name: player.name,
        game: player.game,
    }
};