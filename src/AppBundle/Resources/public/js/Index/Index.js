Index = {};

Index.Launch = function(params) {
	
	Index.url_menu = params.url_menu;
	Index.url_new_player = params.url_new_player;
	
	Index.EventAjaxGameMenu = function()
	{
		$('#btn-fight').click(function() {
			HtmlRender.Preloader('#global-bloc #btn-bloc');
			Index.Ajax(Index.url_new_player)
		})
	}
	
	Index.LoadGameMenu = function()
	{
		Index.Ajax(Index.url_menu)
	}
	
	Index.Ajax = function(url)
	{
		$.ajax({
			type: "GET",
			url: url,
			success: function(response) {
				$('#global-bloc').html(response);
			}
		});
	}
}