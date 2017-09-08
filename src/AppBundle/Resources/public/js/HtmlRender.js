HtmlRender = {}

HtmlRender.Launch = function() {
	
	HtmlRender.Preloader = function(id, label = 'Chargement...') {
		
		var html = 
		'<div class="center-align">' +
			'<div class="preloader-wrapper small active">' +
				'<div class="spinner-layer spinner-red-only">' +
					'<div class="circle-clipper left">' +
						'<div class="circle"></div>' +
					'</div>' +
					'<div class="gap-patch">' +
						'<div class="circle"></div>' +
					'</div>' +
					'<div class="circle-clipper right">' +
						'<div class="circle"></div>' +
					'</div>' +
				'</div>' +
			'</div>' +
			'<div>' + label + '</div>' +
		'</div>';
		
		$(id).html(html);
	}
}