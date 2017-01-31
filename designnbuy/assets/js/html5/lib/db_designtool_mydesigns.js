(function (db, window) {
	'use strict';
	db.designtool = db.designtool || {};
	db.designtool.mydesigns = db.designtool.mydesigns || {};

	db.designtool.mydesigns.data = TAFFY(); // hold json data
	db.designtool.mydesigns.urlList = "mobidesigntool/customer/saveddesigns";
	db.designtool.mydesigns.urlRemoveItem = "mobidesigntool/customer/designdelete";

	db.designtool.mydesigns.syncList = function(data){
		data = {};
		db.sync(db.designtool.mydesigns.urlList, data, {success:db.designtool.mydesigns.saveDataList});
	}

	function saveDataList(data){
		if(data.status.toLowerCase() == 'success'){
			db.designtool.mydesigns.data = TAFFY(data.data);
		}
		else{
			app.t(data.message);
		}
	}

	db.designtool.mydesigns.saveDataList = function(data){
		saveDataList(data);
		app.designtool.mydesigns.loadpage();
	}

	db.designtool.mydesigns.getData = function(arg){
		return db.designtool.mydesigns.data().get();
	}

	db.designtool.mydesigns.removeItem = function(data_to_send){
		db.sync(db.designtool.mydesigns.urlRemoveItem, data_to_send, {success:app.designtool.mydesigns.removeItemSuccess});
	};
 
} (db= window.db || {}, window));