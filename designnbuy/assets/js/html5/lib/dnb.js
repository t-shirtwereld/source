var config_data={};
var mySwiper_gallery='';
var mySwiper_colorSlider='';

var temp_test;
//plus minus button for quantity input
function incDecQuantity(that)
{
	temp_test=that;
	if(that.text=='+')
	{

		that.previousSibling.value=parseInt(that.previousSibling.value)+1;
	}
	else
	{
		//temp_test.previousSibling.value
		(parseInt(that.nextSibling.value)-1) < 0 ? 0 :that.nextSibling.value=parseInt(that.nextSibling.value)-1;	
	}
	livePrice();
}
// show signup div and hide login in popup
$j("#btn_signup").on("click",function(evt) {
    $j(".signup-div").show();
    $j(".login-div").hide();
    $j("#error_msg").html('');
});


$j("#changeside").on("mouseup",function(evt) {
	if(evt.button == 0){	
		disableOther('changeside');
		$j(this).addClass('active');
		$j("#changeside-panel").addClass('cbp-spmenu-open');
		svgCanvas.clearSelection(true);
		getUserImages();
		hideEditPanel(true);
	}
});

$j("#designtemplate").on("mouseup",function(evt) {

	if(evt.button == 0){	
		disableOther('desingidea');
		$j(this).addClass('active');
		//$j("#designideaopt").val(1);
		$j("#designidea-panel").addClass('cbp-spmenu-open');
		svgCanvas.clearSelection(true);
		
		if(!designIdeasloaded){
			//trace("length = " + $j($j("#designideaopt option")[0]).attr("value"));
			//getrelateddesignidea($j($j("#designideaopt option")[0]).attr("value")); 
			//designIdeasloaded=1;
		}
		 mySwiper_design.resizeFix();
		 hideEditPanel(true);
	}
});
var temp_obj='';
$j(".popout-control").on("mouseup",function(evt) {
		//return;
		if(($j(this).attr('class').indexOf('mini-mize'))!=-1)
		{
			$j('.tab-editor').removeClass('hide-container');
			$j(this).addClass('maxi-mize').removeClass('mini-mize');
			$j('#addart-panel .box-outer').show();
			$j('#addtimage-panel .box-outer').show();
			$j('#changeside-panel .sides').show();
			$j('#pickcolor-panel .gallery').show();
			$j('#addtext-panel .tab-contentarea').show();
			$j('#addtext-panel .left-tabs').show();
			$j('#designidea-panel .field-raw').show();
			$j('#addshape-panel .box-outer').show();
			$j('#designidea-panel .box-outer').show();
			//console.log('if');
		}
		else
		{
			$j('.tab-editor').addClass('hide-container');
			$j(this).addClass('mini-mize').removeClass('maxi-mize');;
			$j('#addart-panel .box-outer').hide();
			$j('#addtimage-panel .box-outer').hide();
			$j('#changeside-panel .sides').hide();
			$j('#pickcolor-panel .gallery').hide();
			$j('#addtext-panel .tab-contentarea').hide();
			$j('#addtext-panel .left-tabs').hide();
			$j('#designidea-panel .field-raw').hide();	
			$j('#addshape-panel .box-outer').hide();
			$j('#designidea-panel .box-outer').hide();

				//console.log('else');
		}
});

