(function (db, window) {
	//'use strict';
	db.designtool = db.designtool || {};
	db.designtool.urlLoadData = "http://192.168.0.139/work/print_commerce/pc-newtool/source/print-commerce/pcstudio_config/getAllInitialData";
	console.log('hihihih');
	//console.log(db);
	
	db.designtool.syncFonts = function(data){
		db.sync(db.designtool.urlFontList, data, {success:db.designtool.saveFontList});
	}

	function saveFontList(data){
		// save it into variable
	}
	

	// ----- Load All data in one call 
	db.designtool.loadAllData = function(data){
		console.log('data');
		showLoader(true);
		//app.c("Tool loadInitialData called");
		data = db.convertJsonString(data);
		data += '&' + app.designtool.querystring;
		db.sync(db.designtool.urlLoadData, data, {success:saveAllData});
	}

	// --- Save all Data into variables 
	function saveAllData(data){
		$j.alert=function(textString){
			app.t(textString)
		}
		// save it into variable
		if(data.status.toLowerCase()=='success'){
			config_data=data.data;
			updateSettingVariables(data.data); /// --- Create these fuction in dnb.js
			craeteTextAreaToStoreSVG(data.data); 
			setSVGCanvasImages(data.data);
			setProductDesignAreaValues(data.data);
			app.designtool.loadClipArt();
			app.designtool.loadDesignIdea();
			updateProductData(data.data);
			fontCssLoad(data.data);
			updateFontData(data.data);			 
			updateUserImages();

			// updateDesignIdeas(data);
			
			//app.c(data);
			init(); // - Init svgEditor on ajax call end 
          	zoomChange(getZoomPercentage()); // Adjust the Zoom of canvas as per device
			hideLoader();

		}
		else{
			app.t('data sync error');
		}
	}
} (db= window.db || {}, window));