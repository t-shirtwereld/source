(function (app, window) {
	
	app.designtool = app.designtool || {};
	app.designtool.data = {};
	app.designtool.querystring = '';

	app.designtool.initialize = function(){
		$(document).one("pagebeforeshow", "#designtool", function(event, data){
			var data_obj = {};
			app.designtool.querystring = $(this).data("url").split("?")[1];
			data_obj.key = app.functions.getParameterByName('designtype');
			data_obj.id = app.functions.getParameterByName('product_id');
			data_obj.time = Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
			$(this).render_view('#h_designtool', data_obj, {refresh:"true"});
			app.hidePageLoader();
		});

		$(document).one("pagehide", "#designtool", function(event, data){
			//document.removeEventListener("touchstart", touchHandler, true);
			document.removeEventListener("touchstart", touchHandler);
			document.removeEventListener("touchmove", touchHandler, true);
			document.removeEventListener("touchend", touchHandler, true);
			document.removeEventListener("touchcancel", touchHandler, true);
		});
	}

	app.designtool.loadpage = function(){
		var data_obj = {};
		data_obj.cart_bubble = db.cart.get_cart_items_count();
		data_obj.cart_errors = db.cart.get_cart_items_errors_count();
		data_obj.three_level_categories = db.category.get_three_level(db.category.getData({"parent_id":app.settings.root_category_id}));
		data_obj.cart_items = db.cart.getData();
		$(app.functions.get_active_page_object()).render_view('#h_cart', data_obj, {refresh:"true"});
		app.hidePageLoader();
	}

	app.designtool.loadClipArt = function(){
		var data_obj={};
		data_obj=config_data;
		$('#clipartopt').render_view('#h_clipartlist', data_obj, {refresh:"true"});
		$('#breadcrumb_clipart').find('a').not(':first').remove();
	}
	app.designtool.loadDesignIdea = function(){
		var data_obj={};
		data_obj=config_data;
		$('#designideadiv').render_view('#h_desinidealist', data_obj, {refresh:"true"});
		$('#breadcrumb_designidea').find('a').not(':first').remove();
	}

} (app = window.app || {}, window));