function disableOther(button) {
	trace('button = ' + button);
	//$j(".product-area").addClass("product-area_portrait");
	//setSharePanel(true);
	$j(".canvas-heading").removeClass("preview_portrait");
	if(button !== 'changeside')	{ $j("#changeside-panel").removeClass('cbp-spmenu-open'); $j("#changeside").removeClass('active');}
	if(button !== 'addproduct')	{ $j("#addproduct-panel").removeClass('cbp-spmenu-open'); $j("#addproduct").removeClass('active');}
	if(button !== 'addart')		{ $j("#addart-panel").removeClass('cbp-spmenu-open'); $j("#addart").removeClass('active');}
	if(button !== 'pickcolor')	{ $j("#pickcolor-panel").removeClass('cbp-spmenu-open');$j("#pickcolor").removeClass('active');}
	if(button !== 'layerPanel')	{ $j("#layer-panel").removeClass('cbp-spmenu-open');}
	if(button !== 'addtocart')	{ $j("#addtocart-panel").removeClass('cbp-spmenu-open');}
	if(button !== 'addtext') 	{ $j("#addtext-panel").removeClass('cbp-spmenu-open'); $j("#addtext").removeClass('active'); $j("#common-panel").removeClass('cbp-spmenu-open');}
	if(button !== 'addtimage') 	{ $j("#addtimage-panel").removeClass('cbp-spmenu-open'); $j("#addtimage").removeClass('active');}
	if(button !== 'addshape') 	{ $j("#addshape-panel").removeClass('cbp-spmenu-open'); $j("#addshape").removeClass('active'); $j("#common-panel").removeClass('cbp-spmenu-open');}
	if(button !== 'desingidea')	{ $j("#designidea-panel").removeClass('cbp-spmenu-open'); $j("#designtemplate").removeClass('active');}
	if(button !== 'qrCode')	{ $j("#qrcode-panel").removeClass('cbp-spmenu-open');$j("#qrCode").removeClass('active');}
	if(button !== 'editpanel')	{ $j("#edit-panel").removeClass('cbp-spmenu-open'); }
	if(button !== 'photobox')	{ $j("#photoBox-panel").removeClass('cbp-spmenu-open');}
	if(button !== 'freehandtool'){ $j("#addshape-panel").removeClass('cbp-spmenu-open'); $j("#freehandtool").removeClass('active');}
	
	$j("#design_tooltip").hide();
	$j("#inst_text_tooltip").hide();
	$j("#inst_image_tooltip").hide();
	$j("#inst_shape_tooltip").hide();
	//$j("#tool_font_family").hide(); // Commented by Rakesh Jain
	//$j("#tool_font_size").hide(); // Commented by Rakesh Jain
	//$j("#text_size_box").hide(); // Commented by Rakesh Jain
	$j("#design_tooltip_edit_panel").hide();
	//disableEditPanel('all');
}
$j("#tool_place_text_close").on("mouseup",function(evt){
	if(evt.button == 0){
		$j("#tool_font_family").hide();
		//$j("#tool_font_size").hide();
		$j("#addtext-panel").removeClass('cbp-spmenu-open');
		$j("#common-panel").removeClass('cbp-spmenu-open');
		$j("#addtext").removeClass('active');
		//$j(".product-area").removeClass("product-area_portrait");
		//setSharePanel();
		$j(".canvas-heading").addClass("preview_portrait");
	}
});

