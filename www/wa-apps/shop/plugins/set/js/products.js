(function($) {
	$.products.actionsetsAction = function (params) {
		this.load('?plugin=set&module=setList', function () {
			$("#s-sidebar li.selected").removeClass('selected');
			$("#s-action-sets").addClass('selected');
			document.title = 'Акционные комплекты';
		});
	}
})(jQuery);