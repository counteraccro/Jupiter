Game = {};

Game.Launch = function(params)
{
	Game.url_generate_player = params.url_generate_player;
	Game.url_game = params.url_game;
	
	Game.data = [];
	
	/**
	 * Manage Event of the page
	 */
	Game.Event = function()
	{
		// Generate player
		$('#new-game').click(function() {

			var html = 'Génération des joueurs <br /><div class="progress"><div class="indeterminate"></div></div>';
			$('#index-bloc #begin-bloc').html(html);

			$.ajax({
				type: "GET",
				url: Game.url_generate_player,
				dataType: "json",
				success: function(response) {
					data = JSON.parse(response['data']);
					console.log(data);
					$('#index-bloc #begin-bloc').html("Affichage des joueurs présent dans " + data['lobby'].name + " n°" + data['lobby'].id + "<br />");

					for(var i in data['players']) {
						player = data['players'][i];
						$('#index-bloc #begin-bloc').append(player.id + ' - Name :' + player.name + ' - HP : ' + player.hp + '<br />');
					}
					$('#index-bloc #begin-bloc').append('<a class="waves-effect waves-light btn" id="launch_game" data-lobby="' + data['lobby'].id + '">Que le meilleur gagne !</a>');
				}
			});
		}),

		// Event button lauch_game
		$("body").on("click", "#launch_game", function() {
			var id = $(this).data('lobby');
			var url = Game.url_game.substring(0,Game.url_game.length-1) + id;

			$.ajax({
				type: "GET",
				url: url,
				dataType: "json",
				success: function(response) {
					Game.data = JSON.parse(response['data']);
					Game.DisplayLogs(1);
				}
			});
		})
		
		// Event button next day
		$("body").on("click", "#log_next_day", function() {
			var day = $(this).data('day');
			Game.DisplayLogs(day);
		})
	}
	
	/**
	 * Format and display logs in HTML
	 */
	Game.DisplayLogs = function(day) {
		
		$('#index-bloc #begin-bloc').html('').append('Jour ' + day).append('<ul>');

		for(var i in Game.data['logs'][day]) {
			log = Game.data['logs'][day][i];
			$('#index-bloc #begin-bloc').append('<li>' + log + '</li>');
		}

		$('#index-bloc #begin-bloc').append('</ul>');
		if (Game.data['logs'].hasOwnProperty((day+1)))
		{
			$('#index-bloc #begin-bloc').append('<a class="waves-effect waves-light btn" id="log_next_day" data-day="' + (day+1) + '">Passer au jour ' + (day+1) + '</a>');
		}
		else
		{
			$('#index-bloc #begin-bloc').append('Fin de la partie');
		}
	}

	Game.SearchPlayer = function()
	{

	}
}