function updateSettingVariables(data)
{
	app.c("checkin///////////////////");
	app.c(data.settings);

    // -- Variables used for Mobi DNB versiob
    callback_generatePNG = data.settings.callback_generatePNG; 
    callback_base64 = data.settings.callback_base64; 

	 // isReady = false;
     formkey ='';
     productid = data.settings.productId; //<?php echo $productId;?>';
    
     savedPrintingMethod = $j.parseJSON(data.settings.savedPrintingMethod);    // rakesh
     isFront = data.settings.isFront;
     user = data.settings.user;
     preTemplate = data.settings.preTemplate;
    

     formKey = data.settings.formKey;//-----Required only in magento AJAX post request Bhavik
     jspath = data.settings.jspath; //'<?php echo $jspath;?>';
     cartUrl = data.settings.cartUrl; //'<?php echo $cartUrl;?>';//-----Declared in config.php Bhavik
     facebookAppId = data.settings.facebook;
     flickrAppId = data.settings.flickr ;
	 instagramAppId = data.settings.instagram;
    // var imageDPI = '';-----Declared in globalVariables.js Bhavik
     basepath = data.settings.basepath; //'<?php echo $webpath; ?>';//-----Declared in config.php Bhavik
     mediapath = data.settings.mediapath;// '<?php echo $media; ?>';//-----Declared in config.php Bhavik
   // console.log(mediapath);
    //var cofigcatid = ''; //-----To be done, if required-----
     confignotfound = data.settings.confignotfound;
     qrCodePath = data.settings.qrCodePath;//'<?php echo $media.'uploadedImage/'; ?>';
     qrCodeLib = data.settings.qrCodeLib; //'<?php echo $webpath.'designtool/phpqrcode/index.php' ?>';
    //var deleteQrCodeUrl = '<?php '';?>';//-----Required to deleteQrCode Bhavik
     relatedSubCategoryUrl = data.settings.relatedSubCategoryUrl;//-----Used in "function getrelatedsubcat" product.js.. Not used in new tool. Was used in Choose Product Bhavik
     relatedProductUrl = data.settings.relatedProductUrl;//Not used anywhere Bhavik
     relatedClipartUrl = data.settings.relatedClipartUrl; //'<?php echo $getrelatedclipart;?>';//-----Declared in config.php Bhavik
     relatedDesignIdeaUrl = data.settings.relatedDesignIdeaUrl; //'<?php echo $relatedDesignIdeaUrl;?>';//-----Declared in config.php Bhavik
     productUrl = data.settings.productUrl; //'<?php echo $productUrl;?>';//-----Declared in config.php Bhavik
     fontUrl = data.settings.fontUrl; //'<?php echo $webpath.'Font.json';?>'; //add a web service link to manage fonts Bhavik
     clipartCategoryUrl = data.settings.clipartCategoryUrl; //'<?php echo $webpath.'clipartCategory.json';?>';//add a web service link to manage cliparts Bhavik
     productPriceUrl = data.settings.productPriceUrl; //'<?php echo $productPriceUrl;?>';//-----Declared in config.php Bhavik
     crossOriginalUploadUrl = data.settings.crossOriginalUploadUrl;//'<?php echo $crossOriginalUploadUrl;?>';//-----Declared in config.php Bhavik
    //var instagramChannelUrl = '<?php //echo $media.'designtool/instagram-channel.html';?>';
     loginUrl =data.settings.loginUrl; //'<?php echo $loginUrl;?>';
     registrationUrl = data.settings.registrationUrl; //'<?php echo $registrationUrl;?>';
     loginCheckUrl = data.settings.loginCheckUrl; //'<?php echo $loginCheckUrl;?>';
     saveBase64Url = data.settings.saveBase64Url;//'<?php echo $saveBase64Url;?>';
     saveDesignUrl = data.settings.saveDesignUrl; //'<?php echo $saveDesignUrl;?>';
     shareDesignUrl = data.settings.shareDesignUrl; //'<?php echo $shareDesignUrl;?>';
     generatePreviewPngUrl = data.settings.generatePreviewPngUrl;//'<?php echo $generatePreviewPngUrl;?>';
     previewPdfUrl = data.settings.previewPdfUrl;// '<?php echo $previewPdfUrl;?>';
     backgroundImageUrl =data.settings.backgroundImageUrl;//'<?php echo $backgroundImageUrl;?>';
     addToCartUrl = data.settings.addToCartUrl; //'<?php echo $addToCartUrl;?>';
     userImagesUrl = data.settings.userImagesUrl; //'<?php echo $userImagesUrl;?>';
     welcomeMessageUrl = data.settings.welcomeMessageUrl; //'<?php echo $welcomeMessageUrl;?>';
     updateTopLinksUrl = data.settings.updateTopLinksUrl; //'<?php echo $updateTopLinksUrl;?>';
     topCartsUrl = data.settings.topCartsUrl; //'<?php echo $topCartsUrl;?>';
     FBredirectURI = data.settings.FBredirectURI; //'<?php echo $media.'designtool/FBredirectURI.html';?>';
     uniqueid = data.settings.uniqueid; //'<?php echo $uniqueid;?>';
     fontlist = data.settings.fontlist;//Not used anywhere Bhavik
    jsonfontList = $j.parseJSON(data.settings.fontlist);//Not used anywhere Bhavik // rakesh
    //var colorlist = '<?php //echo $colorCollection;?>';
    printableColorList = $j.parseJSON(data.settings.colorlist); // rakesh

     no_of_side = data.settings.no_of_side; //ProductDataJson.noofSides //'<?php echo $no_of_side;?>';
   // console.log("no_of_side: " + no_of_side);

     first_font = data.settings.first_font; // i think it will be name of the dont which is first in drop down, it is used by addText function 
     text_svg1=data.settings.text_svg1;  
     text_svg2=data.settings.text_svg2;
     text_svg3=data.settings.text_svg3;
     text_svg4 =data.settings.text_svg4;
     side_1 = data.settings.side_1;
     side_2 = data.settings.side_2;
     side_3 = data.settings.side_3;
     side_4 = data.settings.side_4;
     png_data=data.settings.png_data;
     filearray = new Array();
    
     added_images = data.settings.added_images;
     fonts_used = data.settings.fonts_used;
     configtotalprice = data.settings.configtotalprice; //'<?php echo 'Total Price';?>';
     configselectedsize = data.settings.configselectedsize; //'<?php echo 'Selected Size';?>';
     configqtymessage = data.settings.configqtymessage; //'<?php echo 'Please choose appropriate Quantity.';?>';
     clipartsloaded = data.settings.clipartsloaded;
     designIdeasloaded = data.settings.designIdeasloaded;
     curr_side_id = data.settings.curr_side_id;
     datauri=data.settings.datauri;
     productImageCan=data.settings.productImageCan;
     customizationCan=data.settings.customizationCan;
     priceInterval=data.settings.priceInterval;
     action=data.settings.action;
     shareType=data.settings.shareType;
     sideNameAry = data.settings.sideNameAry;
     toolType = data.settings.toolType; //possible values: "producttool", "web2print" 
     pickerMode = data.settings.pickerMode; //possible values: "full", "printable"
     printingMode = data.settings.printingMode; //possible values: "DTG", "Vinyl/Screen" 
     mobi_image_upload_url =  data.settings.mobi_image_upload_url;
    if(pickerMode=="printable"){
        firstColor = printableColorList[0].colorCode;       
        borderColor = firstColor.substring(1);      
    }else{
        borderColor = "000";
    }
}


function updateProductData(data){
	showLoader();
	productData = data.productdata;
	console.log('yash');
	console.log(data.productdata);
	printingMethods = productData.printingMethods;			
	jsonMaskImageUrls = productData.maskImages;
	overlayImageUrls = productData.overlayImages;
	configsku = productData.code;
	is_multicolor = productData.multiColor;
	if(isReady){
		createProductDesign();
	}	
}

