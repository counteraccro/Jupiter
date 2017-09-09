Game = {};

Game.Launch = function(params)
{
	Game.url_generate_player = params.url_generate_player;
	Game.url_game = params.url_game;
	Game.url_find_open_lobby = params.url_find_open_lobby;
	Game.url_waiting_lobby = params.url_waiting_lobby;
	Game.player_id = params.player_id;

	Game.data = [];
	Game.interval = 0;
	Game.loop = 1;

	/**
	 * Manage Event of the page
	 */
	Game.Event = function()
	{
		// Generate player
		$('#new-game').click(function() {

			var html = 'Génération des joueurs <br /><div class="progress"><div class="indeterminate"></div></div>';
			$('#game-bloc #begin-bloc').html(html);

			$.ajax({
				type: "GET",
				url: Game.url_generate_player,
				dataType: "json",
				success: function(response) {
					data = JSON.parse(response['data']);
					console.log(data);
					$('#game-bloc #begin-bloc').html("Affichage des joueurs présent dans " + data['lobby'].name + " n°" + data['lobby'].id + "<br />");

					for(var i in data['players']) {
						player = data['players'][i];
						$('#game-bloc #begin-bloc').append(player.id + ' - Name :' + player.name + ' - HP : ' + player.hp + '<br />');
					}
					$('#game-bloc #begin-bloc').append('<a class="waves-effect waves-light btn" id="launch_game" data-lobby="' + data['lobby'].id + '">Que le meilleur gagne !</a>');
				}
			});
		}),

		// Event button next day
		$("body").on("click", "#log_next_day", function() {
			var day = $(this).data('day');
			Game.DisplayLogs(day);
		})
	}
	
	/**
	 * Run the game
	 */
	Game.RunGame = function(id, is_first)
	{
		clearInterval(Game.interval);
		
		if(is_first)
		{
			var url = Game.url_game.substring(0,Game.url_game.length-1) + id;
	
			$.ajax({
				type: "GET",
				url: url,
				dataType: "json",
				success: function(response) {
					Game.data = JSON.parse(response['data']);
					Game.DisplayLogs(1);
					Game.RunGame(id, false);
				}
			});
		}
		else {
			console.log('a faire');
		}
	}

	/**
	 * Format and display logs in HTML
	 */
	Game.DisplayLogs = function(day) {

		$('#game-bloc #begin-bloc').html('').append('Jour ' + day).append('<ul>');

		for(var i in Game.data['logs'][day]) {
			log = Game.data['logs'][day][i];
			$('#game-bloc #begin-bloc').append('<li>' + log + '</li>');
		}

		$('#game-bloc #begin-bloc').append('</ul>');
		if (Game.data['logs'].hasOwnProperty((day+1)))
		{
			$('#game-bloc #begin-bloc').append('<a class="waves-effect waves-light btn" id="log_next_day" data-day="' + (day+1) + '">Passer au jour ' + (day+1) + '</a>');
		}
		else
		{
			$('#game-bloc #begin-bloc').append('Fin de la partie');
		}
	}

	/**
	 * Search open lobby
	 */
	Game.SearchOpenLobby = function()
	{
		HtmlRender.Preloader('#game-bloc #begin-bloc', 'Recherche d\'un lobby... Essai n° ' + Game.loop);

		Game.interval = setInterval(Game._SearchOpenLobby, 1000);

	}

	/**
	 * Loop search open lobby
	 */
	Game._SearchOpenLobby = function()
	{
		$.ajax({
			type: "GET",
			url: Game.url_find_open_lobby,
			dataType: "json",
			success: function(response) {
				data = JSON.parse(response['data']);

				Game.loop++;

				if(data.response == 'no_lobby')
				{
					HtmlRender.Preloader('#game-bloc #begin-bloc', 'Recherche d\'un lobby... Essai n° ' + Game.loop);

					if(Game.loop == 6)
					{
						clearInterval(Game.interval);
						alert('fin mais pas trouvé');
					}
				}
				else if(data.response == 'critique_error')
				{
					clearInterval(Game.interval);
					alert(data.text);
				}
				else
				{
					clearInterval(Game.interval);
					Game.WaitInLobby(data.lobby_id);
				}
			}
		});
	}
	
	/**
	 * Waiting complete game
	 */
	Game.WaitInLobby = function(lobby_id)
	{
		Game.interval = '';
		HtmlRender.Preloader('#game-bloc #begin-bloc', 'Lobby trouvé en attente de joueur....');
		Game.interval = setInterval(function () {Game._WaitInLobby(lobby_id)}, 3000);
	}
	
	Game._WaitInLobby = function(lobby_id)
	{
		var url =  Game.url_waiting_lobby.substring(0, Game.url_waiting_lobby.length-1) + lobby_id;
		
		console.log(url);
		
		$.ajax({
			type: "GET",
			url: url,
			success: function(response) {
				$('#game-bloc #begin-bloc').html(response);
			}
		});
	}
}