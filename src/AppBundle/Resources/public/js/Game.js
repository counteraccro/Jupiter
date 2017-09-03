Game = {};

Game.Launch = function(params)
{
	Game.url_generate_player = params.url_generate_player;

	Game.Event = function()
	{
		$('#new-game').click(function() {

			var html = 'Génération des joueurs <br /><div class="progress"><div class="indeterminate"></div></div>';
			$('#index-bloc #begin-bloc').html(html);

			$.ajax({
				type: "GET",
				url: Game.url_generate_player,
				dataType: "json",
				success: function(response) {

					players = JSON.parse(response['data']);
					
					$('#index-bloc #begin-bloc').html("Affichage des joueurs <br />");
					
					for(var i in players) {
						player = players[i];
						$('#index-bloc #begin-bloc').append(player.id + ' - Name :' + player.name + ' - HP : ' + player.hp + '<br />');
					}
				}
			});
		})
	}

	Game.SearchPlayer = function()
	{

	}
}