ProductImageModule.createSidePanelIcon = function() {	
	var temp_height=$('#changeside-panel').height()-70;
			var temp_width=($('#changeside-panel').width()-80)/no_of_side;
			// no_of_side = productData.noofSides;	
			productImages = productData.productImages;		
			var text_area = "";	
			for(j= 0; j< no_of_side; j++){
				productImage = productImages[j];
				var i = j+1;
				if(productData.multiColor == 'yes'){
					if(productData.type == 'configurable'){
						productImage = productData.allColors.color[productData.postData.colorOptionId].image[i]
					}
                    console.log("productImage::"productImage);
					text_area += '<li><button id="sideButton_'+i+'" onmousedown="showSide(this)" title="'+sideNameAry[i-1]+'"><svg  height="'+temp_height+'" width="'+temp_width+'" id="svg-doc" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><filter id="colorMatrixIcon'+i+'"><feColorMatrix values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0" type="matrix" id="feColorMatrixIcon'+i+'"/></filter></defs><image filter="url(#colorMatrixIcon'+i+')" xlink:href="'+productImage+'" height="'+temp_height+'" width="'+temp_width+'"/></svg></button><label id="sideCaption'+sideNameAry[i-1]+'">'+sideNameAry[i-1]+'</label></li>';
				}else{
                    console.log("productImage::"productImage);
					text_area += '<li><button id="sideButton_'+i+'" onmousedown="showSide(this)" title="'+sideNameAry[i-1]+'"><svg  height="'+temp_height+'" width="'+temp_width+'" id="svg-doc" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><filter id="colorMatrixIcon'+i+'"><feColorMatrix values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0" type="matrix" id="feColorMatrixIcon'+i+'"/></filter></defs><image filter="url(#colorMatrixIcon'+i+')" xlink:href="'+productImage+'" height="'+temp_height+'" width="'+temp_width+'"/></svg></button><label id="sideCaption'+sideNameAry[i-1]+'">'+sideNameAry[i-1]+'</label></li>';
				}
				
				
				var svgElement = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
				svgElement.width = 400;
				svgElement.height = 400;
				// svgElement.appendChild(rectElement);
				init_data = '';
			}
			$j('#productSides').append(text_area);
		}

function updateFontData(data)
{
	fontData = data.fontlist;				
	Smm.init('tool_font_family');
	var dd = new DropDown( $j('#dd') );
}

function updateUserImages()
{			
	var html_string='';
	userImages = config_data.userimages;
	//trace(userImages);	
	html_string='<div class="swiper-container" id="gallery_container"><div class="swiper-wrapper">';
			
	$j.each(userImages,function(index,value){
		//trace(value.id);
		if(value.vectorname != '' && value.vectorname !== undefined){
			uploadCheckClass="uploadCheckTrue";
		}else{
			uploadCheckClass="uploadCheckFalse";
		}
		html_string += '<div class="swiper-slide" ><li>'+
		'<a rel="'+value.imageUrl+'" onclick="loadImageONCanvas(this.rel); return false;" class="imageclass" href="javascript:void(0);">'+
		'<img onload="lazyLoaderImg(this)" src="'+value.imageUrl+'" width="70" height="70" title="" class="userImage" />'+
		'</a>'+							
		'</li></div>';
			
		//$j(userImage).appendTo($j('#image_gallery_ul', window.parent.document));
		//initHDUploaders(value.id);
	});	
	$j('#image_gallery_ul').append(html_string+'</div></div>');
	//$j('#image_gallery_ul').html(html_string+'</div></div>').trigger('create');

	mySwiper_gallery = new Swiper('#gallery_container',{
		freeMode: true,
		slidesPerView: 'auto'
	});
}
// --- Overwrite product-src loaddata function
function loadData(){

}
function fontCssLoad(data)
{
	var root_path=data.settings.mediapath+'font/';
	$j.each(data.fontlist,function(index,value){
		var filename=root_path+value.cssFile;
		//console.log(filename);
		var fileref=document.createElement("link")
   		fileref.setAttribute("rel", "stylesheet")
  		fileref.setAttribute("type", "text/css")
   		fileref.setAttribute("href", filename)
   		document.getElementById('designtool').appendChild(fileref);
	});
}
function craeteTextAreaToStoreSVG(data)
{
	var NoOfSides=data.productdata.noofSides;
	var init_data = Array("","","","","","");
	var i=1;
	$j.each(data.settings.init_data,function(index,value){
		init_data[i] = value;
		i++;
	});

	var string_html='';
	for (var i = 1; i <= NoOfSides; i++) {
		string_html+='<textarea style="display:none;" id="textsvg_'+i+'" name="textsvg_'+i+'">'+init_data[i]+'</textarea> \n';
	}
	$j('#pages_data').html(string_html);
}


