Player = {};

Player.Launch = function(params) {

	Player.url_new_player = params.url_new_player;
	Player.url_battle = params.url_battle;

	Player.Event = function() {

		$("form[name='appbundle_player']").submit(function(){
			$.ajax({
				type:"POST", 
				data: $(this).serialize(), 
				url: Player.url_new_player, 
				success: function(data){
					data = JSON.parse(data['data']);

					if(data.response == 'success')
					{
						document.location.href = Player.url_battle;
					}
				},
				error: function(){
					console.log('erruer');
				}
			});
			return false;
		});
	}
}