(function (app, window) {
	
	app.designtool.mydesigns = app.designtool.mydesigns || {};
	app.designtool.mydesigns.data = {};

	app.designtool.mydesigns.initialize = function(){
		$(document).one("pagebeforeshow", "#mydesigns", function(event, data){
			var data_obj = {};
			$(this).render_view('#h_mydesigns_list', data_obj, {refresh:"true",success:db.designtool.mydesigns.syncList()});
			app.showPageLoader();
		});
	}

	app.designtool.mydesigns.loadpage = function(){
		var data_obj = {};
		data_obj.mydesigns = db.designtool.mydesigns.getData();
		data_obj.cart_bubble = db.cart.get_cart_items_count();
		data_obj.cart_errors = db.cart.get_cart_items_errors_count();
		data_obj.three_level_categories = db.category.get_three_level(db.category.getData({"parent_id":app.settings.root_category_id}));
		$(app.functions.get_active_page_object()).render_view('#h_mydesigns_list', data_obj, {refresh:"true"});
		app.hidePageLoader();
	}

	app.designtool.mydesigns.removeMyDesign = function(item_id){
		if(app.checkPageLoaderStatus()){
			data_to_send = {};
			data_to_send['id'] = item_id;
			app.c(data_to_send);
			app.showPageLoader();
			db.designtool.mydesigns.removeItem(data_to_send);
		}
	};

	app.designtool.mydesigns.removeItemSuccess = function(data){
		if(data.status.toLowerCase() == 'success')
			app.t("Item_removed_successfully");

		app.hidePageLoader();
		db.designtool.mydesigns.saveDataList(data);
	}

} (app = window.app || {}, window));