function setProductDesignAreaValues(data){
	var ProductSidesArray=data.productdata.Area;
	var i=1;
	var valueArray="";
	$j.each(ProductSidesArray,function(index,value)
	{

	   valueArray = value.split(",");
       pos_x[i] = parseFloat(valueArray[0])*15;
       pos_y[i] = parseFloat(valueArray[1])*15;
       design_area_width[i] =parseFloat(valueArray[2])*15;
       design_area_height[i] =parseFloat(valueArray[3])*15;
       outputWidth[i] =valueArray[4];
       outputHeight[i] =valueArray[5];
	   if(i==1){
	   		if ($j("#textsvg_1").val()!="")	svgCanvas.setSvgString($j("#textsvg_1").val());   		
	   		else 	svgCanvas.setSvgString('<svg width="' + design_area_width[i] + '" height="' + design_area_height[i] + '" x="' + pos_x[i] + '" y="' + pos_y[i] + '" xmlns="http://www.w3.org/2000/svg"></svg>');
	   }    
       i++;
    });
}

function setSVGCanvasImages(data){
	var canvasImageArray=data.settings.svgcanvas_images;
	$j.each(canvasImageArray,function(index,value){
		var imagewidth;
		var imageheight;
		var displayStyle;
		var filter;
		var tempNoSide;

		var productImage = new Image();
		productImage.src = value.imageurl;
		imagewidth = value.imagewidth+"px";
		imageheight = value.imageheight+"px";
		
		if(index != 1) {
			displayStyle = "display:none;"; 			
		}			
		if(data.productdata.multiColor == 'yes'){
			filter = '';
		}else{
			filter = 'url(#colorMat)';
		}
		newImage = document.createElementNS("http://www.w3.org/2000/svg", 'image');
		svgedit.utilities.assignAttributes(newImage,{					
				"width": imagewidth,
				"height": imageheight,
				"id": "img_"+index,
				"xlink:href": productImage.src,
				"style": "pointer-events:none;"+displayStyle,
				"class": "main_image",
				"filter": filter
		});	

		$j("#productSvg").append($(newImage));					
		updateMaskImage();
		if(productOverlayImageExt.updateOverlayImage){
			productOverlayImageExt.updateOverlayImage();
		}
		
	});
}

Smm.init = function(divId){		
		Smm.fonts = fontData;
		var flag = false;		
		$j('#dd').css('position','relative').append('<ul tabindex="1" class="dropdown select-small" id="font-selector">');
		var selector = $j('#'+Smm.selectorId);
		$j.each(Smm.fonts, function(index,value){
			if(flag == false){
				first_font = index;
				flag = true;
			}	
			
			// $j("head").append("<link>");
			// var css = $j("head").children(":last");
			// css.attr({
			//   rel:  "stylesheet",
			//   type: "text/css",
			//   href: Smm.fontDirectory+value.cssFile
			// });			
			
			selector.append('<li font-name="' + index + '"><a class="font-item" font-name="' + index + '" style="font-family:' + index + '">' + index + '</a></li>');       	  
    });
    
    $j("#close-selector").click(Smm.hideSelector);
    $j(".font-item").click(Smm.selectFont);
	/*$j('#font-selector').change(function(){
		var font = $j(this).val();
		var active = function(){
		svgCanvas.setFontFamily(font);
		};
		$j('#font_family').val(font);
		var loading = function(){};
		Smm.loadFont(font, active, loading);
	});*/
	
    $j('#font_family_dropdown button').unbind('mousedown').bind('mousedown',function(event){
        if (Smm.visible === false) {
            Smm.showSelector();
        } else {
            //Smm.hideSelector();
        }
    });
    $j(window).mouseup(function(evt) {
        if(!Smm.visible === true) {
            //Smm.hideSelector();
        }
        Smm.visible = false;
    });
    $j('#'+Smm.selectorId).mouseup(function(){
        Smm.showSelector();
    });
};
function getCategoryClipart(id,category)
{
	var clipart_obj=new Object();
	clipart_obj.cliparts_array=new Array();
	$j('#breadcrumb_clipart').find('a').not(':first').remove();
	$j('#breadcrumb_clipart').append('<a>'+category+'</a>');
	$j.each(config_data.cliparts,function(index,values)
	{
		//console.log(values.id);
		if(values.id == id)
		{
			clipart_obj.cliparts_array=values.cliparts;
			return false;
		}
	});
	$('#clipartopt').render_view('#h_clipartlist',clipart_obj, {refresh:"true"});
				
}
function getCategoryDesignIdea(id,category)
{
	var desinidea_obj=new Object();
	desinidea_obj.designarray=new Array();
	$j('#breadcrumb_designidea').find('a').not(':first').remove();
	$j('#breadcrumb_designidea').append('<a>'+category+'</a>');
	$j.each(config_data.designideas,function(index,values)
	{
		console.log(values.id);
		if(values.id == id)
		{
			desinidea_obj.designarray=values.designideas;
			return false;
		}
	});
	$('#designideadiv').render_view('#h_desinidealist',desinidea_obj, {refresh:"true"});
				
}
// $j("#btnAddText").on("click", function(evt) 
// {
// 			if(evt.button == 0){
// 				if(!$j("#text").val()){
// 				app.t(svgEditor.uiStrings.notification.ADDTEXT_ALERT);
// 			return;
// 		}
// $('#btnAddTextDiv').hide();
// 				}
// });
$j("#addtext").on("click", function(evt) {
				$j('#text_editor_title').text('TEXT EDITER');				
				trace("svgCanvas.getSelectedElems()");
				trace(svgCanvas.getSelectedElems());
				$j("#text_bottom").val("");
				disableOther('addtext');
				$j(this).addClass('active');
				$j("#addtext-panel").addClass('cbp-spmenu-open');
				$j('#text_panel_bottom').show();
				$j('#btnAddTextDiv').show();
				//if(svgCanvas.getSelectedElems().length){
				svgCanvas.clearSelection(true);
				hideEditPanel(true);
				//}
});

$j("#freehandtool").on("mouseup",function(evt) {
	if(evt.button == 0){	
		svgCanvas.setMode('fhpath');
		disableOther('freehandtool');

		$j(this).addClass('active');
		//$j("#addshape-panel").addClass('cbp-spmenu-open');
		svgCanvas.clearSelection(true);
		hideEditPanel(true);
	}
});

//$j("#mydesign_button").on("mouseup",function(evt) {
$j("#mydesign_button").on("click",function(evt) {
	save_share("mydesign");
	//(db.user.user_id=='0')?app.redirectFunction('../../'+app.config.themePath+'login.html'):app.redirectFunction('../../'+app.config.themePath+'mydesigns.html');	
});

function on_change_align_selection(that)
{
	switch(that.value) {
    case '0':
       setAlign("start");
        break;
    case '1':
       setAlign("middle");
        break;
    case '2':
       setAlign("end");
        break;
    default:
        app.t("Please select valid value");
}
}
function getDeviceDimentions()
{
	var params={};
	params.height= $(window).height();
	params.width=$(window).width();
	return params;
}

function getZoomPercentage(){
	// -- reset zoom percentage for Mobi DNB. 
	var dimentions=getDeviceDimentions();
	var zoompercentage='';
	//0.25
	((dimentions.width*0.22)<(dimentions.height*(100/485)))?zoompercentage=dimentions.width*0.22+'%':zoompercentage=dimentions.height*(100/485)+'%';
	return zoompercentage;
	// - end
}

function stroke_colorPicker(elem){
	$j.farbtastic('#colorpicker').linkTo(function(){});
	trace(elem.attr('id'));		
	//$j.farbtastic('#colorpicker').setColor();
	var title = 'Pick a Fill Paint and Opacity';
	var pos = elem.offset();
	var paint = svgEditor.paintBox['stroke'].paint;
	trace(paint.solidColor);
	if(paint.solidColor.search('#') == -1)
		$j.farbtastic('#colorpicker').setColor('#'+ paint.solidColor);
	else
		$j.farb

	//$j.farbtastic('#colorpicker').setColor('#'+ paint.solidColor);
	$j("#colorpicker").addClass('displayblock');
	$j.farbtastic('#colorpicker').linkTo(function(color){
			color = color.replace('#','');
            paint.solidColor = color;
            svgEditor.paintBox['stroke'].setPaint(paint);            
            svgEditor.paintBox['stroke'].update();            
            $j(svgCanvas.getSelectedElems()[0]).attr('stroke', '#'+color);
            //uniqueColors[pickerIndex] = "#" + paint[paint.type];
            //$j('#color_picker').hide();
            svgCanvas.runExtensions("elementChanged", {
                elems: svgCanvas.getSelectedElems()
            });
        	//trace("color picker 111111 .............." + color);
	   });
	return 
}

//-----------top panel code ------------------
//according to button click in top side bar hide show html elements in panel
function showHidePanleElements(elementName)
{		
	if(svgCanvas.getSelectedElems().length>1){
		disableEditPanel('all');
		hideEditPanel(true);
		return;
	}
	switch(elementName)
	{
		case 'text':
			hideEditPanel(false);
			$j('#btnAddText').hide();
			
			//disableOther_top('add-text-icon-top');
			//$j("#add-text-icon-top").addClass('active');
			//$j("#add-text-panel-top").addClass('panel-open');
			//$j("#add-text-panel-top").show();
			//$j('#btnAddTextDiv').hide();
		break;
		case 'g':
		case 'use':
			hideEditPanel(false);
			$j('#add-text-icon-top,#format-icon-top,#curve-slider-icon-top').hide();
			//$j("#text").val("");
			//$j("#pick-color-panel-top").addClass('panel-open');
			//j("#pick-color-panel-top").show();
			break;
		case 'image':
			disableEditPanel('all');
			hideEditPanel(true);
			$j('#add-text-icon-top,#format-icon-top,#pick-color-icon-top,#curve-slider-icon-top,#border-icon-top').hide();
			$j('#size-slider-icon-top,#rotation-slider-icon-top,#move-icon-top').addClass('active').show();
			break;
		case 'path':
			hideEditPanel(false);
			$j('#add-text-icon-top,#format-icon-top,#curve-slider-icon-top').hide();
			$j('#tool_fill .singleColor').show();
			break;
		case 'line':
		case 'rect':
		case 'ellipse':
		case 'circle': // user for pancel as well
			hideEditPanel(false);
			$j('#add-text-icon-top,#format-icon-top,#pick-color-icon-top,#curve-slider-icon-top').hide();
			break;
		case 'layerPanel':
			disableEditPanel('all');
			hideEditPanel(true);
			break;
		default:	
	}
}
function hideEditPanel(hideShowFlage)
{
	if(hideShowFlage)
	{
		$j('#right_editor_panel').find('div').find('a').hide();
		disableEditPanel('all');
		$j("#colorpicker").removeClass('displayblock');
	}
	else
	{
		$j('#right_editor_panel').find('div').find('a').show();
	}
}

function disableEditPanel(button)
{
	if(button !== 'add-text-icon-top')	{ $j("#add-text-panel-top").removeClass('panel-open'); $j("#add-text-icon-top").removeClass('active');}
	if(button !== 'size-slider-icon-top')		{ $j("#size-slider-panel-top").removeClass('panel-open'); $j("#size-slider-icon-top").removeClass('active');}
	if(button !== 'curve-slider-icon-top')		{ $j("#curve-slider-panel-top").removeClass('panel-open'); $j("#curve-slider-icon-top").removeClass('active');}
	if(button !== 'rotation-slider-icon-top')	{ $j("#rotation-slider-panel-top").removeClass('panel-open'); $j("#rotation-slider-icon-top").removeClass('active');}
	if(button !== 'move-icon-top')		{ $j("#move-panel-top").removeClass('panel-open'); $j("#move-icon-top").removeClass('active');}
	if(button !== 'pick-color-icon-top')	{ $j("#pick-color-panel-top").removeClass('panel-open');$j("#pick-color-icon-top").removeClass('active');$j("#colorpicker").removeClass('displayblock');}
	if(button !== 'format-icon-top') 	{ $j("#format-panel-top").removeClass('panel-open'); $j("#format-icon-top").removeClass('active'); }
	if(button !== 'border-icon-top')	{ $j("#border-panel-top").removeClass('panel-open'); $j("#border-icon-top").removeClass('active');}
}
//-------------add text button click--------------
$j("#add-text-icon-top").on("mouseup",function(evt) {
	if(evt.button == 0){	
		disableEditPanel('add-text-icon-top');
		$j(this).addClass('active');
		$j("#add-text-panel-top").addClass('panel-open');
		//$j("#add-text-panel-top").show();


		$j("#text_panel").show();
		$j('#btnAddTextDiv').hide();
	}
});
//-------------end add text button click--------------

//-------------color picker button click--------------
$j("#pick-color-icon-top").on("mouseup",function(evt) {
	if(evt.button == 0){	
		disableEditPanel('pick-color-icon-top');
		$j(this).addClass('active');
		$j("#pick-color-panel-top").addClass('panel-open');
		//$j("#pick-color-panel-top").show();
	}
});
//-------------end color picker button click--------------


//-------------formatting button click--------------
$j("#format-icon-top").on("mouseup",function(evt) {
	if(evt.button == 0){	
		disableEditPanel('format-icon-top');
		$j(this).addClass('active');
		$j("#format-panel-top").addClass('panel-open');
		$j("#text_size_box").show();
	}
});
//-------------end formatting button click--------------


//-------------size slider button click--------------
$j("#size-slider-icon-top").on("mouseup",function(evt) {
	if(evt.button == 0){	
		disableEditPanel('size-slider-icon-top');
		$j(this).addClass('active');
		$j("#size-slider-panel-top").addClass('panel-open');
	}
});
//-------------end size slider button click--------------


//-------------boder button click--------------
$j("#border-icon-top").on("mouseup",function(evt) {
	if(evt.button == 0){	
		disableEditPanel('border-icon-top');
		$j(this).addClass('active');
		$j("#border-panel-top").addClass('panel-open');
	}
});
//-------------end border button click--------------


//-------------rotation slider button click--------------
$j("#rotation-slider-icon-top").on("mouseup",function(evt) {
	if(evt.button == 0){	
		disableEditPanel('rotation-slider-icon-top');
		$j(this).addClass('active');
		$j("#rotation-slider-panel-top").addClass('panel-open');
		$j("#rotationSlider").show();
	}
});
//-------------end rotation slider button click--------------


//-------------curve slider button click--------------
$j("#curve-slider-icon-top").on("mouseup",function(evt) {
	if(evt.button == 0){	
		disableEditPanel('curve-slider-icon-top');
		$j(this).addClass('active');
		$j("#curve-slider-panel-top").addClass('panel-open');
	}
});
//-------------end curve slider button click--------------


//-------------move button click--------------
$j("#move-icon-top").on("mouseup",function(evt) {
	if(evt.button == 0){	
		disableEditPanel('move-icon-top');
		$j(this).addClass('active');
		$j("#move-panel-top").addClass('panel-open');
		$j("#rotationSlider").show();
	}
});
//-------------end move button click--------------

//left pnel hide show left-panel-show-button
$j(".left-panel-show-button").on("click",function() {
	
		($j(".button-area").hasClass('active'))?$j('.button-area').hide().removeClass('active'):$j('.button-area').show().addClass('active');
});

var base64_Callback_fun = function (html){
							//filenameobj = $j.parseJSON(html);
							filenameobj = html;
							trace("filenameobj")
							trace(filenameobj);
							var m = 0;
							for(x in filenameobj){
								filearray[m] = filenameobj[x];
								m++;
							}
							hideLoader();
							afteraddtocart();
						}

var savedesign_callback_fun = function () {
							//document.getElementById("btn_save_design").disabled = false;
							$j('#design_name').val('');
							//$j("#svg_save_design_window").hide();
							// app.t('Design saved');
							app.t(svgEditor.uiStrings.notification.designSaved);
							hideLoader();
						}

function jsonCallWithIframe(postUrl,queryStatusSuccessFunc,queryStatusUrl,queryStatusData,imageType){
	var form = $j(".mobi-dnb-iframe-form");
	var iframe_div= $j("#mobi-dnb-iframe");
	//var queryStatusUrl = LicenseBaseURL + "mobidesigntool/config/getPngUrl";
	//var queryStatusSuccessFunc = callbackFun;
	//var queryStatusData ="current_time="+time+"&side="+side+"&imagetype="+imageType;

    
    var tmpDiv = $j('<div style="display: none;"></div>');
   	iframe_div.append(tmpDiv);
    var clonedForm = cloneForm(form);
    var iframe = createIFrameWithContent(tmpDiv, clonedForm);
    if (postUrl)
        clonedForm.attr('action', postUrl);
    var postToken = 'JSONPPOST_' + (new Date).getTime();
    clonedForm.attr('id', postToken);
    clonedForm.append('<input name="JSONPPOSTToken" value="'+postToken+'">');
    clonedForm.attr('id', postToken );
    clonedForm.submit();

    var timerId;
    var watchIFrameRedirectHelper = function()
    {
        if (watchIFrameRedirect(iframe, postToken ))
        {
            clearInterval(timerId);
            tmpDiv.remove();
	            $j.ajax({
	                url:  queryStatusUrl,
	                data: queryStatusData,
	                dataType: "jsonp",
	                type: "GET",
	                success: function (image) {
	                	if(imageType == 'design_image') {
							designImage = image;
						}
	                	queryStatusSuccessFunc(image);
	                },
	                error:function (html) {alert('error in saving'); hideLoader();}
	            });
        }
    }

    if (queryStatusUrl && queryStatusSuccessFunc){
        timerId = setInterval(watchIFrameRedirectHelper, 200);   
    } else if(typeof(queryStatusSuccessFunc)=== "function"){
	    queryStatusSuccessFunc();
    }


}


function watchIFrameRedirect(iframe, formId)
{
	//trace(iframe);
    try
    {
        if (iframe.contents().find('form[id="' + formId + '"]').length)
            return false;
        else
            return true;
    }
    catch (err)
    {
        return true;
    }
    return false;
}

//This one clones only form, without other HTML markup
function cloneForm(form)
{
    var clonedForm = $('<form method="post"></form>');
    //Copy form attributes
    $.each(form.get()[0].attributes, function(i, attr)
    {
        clonedForm.attr(attr.name, attr.value);
    });
    form.find('input, select, textarea').each(function()
    {
        clonedForm.append($(this).clone());
    });

    return clonedForm;
}


	function sizeQuantityChange(obj){
		//alert(obj.value); return;
		if(obj.value!=0 || obj.value!=""){
		}else{
			obj.value=0;
		}

			clearTimeout(priceInterval);
			if(productPricingExt.getProductPrice){
				priceInterval = setTimeout(productPricingExt.getProductPrice(), 1000);
			}

	}


function createIFrameWithContent(parent, content)
{
    var iframe = $j('<iframe></iframe>');
    parent.append(iframe);

    if (!iframe.contents().find('body').length)
    {
        //For certain IE versions that do not create document content...
        var doc = iframe.contents().get()[0];
        doc.open();
        doc.close();
    }

    iframe.contents().find('body').append(content);
    return iframe;
}