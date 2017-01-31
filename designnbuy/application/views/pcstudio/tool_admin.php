<?php
//$jspath = base_url('assets/js/').'/html5/';
$jspath = get_base_url() . 'designnbuy/assets/js/html5/';
$mediapath = get_base_url() . 'designnbuy/assets/images/';
//echo  $jspath ; exit;
$locale = 'en_US';
//$uniqueid = '123456789';
$uniqueid = time();
?>
<script type="text/javascript" src="<?php echo $jspath . 'jquery.js'; ?>"></script>
<script>

    function trace(data)
    {
	console.log(data);
    }
	var langData = '<?php echo $languagedata; ?>';
    var mediapath = '<?php echo $mediapath; ?>';
    var jspath = '<?php echo $jspath; ?>';
    var formkey = '';
    var savedPrintingMethod = "";
    var isFront = '1';
    var user = '';
    var preTemplate = '';
    var pretemplate_id = '';
    var savePreTemplate='';
    var exitUrl='';
	
    var currentStore = '';
    var cartUrl = '';
    var facebookAppId = '';
    var flickrAppId = '';
    var instagramAppId = '';
    var imageDPI = '';	
    var basepath = '<?php echo get_base_url(); ?>';
    var cofigcatid = '';
    var confignotfound = '';
    var qrCodePath = '';
    var qrCodeLib = '';
    var deleteQrCodeUrl = '';
    var relatedSubCategoryUrl = '';
    var categoryUrl = '';
    var relatedProductUrl = '';	
    var designIdeaCategoryUrl = '';
    var relatedDesignIdeaUrl = '';
    var productUrl = '';
    var fontUrl = '';
    var clipartCategoryUrl = '';
    var clipartSubCategoryUrl = '';
    var relatedClipartUrl = '';
	var relatedDesignartUrl = '';
	
    var productPriceUrl = '';
    var crossOriginalUploadUrl = '';
    var instagramChannelUrl = '';
    var loginUrl = '';
    var registrationUrl = '';
    var loginCheckUrl = '';
    var saveBase64Url = '';
    var saveDesignUrl = '';
    var shareDesignUrl = '';
    var generatePreviewPngUrl = '';
    var previewPdfUrl = '';
    var backgroundImageUrl = '';
    var addToCartUrl = '';
    var cartUrl = '';
    var userImagesUrl = '';
    var welcomeMessageUrl = '';
    var updateTopLinksUrl = '';
    var topCartsUrl = '';
    var FBredirectURI = '';
    var removeuserImagesUrl = '';
    var uniqueid = '';	
	
    var colorlist = '';
    //printableColorList = $j.parseJSON(colorlist);
    var no_of_side = '';
    var first_font = '';
    var text_svg1,text_svg2,text_svg3,text_svg4, text_svg5 ,text_svg6, text_svg7, text_svg8 = '';
    var side_1 = 1;
    var side_2 = 0;
    var side_3 = 0;
    var side_4 = 0;
    var side_5 = 0;
    var side_6 = 0;
    var side_7 = 0;
    var side_8 = 0;
    var png_data;	
    var filearray = new Array();
	
    var customer_id = '';
	
    var added_images = '';
    var fonts_used = '';
    var configtotalprice = 'Total Price';
    var configselectedsize = 'Selected Size';
    var configqtymessage = 'Please choose appropriate Quantity.';
    var clipartsloaded = 0;
    var designIdeasloaded = 0;
    var curr_side_id = 1;
    var datauri;
    var productImageCan;
    var customizationCan;
    var priceInterval;
    var action;
    var shareType;
    var sideNameAry = ['Front','Back','Left','Right'];
    var toolType = "producttool"; //possible values: "producttool", "web2print"	
    var pickerMode = "full"; //possible values: "full", "printable"
	
    var printingMode = "DTG"; //possible values: "DTG", "Vinyl/Screen"	
    if(pickerMode=="printable"){
	firstColor = printableColorList[0].colorCode;		
	borderColor = firstColor.substring(1);		
    }else{
	borderColor = "000";
    }
    var cartId = '';
	
	
    var pos_x = new Array();
    var pos_y = new Array();
    var design_area_width = new Array();
    var design_area_height = new Array();
    var outputWidth = new Array();
    var outputHeight = new Array();
    var postData = <?php echo $postData; ?>;
	
	
    var extensionArray = ['../DO_NOT_UPLOAD/extensions/ext-product.js','../DO_NOT_UPLOAD/extensions/ext-multiColor.js','../DO_NOT_UPLOAD/extensions/ext-curveText.js','../DO_NOT_UPLOAD/extensions/ext-objectPanel.js','../DO_NOT_UPLOAD/extensions/ext-objectLock.js','../DO_NOT_UPLOAD/extensions/ext-LayerPanel.js',/*'../DO_NOT_UPLOAD/extensions/ext-photoCollage.js',*/'../DO_NOT_UPLOAD/extensions/ext-productPricing.js','../DO_NOT_UPLOAD/extensions/ext-transformPanel.js','../DO_NOT_UPLOAD/extensions/ext-ProductMaskOverlayImage.js','../DO_NOT_UPLOAD/extensions/ext-ShowObjectSize.js','../DO_NOT_UPLOAD/extensions/ext-textShape.js','../DO_NOT_UPLOAD/extensions/ext-pickDesignColor.js','../DO_NOT_UPLOAD/extensions/ext-fliptools.js','../DO_NOT_UPLOAD/extensions/ext-imageEffect.js','../DO_NOT_UPLOAD/extensions/ext-undoRedo.js'];
	
</script>

<!-- load Galleria -->
<link rel="stylesheet" href="<?php echo $jspath . 'plupload/jquery.plupload.queue.css'; ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo $jspath . 'css/jquery.jscrollpane.css'; ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo $jspath . 'css/default.css'; ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo $jspath . 'css/personalization.css'; ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo $jspath . 'css/font-awesome.css'; ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo $jspath . 'css/jquery.loader.css'; ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo $jspath . 'css/jPicker.css'; ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo $jspath . 'css/jgraduate.css'; ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo $jspath . 'css/knob.css'; ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo $jspath . 'css/knob.css'; ?>" type="text/css" />
<script type="text/javascript" src="<?php echo $jspath . 'js-hotkeys/jquery.hotkeys.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'jquerybbq/jquery.bbq.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'svgicons/jquery.svgicons.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'jquery-ui/jquery-ui.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'jgraduate/jpicker.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'jgraduate/jquery.jgraduate.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'spinbtn/JQuerySpinBtn.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'contextmenu/jquery.contextMenu.js'; ?>"></script>
<script type="text/javascript" src="<?php echo get_base_url() . 'designnbuy/assets/js/jquery.validate.js'; ?>"></script>
<?php if ($_SERVER['HTTP_HOST'] == "192.168.0.222" || $_SERVER['HTTP_HOST'] == "192.168.0.139" || $_SERVER['HTTP_HOST'] == "192.168.0.46" || $_SERVER['HTTP_HOST'] == "192.168.0.30:8080" ) { ?>
<script type="text/javascript" src="<?php echo $jspath.'DO_NOT_UPLOAD/pathseg.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/touch.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/browser.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/svgtransformlist.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/math.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/units.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/svgutils.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/sanitize.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/select.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/history.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/draw.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/path.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/md5-min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/dnb/DNBBaseObject.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/ProductModule.js?t=' . $uniqueid; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/ClipArtModule.js?t=' . $uniqueid; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/DesignArtModule.js?t=' . $uniqueid; ?>"></script><!--needs to be change in admin-->

<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/handlebars-v3.0.3.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/handlerBarUtility.js'; ?>"></script>
<script type="text/javascript" src="<?php //echo $jspath.'DO_NOT_UPLOAD/product-src.js?t='.$uniqueid;  ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/product-src_M.js?t=' . $uniqueid; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/jquery.simple-color.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/svgcanvas.js?t=' . $uniqueid; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/svg-editor.js?t=' . $uniqueid; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/locale/locale.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/contextmenu.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/font_jsapi.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/font-selector.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/raphael.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/plupload/moxie.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/plupload/plupload.dev.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/plupload/jquery.ui.plupload.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/plupload/imageuploader.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/jquery.jscrollpane.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/ImportTemplate.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'DO_NOT_UPLOAD/quill.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath.'DO_NOT_UPLOAD/jquery.mousewheel.js'; ?>"></script>
<?php } else { ?>
<script type="text/javascript">
        extensionArray = [];
    </script>
<script type="text/javascript" src="<?php echo $jspath . 'built.product.min.js?t=' . $uniqueid; ?>"></script>
<?php } ?>
<script id="h_cliparts" type="text/x-handlebars-template">
    {{#if cliparts}}
    {{#cliparts}}
    <li><a href="JavaScript:void(0);" onClick="loadClipartONCanvas('{{clipart_svg}}', 'import_svg','{{clipart_id}}'); return false;"><img src="{{image_path}}" width="94" height="79" /></a><label>{{clipart_price}}</label></li>
    {{/cliparts}}
    {{/if}}
</script>
<script id="h_designarts" type="text/x-handlebars-template">
    {{#if designideas}}
    {{#designideas}}
    <li><a href="JavaScript:void(0);" onClick="loadClipartONCanvas('{{clipart_svg}}', 'import_svg','{{clipart_id}}'); return false;"><img src="{{image_path}}" width="94" height="79" /></a><label>{{clipart_price}}</label></li>
    {{/designideas}}
    {{/if}}
</script>
<script id="h_products" type="text/x-handlebars-template">
	{{#if products}}
	{{#each products}}
	<li><a href="JavaScript:void(0);" onClick="ProductModule.loadProduct('{{id}}'); return false;"><img src="{{image}}" style="width: 90px; height: 90px;" onload="ProductModule.productImageLoaded(this)"/> <span>{{name}}</span></a></li>
	{{/each}}
	{{/if}}
</script>
<script type="text/javascript" src="<?php echo $jspath . 'jquery-ui/jquery.ui.touch-punch.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'plupload/i18n/' . $locale . '.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . 'plugins/farbtastic.js'; ?>"></script>
<script type='text/javascript' src='<?php echo $jspath . 'picasa/picasa.js'; ?>' ></script>
<script type="text/javascript" src="<?php echo $jspath . 'flickr/jquery.flickr.js'; ?>"></script>
<link rel="stylesheet" href="<?php echo $jspath . 'css/farbtastic.css'; ?>" type="text/css" />

<!-- Image Upload End-->
<script type="text/javascript">google.load("webfont", "1");</script>
<script type="text/javascript">
    WebFontConfig = {
	google: { families: [ 'Lato::latin' ] }
    };
    (function() {
	var wf = document.createElement('script');
	wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
	    '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
	wf.type = 'text/javascript';
	wf.async = 'true';
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(wf, s);
    })(); 
</script>
<?php
$langCss = '';
if ($locale == "de_DE") {
    $langCss = 'css/default_german.css';
}
if ($locale == "fr_FR") {
    $langCss = 'css/default_french.css';
}
if ($langCss != "") {
    ?>
<link rel="stylesheet" href="<?php echo $jspath . $langCss; ?>">
<?php
}
?>

<div style="display:none;" id="calAreaDiv"></div>
<div class="designtool">
  <div id="container_dt">
    <div id="loaderImage-overlay" style="display:none;"></div>
    <div id="loaderImage" style="left: 281px;"> <img src="<?php echo $jspath.'images/loader.gif' ?>"  /> </div>
    <div class="wraper_dt">
      <div>
        <div class="main_dt">
          <section class="right-section">
            <section class="right-panel">
              <button id="addproduct" class="a-center"> 
              <div class="spicon_class"> <svg viewBox="0 0 20 20" class="optoin-control-icons">
                <g>
                  <path d="M19.575,1.966L12.477,0h-0.139h-0.02c-0.278,0-0.504,0.191-0.546,0.475c-0.057,0.393-0.26,0.707-0.605,0.973
		C10.827,1.71,10.442,1.84,10.005,1.84c-0.437,0-0.824-0.127-1.171-0.391C8.486,1.183,8.29,0.866,8.227,0.475
		C8.181,0.19,7.949,0,7.682,0h-0.04H7.503L0.404,1.966c-0.289,0.081-0.461,0.411-0.387,0.72L1.01,6.839
		c0.063,0.26,0.288,0.433,0.556,0.433h2.621v9.333c0,0.327,0.238,0.581,0.546,0.581h10.514c0.308,0,0.566-0.253,0.566-0.581V7.272
		h2.621c0.248,0,0.475-0.183,0.536-0.433l1.013-4.153C20.057,2.377,19.883,2.051,19.575,1.966z M18.007,6.099h-2.76
		c-0.288,0-0.546,0.263-0.546,0.591v9.322H5.279V6.69c0-0.328-0.238-0.591-0.546-0.591h-2.74l-0.774-3.14l6.096-1.68
		C7.532,1.798,7.88,2.22,8.366,2.548c0.487,0.327,1.033,0.485,1.638,0.485c0.596,0,1.132-0.158,1.618-0.485
		c0.487-0.327,0.844-0.75,1.063-1.269l6.096,1.68L18.007,6.099z"/>
                </g>
                </svg> </div>
              <div id="addmainproduct" class="caption">choose product</div>
              </button>
              <!--<button id="addproduct">
							<div class="spicon_class">
								<svg viewBox="0 0 20 20" class="optoin-control-icons">
									<path  d="M9.94,18.369c-0.168,0-0.329-0.069-0.444-0.192L2.35,10.494c0-0.002-0.004-0.003-0.005-0.005
										C1.477,9.53,1,8.26,1,6.913c0-1.347,0.477-2.617,1.345-3.574l0.108-0.12c0.883-0.976,2.062-1.512,3.319-1.512
										c1.258,0,2.437,0.537,3.32,1.512L9.94,4.154l0.846-0.935c0.884-0.976,2.063-1.513,3.32-1.513s2.438,0.537,3.32,1.513l0.107,0.12
										c0.868,0.957,1.347,2.227,1.347,3.574c0,1.347-0.479,2.617-1.346,3.575c-0.001,0.002-0.003,0.003-0.005,0.005l-7.146,7.683
										C10.27,18.3,10.108,18.369,9.94,18.369z M3.24,9.676l6.7,7.203l6.7-7.203c1.379-1.525,1.378-4.004-0.004-5.529l-0.107-0.119
										c-0.651-0.719-1.512-1.115-2.422-1.115c-0.911,0-1.771,0.396-2.423,1.115l-1.296,1.431C10.273,5.585,10.11,5.657,9.94,5.657
										c-0.17,0-0.334-0.072-0.449-0.198L8.196,4.028C7.545,3.31,6.684,2.913,5.773,2.913c-0.911,0-1.771,0.396-2.421,1.115l-0.109,0.12
										C1.862,5.671,1.861,8.151,3.24,9.676z"/>
								</svg>
							</div>
							<div class="caption" id="chooseProductCaption">Product</div>
						</button>   -->
              <?php //if($configurefeature['0'] == 1) { ?>
              <button id="addtext">
              <div class="spicon_class"> <svg viewBox="0 0 20 20" class="optoin-control-icons">
                <path d="M17.558,20.003c-0.1,0-0.197-0.024-0.286-0.071l-5.138-2.759c-0.696,0.15-1.387,0.228-2.062,0.228
										c-5.058,0-9.173-3.868-9.173-8.624c0-4.754,4.115-8.623,9.173-8.623c5.058,0,9.171,3.869,9.171,8.623c0,2.35-1.036,4.61-2.852,6.236
										c-0.084,1.428,1.14,3.453,1.64,4.142c0.152,0.209,0.131,0.488-0.052,0.675C17.87,19.942,17.713,20.003,17.558,20.003z M12.22,16.051
										c0.099,0,0.197,0.027,0.286,0.074l3.54,1.899c-0.489-0.997-0.936-2.26-0.776-3.343c0.021-0.125,0.085-0.242,0.184-0.328
										c1.679-1.429,2.641-3.462,2.641-5.576c0-4.158-3.598-7.54-8.021-7.54c-4.422,0-8.02,3.382-8.02,7.54s3.597,7.54,8.02,7.54
										c0.652,0,1.326-0.082,2.003-0.246C12.123,16.06,12.171,16.051,12.22,16.051z"/>
                </svg> </div>
              <div class="caption" id="addTextCaption">Text</div>
              </button>
              <?php// } ?>
              <?php //if($configurefeature['1'] == 1) { ?>
              <button id="addartpopup">
              <div class="spicon_class"> <svg viewBox="0 0 20 20" class="optoin-control-icons">
                <path d="M18.69,6.124c-0.724,0-1.311,0.599-1.311,1.334c0,0.288,0.092,0.554,0.246,0.771l-2.477,1.6L14.5,8.394
										c-0.093-0.201-0.326-0.287-0.522-0.195l-1.64,0.768c-0.058,0.027-0.103,0.07-0.142,0.119l-1.691-2.592
										c0.486-0.194,0.835-0.674,0.835-1.238c0-0.735-0.59-1.333-1.311-1.333c-0.723,0-1.311,0.598-1.311,1.333
										c0,0.564,0.347,1.044,0.834,1.238L7.862,9.085C7.824,9.036,7.779,8.993,7.72,8.966L6.082,8.198C5.884,8.107,5.651,8.192,5.559,8.394
										L4.895,9.857L2.431,8.229c0.154-0.218,0.248-0.483,0.248-0.772c0-0.735-0.588-1.334-1.31-1.334c-0.722,0-1.31,0.599-1.31,1.334
										S0.647,8.79,1.369,8.79c0.138,0,0.267-0.028,0.392-0.068l3.197,5.165c0.073,0.116,0.198,0.188,0.333,0.188h9.477
										c0.136,0,0.261-0.071,0.334-0.188l3.197-5.165c0.125,0.04,0.254,0.068,0.392,0.068c0.723,0,1.31-0.598,1.31-1.333
										C20.001,6.721,19.412,6.124,18.69,6.124z M12.668,9.694l1.281-0.6l0.53,1.168l-1.063,0.687L12.605,9.71
										C12.627,9.703,12.646,9.704,12.668,9.694z M10.029,4.722c0.288,0,0.522,0.239,0.522,0.532c0,0.294-0.233,0.531-0.522,0.531
										c-0.287,0-0.522-0.238-0.522-0.531C9.507,4.961,9.742,4.722,10.029,4.722z M6.11,9.095l1.281,0.6c0.021,0.01,0.041,0.01,0.062,0.015
										l-0.838,1.284l-1.052-0.695L6.11,9.095z M0.846,7.458c0-0.293,0.234-0.532,0.522-0.532S1.89,7.165,1.89,7.458
										c0,0.294-0.233,0.531-0.521,0.531S0.846,7.751,0.846,7.458z M14.551,13.275H5.508L3.354,9.797l3.157,2.085
										c0.183,0.119,0.424,0.068,0.542-0.115l2.975-4.555l2.943,4.508c0.118,0.182,0.358,0.234,0.539,0.116l3.212-2.075L14.551,13.275z
										 M18.69,7.99c-0.288,0-0.523-0.238-0.523-0.531c0-0.294,0.235-0.533,0.523-0.533c0.287,0,0.521,0.239,0.521,0.533
										C19.212,7.751,18.979,7.99,18.69,7.99z M15.194,15.331c0,0.221-0.176,0.401-0.395,0.401H5.259c-0.218,0-0.394-0.181-0.394-0.401
										s0.176-0.4,0.394-0.4H14.8C15.019,14.931,15.194,15.11,15.194,15.331z"/>
                </svg> </div>
              <div class="caption" id="chooseArtCaption">Art</div>
              </button>
              <?php //} ?>
              <?php //if($configurefeature['2'] == 1) { ?>
              <button ids="addtimages" id="addimgbtn">
              <div class="spicon_class"> <svg viewBox="0 0 20 20" class="optoin-control-icons">
                <g>
                  <path d="M2.881,14.929l14.971,0.004c0.322,0,0.584-0.132,0.718-0.363c0.134-0.23,0.117-0.52-0.047-0.793
											c-0.022-0.037-0.052-0.072-0.086-0.101l-4.698-3.864c-0.315-0.216-0.594-0.222-0.774-0.188c-0.259,0.047-0.49,0.204-0.671,0.458
											l-1.681,1.837c-0.145,0.159-0.133,0.405,0.029,0.549c0.163,0.144,0.412,0.132,0.559-0.028l1.697-1.856
											c0.012-0.014,0.023-0.027,0.033-0.042c0.06-0.086,0.125-0.144,0.18-0.153c0.052-0.008,0.128,0.029,0.149,0.043l4.525,3.726
											L2.888,14.15l5.499-6.36c0.017-0.02,0.032-0.041,0.044-0.063c0.002-0.004,0.004-0.008,0.007-0.011
											C8.443,7.723,8.449,7.731,8.456,7.74l2.057,2.809c0.128,0.175,0.375,0.214,0.553,0.089c0.177-0.126,0.216-0.369,0.089-0.543
											L9.107,7.301C8.934,7.039,8.68,6.893,8.409,6.903c-0.258,0.009-0.492,0.158-0.646,0.41l-5.53,6.395
											c-0.017,0.021-0.032,0.041-0.045,0.064c-0.155,0.275-0.163,0.565-0.025,0.795C2.3,14.797,2.563,14.929,2.881,14.929z M19.779,3.644
											H0.551c-0.219,0-0.395,0.174-0.395,0.39v12.578C0.156,16.825,0.333,17,0.551,17h19.228c0.219,0,0.396-0.175,0.396-0.389V4.033
											C20.175,3.818,19.998,3.644,19.779,3.644z M19.385,16.223H0.946V4.422h18.438V16.223z M13.007,8.775
											c0.83,0,1.507-0.665,1.507-1.483S13.837,5.81,13.007,5.81c-0.831,0-1.507,0.665-1.507,1.482S12.176,8.775,13.007,8.775z
											 M13.007,6.587c0.394,0,0.716,0.316,0.716,0.705S13.4,7.997,13.007,7.997c-0.395,0-0.717-0.316-0.717-0.705
											S12.612,6.587,13.007,6.587z"/>
                </g>
                </svg> </div>
              <div class="caption" id="addImageCaption">Upload</div>
              </button>
              <?php //} ?>
              <!--<button ids="btnPhotoBox" id="btnPhotoBox">
							<div class="spicon_class">
								<svg class="optoin-control-icons" width="49.27px" height="47.488px" viewBox="0 0 49.27 47.488" >
									<polyline fill="none" stroke="<?php echo $svgcolor; ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="
										15.802,10.156 26.124,1.156 36.801,10.156 "/>
									<rect x="1.802" y="10.156" fill="none" stroke="<?php echo $svgcolor; ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" width="46" height="36"/>
									<rect x="5.802" y="14.156" fill="none" stroke="<?php echo $svgcolor; ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" width="38" height="28"/>
									<rect x="9.635" y="29.489"  width="11" height="10"/>
									<rect x="23.635" y="19.489"  width="15" height="7"/>
									<polygon  points="14.635,17.063 16.23,20.294 19.796,20.813 17.215,23.328 17.825,26.879 14.635,25.202 
										11.446,26.879 12.055,23.328 9.475,20.813 13.041,20.294 "/>
									<circle  cx="31.302" cy="33.823" r="5.333"/>
								</svg>
							</div>
							<div class="caption" id="photoBoxCaption">PhotoBox</div> 
						</button>--> 
              <!--<button id="desingidea"><div class="spicon_class"> <span class="icon-7"></span> </div> <div class="caption" id="designIdeaCaption">Designs</div> </button>--> 
            </section>
            <nav id="layer-panel" class="layer-navigation-panel">
              <div class="layer-caption-section">
                <div class="svg-holder"><svg viewBox="0 0 20 20" class="optoin-control-icons">
                  <path d="M14.82,8.295l3.543-1.705L9.862,2.499L1.36,6.59l3.542,1.705L1.36,10l3.542,1.705L1.36,13.409l8.501,4.092 l8.501-4.092l-3.543-1.704L18.363,10L14.82,8.295z M6.107,8.875l3.755,1.807l3.754-1.807L15.954,10l-6.092,2.932L3.769,10 L6.107,8.875z M15.954,13.409l-6.092,2.933l-6.093-2.933l2.338-1.125l3.755,1.808l3.754-1.808L15.954,13.409z"/>
                  </svg></div>
                <div id="manageLayersCaption" class="caption">LAYERS</div>
              </div>
              <div class="input-area">
                <div class="mangalyaan">
                  <div id="sortableLayerPanel" class="layer-object"></div>
                </div>
              </div>
            </nav>
            <!-- right-panel-property -->
            <section class="right-panel-property" >
              <div id="color_picker"></div>
              <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="addart-panel">
                <div class="box-outer">
                  <label class="label">Add Art</label>
                  <div class="inst" id="inst">i</div>
                  <div style="display:none;" id="design_tooltip" class="tooltip"> 1. Ungroup does one level layering, so to do further layering, ungroup again until you can select the element you want to change color for <br>
                    <br>
                    2.Select any layer to change color of complete layer. </div>
                  <button id="tool_add_art_close"></button>
                  <div class="input-area"> </div>
                </div>
              </nav>
              <!--<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="addtext-panel">
							<div class="box-outer">
								<label class="label">Place your text</label>
								<div class="inst" id="inst_text">i</div>
								<div style="display:none;" id="inst_text_tooltip" class="tooltip"> 1. Select the text element and edit it further.</div>
								<button id="tool_place_text_close"></button>
								
							</div>
						</nav>-->
              <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right"> </nav>
              <!--<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="addshape-panel">
					<div class="box-outer">
					<label class="label">ADD SHAPES</label>
					<div class="inst" id="inst_shape">i</div>
					<div style="display:none;" id="inst_shape_tooltip" class="tooltip"> 1. Click any control to get more options.<br>
						<br>
						2.Please click the select pointer in left panel to exit shape mode.
					</div>
					<button id="tool_add_shape_close"></button>
					<div class="note-background">
					</div>
					<br class="clear" />
					<br class="clear" />
					</div>
					</nav>--> 
              <!--<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="qrcode-panel">
					<div class="box-outer">
					<label class="label">Qr Code</label>
					<div class="inst" id="inst_qrcode">i</div>
					<div style="display:none;" id="inst_qrcode_tooltip" class="tooltip"> 
					To place QR Code on your design, choose the type of information you wish to input. Fill in the information and then click the 'Generate' button. To place it on canvas, simply click on the generated QR Code image.
					<button onclick="$j('#inst_qrcode_tooltip').hide();" class="closebuttonclass">x</button>
					</div>
					<div class="scroll-area">

					</div>
					</div>
					</nav>-->
              <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="designidea-panel">
                <div class="box-outer">
                  <label class="label">DESIGN IDEA</label>
                  <button id="tool_design_idea_close"></button>
                  <div class="input-area">
                    <div class="field-raw">
                      <div class="product-type">
                        <select class="select-small" id="designideaopt" autocomplete="off">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="scroll-area">
                    <div class="tshirt-product">
                      <ul id="designideacontainer" class="t-shirt-list">
                      </ul>
                    </div>
                  </div>
                </div>
              </nav>
              <!-- common property panel --> 
              <!--<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="edit-panel">
					<div class="box-outer">
					<label class="label">Edit Panel</label>
					<div class="inst" id="inst_edit_panel">i</div>
					<div style="display:none;" id="design_tooltip_edit_panel" class="tooltip"> 1. Ungroup does one level layering, so to do further layering, ungroup again until you can select the element you want to change color for.<br>
				    <br>
				    2. Select any layer to change color of complete layer.</div>
					<button id="tool_edit_close"></button>              
					</div>
					</nav>--> 
              <!-- /common property panel --> 
              <!-- common property -->
              
              <nav class="ranger-panel cbp-spmenu-right" id="common-panel" style=" left: 228px; top: 303px;">
                <div class="box-outer clearfix">
                  <div id="text_panel" style="display: inline;">
                    <div>
                      <textarea id="text" rows="1" cols="2"></textarea>
                    </div>
                  </div>
                  <div class="opration-1"> 
                    <!-- Font family -->
                    
                    <div class="toolset_fontfamily" id="tool_font_family" style="display:none">
                      <div tabindex="1" class="wrapper-dropdown-1" id="font-box"> <span id="selectedFont">CHOOSE<strong>FONT</strong></span> </div>
                    </div>
                    <!--<div class="font-size">5</div>-->
                    <!-- <div class="textFontSizePanel">
                      <button id="textFontSize"><i class="fa fa-text-height"></i></button>
                      <div class="shape_con" style="display:none;">
                        <div class="currentFontSize">Font Size: 12</div>
                        <div id="fontSizeInstruction">Font Size: 12</div>
                      </div>
                    </div> -->
                    <div class="common-line-field clearfix">
                      <div class="cmn-ln-caption" id="color-caption">Color</div>
                      <div id="tool_fill">
                        <div class="color_tool" >
                          <div class="color_block singleColor"> &nbsp;
                            <div id="fill_bg"></div>
                            <div id="fill_color" class="color_block" title="Change fill color"></div>
                          </div>
                          <div class="multiColorBlock"></div>
                        </div>
                      </div>
                    </div>
                    <div id="font-style-bold-italic" class="common-line-field clearfix">
                      <div id="font_style_caption" class="cmn-ln-caption">Style</div>
                      <div class="size-bold-italic" >
                        <div class="tool_button" id="tool_bold" title="Bold Text"><i class="fa fa-bold"></i></div>
                        <div class="tool_button" id="tool_italic" title="Italic Text"><i class="fa fa-italic"></i></div>
                        <!-- <div class="tool_button" id="tool_underline" title="Underline Text"><i class="fa fa-underline"></i></div>--> 
                      </div>
                    </div>
                    <div class="common-line-field clearfix">
                      <div class="cmn-ln-caption">Border</div>
                      <div class="borderalignmentpanel global-insidepanel">
                        <button id="borderalignment"><i class="fa fa-star-o fa-6"></i> </button>
                      </div>
                    </div>
                    <div class="common-line-field clearfix">
                      <div class="cmn-ln-caption" id="font-shape-caption">Font Shape</div>
                      <div id="textShapeDiv" class="">
                        <div class="caption-sectiona">
                          <div class="curve-division">
                            <div class="curve-property"></div>
                            <div id="textShapeDD" class="wrapper-dropdown-2" > <span id="textShapeSelected"><img src="<?php echo $jspath.'images/shape-none.jpg'; ?> " /></span>
                              <div class="shape_con">
                                <div class="ranger-area">
                                  <div id="textShapeSlider"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="ranger-area no-display">
                          <div id="textShapeSlider"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="opration-2">
                    <div class="common-line-field clearfix">
                      <div class="cmn-ln-caption" id="alignmentCaption">Alignment</div>
                      <div class="align_icons clearfix">
                        <!--<ul class="optcols3" style="width:100px; height:70px;">
                          <li class="push_button" id="tool_posleft" title="Align Left"> <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 30 30">
                            <rect x="8" y="8" width="1" height="14"/>
                            <rect x="10" y="10" width="11" height="4" stroke="none" />
                            <rect x="10" y="16" width="5" height="4" stroke="none" />
                            </svg> </li>
                          <li class="push_button" id="tool_poscenter" title="Align Center"> <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 30 30">
                            <rect x="15" y="8" width="1" height="14"/>
                            <rect x="10" y="10" width="11" height="4" stroke="none" />
                            <rect x="13" y="16" width="5" height="4" stroke="none" />
                            </svg> </li>
                          <li class="push_button" id="tool_posright" title="Align Right"> <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 30 30">
                            <rect x="21" y="6" width="1" height="18"/>
                            <rect x="6" y="9" width="14" height="5" stroke="none" />
                            <rect x="14" y="16" width="6" height="5" stroke="none" />
                            </svg> </li>
                          <li class="push_button" id="tool_postop" title="Align Top"> <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 30 30">
                            <rect x="5" y="7" width="18" height="1"/>
                            <rect x="8" y="9" width="5" height="14" stroke="none" />
                            <rect x="15" y="9" width="5" height="6" stroke="none" />
                            </svg> </li>
                          <li class="push_button" id="tool_posmiddle" title="Align Middle"> <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 30 30">
                            <rect x="5" y="15" width="18" height="1"/>
                            <rect x="15" y="8" width="5" height="14" stroke="none" />
                            <rect x="8" y="12" width="5" height="7" stroke="none" />
                            </svg> </li>
                          <li class="push_button" id="tool_posbottom" title="Align Bottom"> <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 30 30">
                            <rect x="5" y="22" width="18" height="1"/>
                            <rect x="15" y="7" width="5" height="14" stroke="none" />
                            <rect x="8" y="15" width="5" height="6" stroke="none" />
                            </svg> </li>
                        </ul>-->
                        <div id="tool_align_relative"> <span id="relativeToLabel">relative to:</span>
                          <select title="Align relative to ..." id="align_relative_to" autocomplete="off">
                            <option value="selected" id="selected_objects">Selected Objects</option>
                            <option value="largest" id="largest_object">Largest Object</option>
                            <option value="smallest" id="smallest_object">Smallest Object</option>
                            <option value="page" id="page">page</option>
                          </select>
                        </div>
                        <div class="interlock">
                          <input type="checkbox" checked="checked" class="checkboxStepper" name="propCheckbox" id="propCheckbox">
                          <i class="fa fa-link"></i></div>
                      </div>
                    </div>
                    <div class="copypaste">
                      <button id="tool_group" class="disabled" title="Group"> <svg version="1.1" id="Layer_1" width="30px" height="30px" viewBox="0 0 30 30">
                      <g>
                        <path d="M18.416,6.008h-6.843v6.834h6.843V6.008z M17.656,12.083h-5.323V6.767h5.323V12.083z M20.697,21.195v3.797 h3.802v-3.797H20.697z M23.738,24.232h-2.28v-2.277h2.28V24.232z M5.49,24.992h3.802v-3.797H5.49V24.992z M6.251,21.955h2.28 v2.277h-2.28V21.955z M13.094,24.992h3.802v-3.797h-3.802V24.992z M13.854,21.955h2.28v2.277h-2.28V21.955z M7.771,17.398h6.843 v2.658h0.76v-2.658h6.844v2.658h0.76v-3.418h-7.604v-2.658h-0.76v2.658H7.011v3.418h0.761V17.398z"/>
                      </g>
                      </svg></button>
                      <button id="tool_ungroup" title="Ungroup" class="disabled" style="display:none"><svg version="1.1" id="Layer_1" width="30px" height="30px" viewBox="0 0 30 30">
                      <g>
                        <path d="M11.584,24.992h6.843v-6.834h-6.843V24.992z M12.344,18.917h5.323v5.315h-5.323V18.917z M5.501,9.805 h3.802V6.008H5.501V9.805z M6.262,6.768h2.28v2.278h-2.28V6.768z M20.708,6.008v3.797h3.802V6.008H20.708z M23.749,9.045h-2.28 V6.768h2.28V9.045z M16.906,6.008h-3.802v3.797h3.802V6.008z M16.146,9.045h-2.28V6.768h2.28V9.045z M15.386,17.019v-6.075h-0.761 v6.075H15.386z"/>
                      </g>
                      </svg></button>
                      <button id="tool_flipHoriz" title="Flip Horizontal">
                      <div><i> <svg version="1.1" id="Layer_1"  width="30px" height="30px" viewBox="0 0 30 30" enable-background="new 0 0 30 30">
                        <g>
                          <path d="M13.786,6.67c-0.118-0.025-0.237,0.036-0.286,0.146L6.855,21.639c-0.036,0.078-0.028,0.17,0.018,0.242
		c0.046,0.074,0.128,0.117,0.215,0.117h6.645c0.141,0,0.255-0.115,0.255-0.256V6.92C13.987,6.8,13.903,6.696,13.786,6.67z
		 M13.477,21.486H7.481l5.995-13.37V21.486z"/>
                          <path d="M22.143,21.639L15.499,6.816c-0.049-0.109-0.17-0.171-0.287-0.146s-0.201,0.13-0.201,0.25v14.822
		c0,0.141,0.114,0.256,0.255,0.256h6.645c0.087,0,0.169-0.043,0.215-0.117C22.171,21.809,22.179,21.717,22.143,21.639z"/>
                        </g>
                        </svg> </i></div>
                      </button>
                      <button id="tool_flipVert" title="Flip Vertical">
                      <div><i> <svg version="1.1" id="Layer_0"  width="30px" height="30px" viewBox="0 0 30 30" enable-background="new 0 0 30 30">
                        <g>
                          <path d="M22.66,13.953c0.025-0.118-0.036-0.237-0.146-0.286L7.692,7.023C7.613,6.987,7.521,6.995,7.45,7.041
		C7.375,7.086,7.332,7.168,7.332,7.255v6.644c0,0.141,0.115,0.255,0.255,0.255H22.41C22.53,14.155,22.635,14.071,22.66,13.953z"/>
                          <path d="M7.692,22.31l14.822-6.644c0.109-0.049,0.171-0.17,0.146-0.287s-0.13-0.201-0.25-0.201H7.587
		c-0.141,0-0.255,0.114-0.255,0.255v6.645c0,0.087,0.043,0.169,0.118,0.215C7.521,22.338,7.613,22.346,7.692,22.31z M7.843,15.688
		h13.371L7.843,21.684V15.688z"/>
                        </g>
                        </svg></i></div>
                      </button>
                      <button id="tool_cut" title="Cut" class="disabled">
                      <div><i><svg version="1.1" id="Layer_1"  width="30px" height="30px" viewBox="0 0 30 30" enable-background="new 0 0 30 30">
                        <path fill="#545352" d="M18.15,16.393c-0.377-0.694-0.962-1.165-1.58-1.342c-0.488-0.141-0.994-0.098-1.433,0.164l-1.302-2.406	c0.806-1.698,2.615-5.508,2.94-6.107C17.197,5.925,16.444,5,16.444,5l-3.419,6.314L9.608,5c0,0-0.752,0.925-0.332,1.7 c0.325,0.601,2.136,4.409,2.94,6.108l-1.302,2.405c-0.438-0.261-0.945-0.304-1.433-0.164c-0.618,0.178-1.203,0.647-1.58,1.343 c-0.678,1.249-0.419,2.748,0.575,3.349c0.44,0.268,0.951,0.311,1.443,0.169c0.616-0.178,1.203-0.647,1.579-1.341 c0.166-0.307,0.274-0.627,0.33-0.945v0.001c0.001-0.003,0.001-0.006,0.003-0.008c0.004-0.026,0.008-0.053,0.011-0.082 c0.279-1.981,0.808-2.993,1.182-3.484c0.375,0.491,0.904,1.503,1.183,3.484c0.003,0.029,0.008,0.056,0.011,0.082 c0.001,0.002,0.001,0.005,0.002,0.008v-0.001c0.057,0.318,0.165,0.639,0.33,0.945c0.377,0.693,0.963,1.163,1.581,1.341 c0.491,0.142,1.002,0.099,1.443-0.169C18.569,19.141,18.827,17.643,18.15,16.393z M10.533,17.984 c-0.213,0.394-0.552,0.69-0.904,0.792c-0.157,0.045-0.388,0.072-0.599-0.056c-0.451-0.271-0.524-1.072-0.161-1.744 c0.218-0.398,0.547-0.688,0.906-0.792c0.155-0.044,0.386-0.071,0.598,0.056C10.824,16.514,10.897,17.312,10.533,17.984z	 M17.022,18.721c-0.212,0.129-0.441,0.101-0.598,0.056c-0.354-0.102-0.691-0.398-0.905-0.792c-0.364-0.673-0.291-1.471,0.16-1.744 c0.212-0.128,0.442-0.1,0.599-0.056c0.358,0.104,0.688,0.394,0.904,0.792C17.547,17.648,17.473,18.449,17.022,18.721z"/>
                        </svg> </i></div>
                      </button>
                      <button id="tool_copy" title="Copy" class="disabled">
                      <div><i><svg version="1.1" id="Layer_1"  width="30px" height="30px" viewBox="0 0 30 30" enable-background="new 0 0 30 30">
                        <path fill="#545352" d="M16,4.516c-0.062,0-6-0.016-6-0.016c-1.058,0-2,0.971-2,2L7.423,6.514C6.365,6.514,5.5,7.47,5.5,8.5v10 c0,1.029,0.942,2,2,2H15c1.059,0,2-0.971,2-2h0.501c1.058,0,2-0.971,2-2V8.512L16,4.516z M15,19.5H7.5c-0.524,0-1-0.491-1-1v-10	c0-0.744,0.612-1,1.5-1v9c0,1.029,0.942,2,2,2c0,0,5.422-0.006,6.005-0.006C16.005,19.102,15.607,19.5,15,19.5z M16,15.005h-4.5	c-0.275,0-0.499-0.224-0.499-0.499c0-0.276,0.224-0.5,0.499-0.5H16c0.276,0,0.5,0.224,0.5,0.5C16.5,14.781,16.276,15.005,16,15.005z	 M16,12.508h-4.5c-0.275,0-0.499-0.224-0.499-0.5c0-0.275,0.224-0.499,0.499-0.499H16c0.276,0,0.5,0.224,0.5,0.499 S16.276,12.508,16,12.508z M17,8.5c-0.532,0-1-0.48-1-1c0,0,0-1.014,0-1.984V5.515L18.501,8.5H17z"/>
                        </svg> </i></div>
                      </button>
                      <button id="tool_clone" title="Duplicate" class="disabled">
                      <div><i> <svg version="1.1" id="Layer_1"  width="30px" height="30px" viewBox="0 0 30 30" enable-background="new 0 0 30 30" xml:space="preserve">
                        <g>
                          <path fill="#545352" d="M16.854,6.961H5.596C5.267,6.961,5,7.227,5,7.554v11.198c0.001,0.326,0.267,0.592,0.596,0.592h11.257 c0.33,0,0.596-0.266,0.596-0.592V7.554C17.449,7.227,17.184,6.961,16.854,6.961z M16.258,18.158H6.192V8.148h10.066V18.158z M19.404,4.343H8.146c-0.328,0-0.596,0.266-0.596,0.593v1.253c0,0.326,0.268,0.592,0.596,0.592c0.33,0,0.597-0.266,0.597-0.592 v-0.66h10.065v10.012h-0.613c-0.33,0-0.596,0.266-0.596,0.594c0,0.326,0.266,0.592,0.596,0.592h1.209 c0.33,0,0.597-0.266,0.597-0.592V4.936C20,4.609,19.734,4.343,19.404,4.343z"/>
                        </g>
                        </svg> </i> </div>
                      </button>
                      <div>
                        <button id="tool_close" title="Close">Close</button>
                      </div>
                    </div>
                    <div id="transformPanel" style="display: none;">
                      <div class="move-object-panel dragablePanel">
                        <div class="mop-heading"><span class="mop-close">x</span></div>
                        <div class="ranges-content"> 
                          <!--<div class="moving-knob"><input id="knobAngle" class="knob" data-step="1" data-displayprevious=true data-min="0" data-max="360" data-width="65" data-cursor=true  value="0"></div>--> 
                          <!--<div class="joystic-button">
                                  <div class="toward-up">
                                    <button id="upMove"><span><i class="fa fa-angle-up"></i></span></button>
                                  </div>
                                  <div class="toward-left">
                                    <button id="leftMove"><span><i class="fa fa-angle-left"></i></span></button>
                                  </div>
                                  <div class="toward-right">
                                    <button id="rightMove"><span><i class="fa fa-angle-right"></i></span></button>
                                  </div>
                                  <div class="toward-down">
                                    <button id="downMove"><span><i class="fa fa-angle-down"></i></span></button>
                                  </div>
                            </div>--> 
                        </div>
                      </div>
                      <div class="parent-block">
                        <div class="pt-tp-row">
                          <div class="pt-top-arrow" align="center">
                            <button id="upMove"></button>
                          </div>
                          <div class="pt-top-left">
                            <button id="tool_posw"> <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 width="22.893px" height="23.019px" viewBox="-0.001 10 22.893 23.019" enable-background="new -0.001 10 22.893 23.019"
                 xml:space="preserve">
                            <polygon  points="-0.063,32.957 4.937,32.957 4.937,15.064 22.955,15.064 22.955,10.064 -0.063,10.064 "/>
                            </svg> </button>
                          </div>
                          <div class="pt-top-center">
                            <button id="tool_postop"> <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 width="22.893px" height="23.019px" viewBox="-0.001 10 22.893 23.019" enable-background="new -0.001 10 22.893 23.019"
                 xml:space="preserve">
                            <polygon  points="22.892,10.008 17.892,10.008 17.892,10 -0.126,10 -0.126,15 22.892,15 "/>
                            </svg> </button>
                          </div>
                          <div class="pt-top-right">
                            <button id="tool_posx"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 width="22.893px" height="23.019px" viewBox="-0.001 10 22.893 23.019" enable-background="new -0.001 10 22.893 23.019"
                 xml:space="preserve">
                            <polygon  points="-0.001,10.002 -0.001,15.002 17.892,15.002 17.892,33.02 22.892,33.02 22.892,10.002 "/>
                            </svg></button>
                          </div>
                        </div>
                        <div class="pt-tl-row">
                          <div class="left-arrow">
                            <div class="f-leftMove">
                              <button id="leftMove"></button>
                            </div>
                            <button id="tool_posleft"> <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 width="5px" height="23.019px" viewBox="-0.001 0.001 5 23.019" enable-background="new -0.001 0.001 5 23.019"
                 xml:space="preserve">
                            <polygon  points="0.007,0.001 0.007,5.001 -0.001,5.001 -0.001,23.019 4.999,23.019 4.999,0.001 "/>
                            </svg> </button>
                          </div>
                          <div class="midd-stuff">
                            <div class="pt-top-center">
                              <div class="plus-symbol">
                                <button id="tool_poso" class="plus-middle">&nbsp;</button>
                                <button id="tool_poscenter"class="tool_poscenter">&nbsp;</button>
                                <button id="tool_posmiddle" class="tool_posmiddle">&nbsp;</button>
                              </div>
                            </div>
                          </div>
                          <div class="right-arrow">
                            <button id="tool_posright"> <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 width="5px" height="23.019px" viewBox="17.892 0 5 23.019" enable-background="new 17.892 0 5 23.019" xml:space="preserve">
                            <polygon  points="17.9,0 17.9,5 17.892,5 17.892,23.018 22.892,23.018 22.892,0 "/>
                            </svg></button>
                            <div class="f-rightMove">
                              <button id="rightMove"></button>
                            </div>
                          </div>
                        </div>
                        <div class="pt-btm-row">
                          <div class="pt-btm-left">
                            <button id="tool_posy"> <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22.893px" height="23.019px" viewBox="-0.001 10 22.893 23.019" enable-background="new -0.001 10 22.893 23.019" xml:space="preserve">
                            <polygon  points="22.892,33.019 22.892,28.019 4.999,28.019 4.999,10 -0.001,10 -0.001,33.019 "/>
                            </svg> </button>
                          </div>
                          <div class="pt-btm-center">
                            <button id="tool_posbottom"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22.893px" height="23.019px" viewBox="-0.001 10 22.893 23.019" enable-background="new -0.001 10 22.893 23.019" xml:space="preserve">
                            <polygon  points="22.892,28.027 17.892,28.027 17.892,28.019 -0.126,28.019 -0.126,33.019 22.892,33.019 "></polygon>
                            </svg></button>
                          </div>
                          <div class="pt-btm-right">
                            <button id="tool_posz"> <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 width="22.893px" height="23.019px" viewBox="-0.001 10 22.893 23.019" enable-background="new -0.001 10 22.893 23.019"
                 xml:space="preserve">
                            <polygon  points="22.954,10.063 17.954,10.063 17.954,27.956 -0.064,27.956 -0.064,32.956 22.954,32.956 "/>
                            </svg> </button>
                          </div>
                          <div class="pt-top-bottom" align="center">
                            <button id="downMove"></button>
                          </div>
                        </div>
                      </div>
                    </div>
                  <div class="border-box" id="text_size_box">
                    <div class="textalignmentpanel">
                      <button id="textalignment"><i class="fa fa-align-center"></i></button>
                      <div class="text-align" style="display: none;">
                        <div class="tool_button" id="text_align_left" title="Align Left"><i class="fa fa-align-left"></i></div>
                        <div class="tool_button" id="text_align_center" title="Align Center"><i class="fa fa-align-center"></i></div>
                        <div class="tool_button" id="text_align_right" title="Align Right"><i class="fa fa-align-right"></i></div>
                      </div>
                    </div>
                    <div class="textlinespacepanel">
                      <button id="textLineSpace"><i class="fa fa-bars"></i></button>
                      <div class="text-lineSpace" style="display: none;">
                        <div class="ranger-area">
                          <div id="lineSpaceSlider"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="range-blocks image_quality">
                    <div class="upload-mathod">
                      <div class="div-table">
                        <div class="div-table-cell">
                          <div id="printQualityCaption" class="caption-tabs active">PRINT QUALITY</div>
                        </div>
                        <div class="div-table-cell">
                          <ul class="active">
                            <li id="printQualityPoorCaption" class="not-reco active"><i></i> Poor</li>
                            <li id="printQualityFairCaption" class="fair"><i></i> Fair</li>
                            <li id="printQualityGoodCaption" class="luk-gud"><i></i> Good</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!--<div id="sizeDiv" class="range-blocks no-display">
    <div class="caption-section">
      <div id="objectSizeCaption" class="caption">SIZE</div>
    </div>
    <div class="border-box">
      <div class="size-global-area">
        <div id="sizeSlider"></div>
      </div>
    </div>
  </div>-->
                  <div id="sizeDivPc" style="display:none;" class="range-blocks">
                    <div class="caption-section">
                      <div class="caption">SIZE</div>
                    </div>
                    <div class="border-box opacity-ranger">
                      <div class="toolset_global">
                        <div class="size-global-area">
                          <div id="opacity_dropdown" class="dropdown">
                            <div id="sizeSliderPc"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div> </div>
                  <div class="photo-boxbtn">
                    <button title="Change Image" class="button" id="changeImageCaption"><i class="fa fa-pencil-square-o"></i></button>
                    <button title="Delete Image" class="button" id="removeImageCaption"><i class="fa fa-trash"></i></button>
                  </div>
                  <!--<div class="range-blocks no-display" id="border_box">
    <div class="caption-section">
      <div id="objectborderCaption" class="caption">BORDER</div>
      <input id="stroke_width" class="sliderValue" size="2" value="5" type="text" data-attr="Stroke Width" disabled="disabled"/>
      <div class="color_tool" id="tool_stroke">
        <div class="color_block">
          <div id="stroke_bg"></div>
          <div id="stroke_color" class="color_block" title="Change stroke color"></div>
        </div>
        <div id="toggle_stroke_tools" title="Show/hide more stroke tools" style="display:none;"></div>
      </div>
      <div class="right">
        <label class="stroke_tool" >
          <select id="stroke_style" title="Change stroke dash style">
            <option selected="selected" value="none">&mdash;</option>
            <option value="2,2">...</option>
            <option value="5,5">- -</option>
            <option value="5,2,2,2">- .</option>
            <option value="5,2,2,2,2,2">- ..</option>
          </select>
        </label>
      </div>
    </div>
    <div class="border-box opacity-ranger">
      <div class="toolset_global">
        <div class="size-global-area">
          <div id="stroke_slider"></div>
        </div>
      </div>
    </div>
  </div>-->
                  <div id="rotationDiv" class="range-blocks no-display">
                    <div class="caption-section">
                      <div id="objectRotateCaption" class="caption">ROTATE</div>
                      <input id="rotateAngle" class="sliderValue" size="3" value="100" type="text" disabled="disabled"/>
                    </div>
                    <div class="border-box opacity-ranger">
                      <div class="toolset_global">
                        <div class="size-global-area">
                          <div id="opacity_dropdown" class="dropdown">
                            <div id="rotationSlider"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div id="rotationDivPc" style="display:none;" class="range-blocks">
                    <div class="caption-section">
                      <div class="caption">ROTATE</div>
                      <input id="rotateAnglePc" class="sliderValue" size="3" value="100" type="text" disabled="disabled"/>
                    </div>
                    <div class="border-box opacity-ranger">
                      <div class="toolset_global">
                        <div class="size-global-area">
                          <div id="opacity_dropdown" class="dropdown">
                            <div id="rotationSliderPc"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="range-blocks" id="tool_curve">
                    <div class="caption-section">
                      <div id="objectCurveCaption" class="caption">CURVE</div>
                      <input id="curveAngle" class="sliderValue" size="2" value="0" type="text" disabled="disabled"/>
                      <div id="objectCurveReverseCaption" class="caption">REVERSE</div>
                      <input id="curveInvert" class="" size="2" value="0" type="checkbox" />
                    </div>
                    <div class="border-box opacity-ranger">
                      <div class="toolset_global">
                        <div class="size-global-area">
                          <div id="curve_slider"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div id="lineSpaceDiv" class="range-blocks no-display">
                    <div class="caption-section">
                      <div id="objectLineSpaceCaptioon" class="caption">Line Spacing</div>
                      <input id="lineHeight" class="sliderValue" size="3" value="100" type="text" disabled="disabled"/>
                    </div>
                    <div class="border-box ">
                      <div class="toolset_global">
                        <div id="lineSpaceSlider"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="border-align-panel" id="common_upper_open_panel" style="display: none;">
                <button onClick="closeColorPanel();" id="cpclose"><i class="fa fa-close"></i></button>
                <div id="bordercontrolls" style="display:none;">
                  <div class="range-blocks" id="border_box">
                    <div class="caption-section">
                      <div id="objectborderCaption" class="caption no-display">BORDER</div>
                      <input id="stroke_width" class="sliderValue no-display" size="2" value="5" type="text" data-attr="Stroke Width" disabled="disabled"/>
                      <div class="color_tool" id="tool_stroke">
                        <div class="color_block">
                          <div id="stroke_bg"></div>
                          <div id="stroke_color" class="color_block" title="Change stroke color"></div>
                        </div>
                        <div id="toggle_stroke_tools" title="Show/hide more stroke tools" style="display:none;"></div>
                      </div>
                    </div>
                  </div>
                  <div class="border_size_option">
                    <div class="border_style">
                      <div class="inside_caption">BORDER STYLE</div>
                      <ul>
                        <li>
                          <button id ="border_style_0" value="none" class="tool_button">&mdash;</button>
                        </li>
                        <li>
                          <button id ="border_style_1" value="2,2" class="tool_button">...</button>
                        </li>
                        <li>
                          <button id ="border_style_2" value="5,5" class="tool_button">- -</button>
                        </li>
                        <li>
                          <button id ="border_style_3" value="5,2,2,2" class="tool_button">- .</button>
                        </li>
                      </ul>
                    </div>
                    <div class="clear"></div>
                    <div class="inside_caption">BORDER SIZE</div>
                    <button id ="border_size_0" class="tool_button">0</button>
                    <button id ="border_size_1" class="tool_button">1</button>
                    <button id ="border_size_2" class="tool_button">2</button>
                    <button id ="border_size_3" class="tool_button">3</button>
                    <button id ="border_size_4" class="tool_button">4</button>
                    <button id ="border_size_5" class="tool_button">5</button>
                    <button id ="border_size_6" class="tool_button">6</button>
                  </div>
                </div>
                <div id="mainSimpleColCon"> 
                  <!--Added by Gul for new color picker updated on 14/7/2015-->
                  <div class="border_colorpicker" align="center">
                    <div style="height:208px;display:block;">
                      <div id="colorpicker" class="colorpicker"></div>
                      <div class="hexapart">
                        <div class="div-table">
                          <div class="div-table-row">
                            <div class="div-table-cell">
                              <label title="Set To “Red” Color Mode"> R:</label>
                            </div>
                            <div class="div-table-cell">
                              <input  id="red_col_code" type="text" title="Enter A “Red” Value (0-255)" value="0" maxlength="3">
                            </div>
                          </div>
                          <div class="div-table-row">
                            <div class="div-table-cell">
                              <label title="Set To “Green” Color Mode"> G:</label>
                            </div>
                            <div class="div-table-cell">
                              <input  id="green_col_code" type="text" title="Enter A “Green” Value (0-255)" value="0" maxlength="3">
                            </div>
                          </div>
                          <div class="div-table-row">
                            <div class="div-table-cell">
                              <label title="Set To “Blue” Color Mode"> B:</label>
                            </div>
                            <div class="div-table-cell">
                              <input id="blue_col_code" type="text" title="Enter A “Blue” Value (0-255)" value="0" maxlength="3">
                            </div>
                          </div>
                          <div class="div-table-row">
                            <div class="div-table-cell">
                              <label title="Set Hex Code"> Hex: #</label>
                            </div>
                            <div class="div-table-cell">
                              <input id="hex_col_code" type="text" value="000000" class="Hex" maxlength="6">
                            </div>
                            <!--<div class="div-table-cell">
              <input type="text" title="Enter A “Alpha” Value (#00-#ff)" value="ff" class="AHex" maxlength="2">
            </div>--> 
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </nav>
              
              <!-- /common property --> 
            </section>
            <!-- right-panel-property --> 
          </section>
          
          <!--<div class="priceTag">
            <div class="rate" id="price"></div>
			<div id="priceAjaxLoader" class="ajax-loader"><img src="<?php echo $jspath.'images/ajax-loader.gif' ?>" height="31" width="31"/> </div>            
			<button id="products-settings" title="Add To Cart">
				<span class="crt">
					<i>
						<svg viewBox="0 0 22 22" class="optoin-control-icons">
							<g><path d="M21.313,0h-3.438c-0.332,0-0.616,0.238-0.677,0.566l-1.897,10.49H5.895l-4.613-7.95 C1.089,2.777,0.668,2.667,0.34,2.858C0.013,3.05-0.098,3.474,0.093,3.803l4.813,8.293c0.124,0.213,0.35,0.342,0.595,0.342h9.552 l-0.5,2.764H6.875c-0.38,0-0.688,0.311-0.688,0.691c0,0.383,0.308,0.691,0.688,0.691h8.251c0.331,0,0.616-0.238,0.677-0.566 l2.646-14.636h2.864c0.38,0,0.688-0.31,0.688-0.691S21.693,0,21.313,0z M8.938,17.967c-1.139,0-2.063,0.926-2.063,2.072 s0.924,2.074,2.063,2.074S11,21.186,11,20.039S10.076,17.967,8.938,17.967z M14.438,17.967c-1.141,0-2.063,0.926-2.063,2.072 s0.923,2.074,2.063,2.074c1.14,0,2.063-0.928,2.063-2.074S15.578,17.967,14.438,17.967z"/></g>
						</svg>
					</i>
				</span>	
				<div id="cart_next">NEXT</div>
            </button>
			
        </div>--> 
          
          <!--<section class="bottom-share-panel" style="display:none;">
					<div class="button-area">
						<ul><li><button id="tool_group" title="Group"></button></li></ul>
					</div>
				</section>-->
          <div id="productName"></div>
          <!--<section class="product-area admin-tool"> -->
          <section class="product-area">
            <section id="white">
              <section class="edit-tool-panel" style="width: 276px;">
                <ul>
                  <li>
                    <button id="tool_undo_custom" title="Undo"> 
                    <!--<i class="fa fa-undo"></i>--> 
                    <svg class="top-navigation-icon" viewBox="0 0 15 15">
                    <path  d="M7.547,3.014V0.501L3.954,4.091L7.547,7.68V4.45c2.373,0,4.313,1.938,4.313,4.306 c0,2.369-1.939,4.307-4.313,4.307c-2.371,0-4.312-1.938-4.312-4.307H1.798c0,3.158,2.587,5.742,5.749,5.742	c3.164,0,5.75-2.584,5.75-5.742C13.297,5.598,10.711,3.014,7.547,3.014z"/>
                    </svg> </button>
                  </li>
                  <li>
                    <button id="tool_redo_custom" title="Redo"> <svg class="top-navigation-icon" viewBox="0 0 15 15">
                    <path  d="M1.798,8.756c0,3.158,2.586,5.742,5.75,5.742c3.162,0,5.749-2.584,5.749-5.742h-1.438 c0,2.369-1.94,4.307-4.312,4.307c-2.373,0-4.313-1.938-4.313-4.307c0-2.368,1.939-4.306,4.313-4.306v3.23l3.593-3.589L7.548,0.501	v2.513C4.384,3.014,1.798,5.598,1.798,8.756z"/>
                    </svg> </button>
                  </li>
                  <li>
                    <button id="tool_paste" title="Paste" class="disabled"> 
                    <!--<i class="fa fa-paste"></i>--> 
                    <svg class="top-navigation-icon" viewBox="0 0 15 15">
                    <path d="M1.575,12.895h5.008v0.514H1.575C1.258,13.408,1,13.139,1,12.81V1.536c0-0.331,0.258-0.599,0.575-0.599h1.84 L3.349,1.249C3.343,1.274,3.34,1.298,3.34,1.321V2.69c0,0.189,0.147,0.342,0.329,0.342h4.517c0.182,0,0.33-0.153,0.33-0.342V1.321 c0-0.023-0.006-0.047-0.01-0.073L8.439,0.937h1.84c0.318,0,0.574,0.269,0.574,0.599v4.791l-0.064-0.059h-0.426V1.536 c0-0.047-0.037-0.086-0.084-0.086H9.006V2.69c0,0.472-0.367,0.855-0.82,0.855H3.669c-0.454,0-0.822-0.383-0.822-0.855V1.449H1.575 c-0.044,0-0.082,0.039-0.082,0.086V12.81C1.493,12.855,1.53,12.895,1.575,12.895z M3.939,2.711h3.975	c0.16,0,0.289-0.135,0.289-0.301V1.205c0-0.167-0.129-0.301-0.289-0.301H6.831C6.831,0.404,6.423,0,5.923,0 C5.421,0,5.014,0.404,5.014,0.904H3.94c-0.16,0-0.289,0.135-0.289,0.301V2.41C3.651,2.576,3.78,2.711,3.939,2.711z M12.678,8.046v6.566c0,0.247-0.191,0.446-0.426,0.446H7.305c-0.235,0-0.428-0.199-0.428-0.446V7.049c0-0.247,0.192-0.447,0.428-0.447h3.781 L12.678,8.046z M11.182,7.93h0.893l-0.893-0.808V7.93z M12.367,8.256h-1.498V6.927H7.305c-0.063,0-0.115,0.055-0.115,0.122v7.563 c0,0.067,0.052,0.122,0.115,0.122h4.947c0.064,0,0.115-0.055,0.115-0.122V8.256z M7.709,9.801c0,0.134,0.105,0.243,0.235,0.243 h3.667c0.131,0,0.234-0.109,0.234-0.243c0-0.135-0.104-0.245-0.234-0.245H7.945C7.815,9.556,7.709,9.666,7.709,9.801z  M11.611,10.993H7.945c-0.13,0-0.235,0.108-0.235,0.244c0,0.135,0.105,0.244,0.235,0.244h3.667c0.131,0,0.234-0.109,0.234-0.244	C11.846,11.102,11.742,10.993,11.611,10.993z M11.611,12.356H7.945c-0.13,0-0.235,0.108-0.235,0.244	c0,0.133,0.105,0.243,0.235,0.243h3.667c0.131,0,0.234-0.11,0.234-0.243C11.846,12.465,11.742,12.356,11.611,12.356z"/>
                    </svg> </button>
                  </li>
                  <li> 
                    <!--<button id="tool_delete" class="disabled" title="Delete">-->
                    <button id="tool_clear" class="" title="Delete"> <svg class="top-navigation-icon" viewBox="0 0 15 15">
                    <path  d="M10.162,13.148c0.265,0,0.479-0.208,0.479-0.463V7.131c0-0.255-0.215-0.463-0.479-0.463 S9.683,6.875,9.683,7.131v5.555C9.683,12.94,9.897,13.148,10.162,13.148z M13.041,2.039h-2.879V1.113c0-0.511-0.43-0.925-0.96-0.925 H6.324c-0.531,0-0.96,0.414-0.96,0.925v0.926H2.486c-0.53,0-0.959,0.415-0.959,0.926V3.89c0,0.511,0.429,0.926,0.959,0.926v8.332 C2.486,14.17,3.345,15,4.405,15h6.716c1.06,0,1.92-0.83,1.92-1.852V4.816C13.57,4.816,14,4.401,14,3.89V2.964 C14,2.453,13.57,2.039,13.041,2.039z M6.324,1.576c0-0.255,0.215-0.463,0.479-0.463h1.919c0.265,0,0.479,0.208,0.479,0.463v0.462 c-0.465,0-2.878,0-2.878,0V1.576z M12.081,13.148c0,0.511-0.429,0.926-0.96,0.926H4.405c-0.53,0-0.959-0.415-0.959-0.926V4.816	h8.635V13.148z M12.562,3.89H2.965c-0.264,0-0.479-0.207-0.479-0.463c0-0.255,0.215-0.463,0.479-0.463h9.596	c0.265,0,0.479,0.208,0.479,0.463C13.041,3.683,12.826,3.89,12.562,3.89z M5.364,13.148c0.265,0,0.479-0.208,0.479-0.463V7.131	c0-0.255-0.214-0.463-0.479-0.463S4.885,6.875,4.885,7.131v5.555C4.885,12.94,5.099,13.148,5.364,13.148z M7.764,13.148	c0.265,0,0.479-0.208,0.479-0.463V7.131c0-0.255-0.214-0.463-0.479-0.463c-0.266,0-0.48,0.208-0.48,0.463v5.555	C7.283,12.94,7.498,13.148,7.764,13.148z"/>
                    </svg> </button>
                    <!--this button is placed to work delete key on keyboard-->
                    <button id="tool_delete_multi" style="display:none;"></button>
                  </li>
                  <li>
                    <button id="zoomButton" title="Set Zoomlevel">
                    <div><i class="fa fa-search-plus"></i> </div>
                    </button>
                    <div id="zoomOptions" style="display:none">
                      <input class="inputBoxMedium" id="zoom" size="3" value="100" type="text" style="display:none" />
                      <ul>
                        <li>400%</li>
                        <li>200%</li>
                        <li>100%</li>
                        <li>50%</li>
                        <li>25%</li>
                        <li id="fit_to_canvas" data-val="canvas">Fit to canvas</li>
                        <!--<li id="fit_to_sel" data-val="selection">Fit to selection</li>--> 
                        <!--<li id="fit_to_layer_content" data-val="layer">Fit to layer content</li>--> 
                        <!--<li id="fit_to_all" data-val="content">Fit to screen</li>-->
                      </ul>
                    </div>
                  </li>
                  <li>
                    <button id="info" title="Product Info"> <svg viewBox="0 0 18 18" class="optoin-control-icons">
                    <path d="M8.952-0.001C3.981,0.024-0.027,4.077,0,9.048C0.027,14.019,4.08,18.026,9.048,18	c4.973-0.027,8.979-4.078,8.952-9.049C17.974,3.979,13.925-0.027,8.952-0.001z M9.041,16.534C4.88,16.556,1.493,13.202,1.469,9.04 c-0.021-4.16,3.33-7.553,7.49-7.575c4.161-0.023,7.552,3.334,7.577,7.493C16.557,13.12,13.2,16.511,9.041,16.534z M10.375,12.938 L10.337,6.21l-2.53,0.015v0.004L6.865,6.232l0.007,1.473l0.943-0.006l0.028,5.254l-1,0.003l0.008,1.407l1-0.005v0.012l2.531-0.016	v-0.01l0.826-0.006L11.2,12.934L10.375,12.938z M9.051,5.217c0.85-0.003,1.36-0.572,1.356-1.271C10.385,3.23,9.887,2.685,9.07,2.688	C8.255,2.693,7.724,3.246,7.728,3.962C7.732,4.66,8.25,5.223,9.051,5.217z"/>
                    </svg> </i></button>
                  </li>
                  
                  <!--<div class="left-panel no-display">
				<li><button id="layerPanel" title="Layer Panel"><div><i class="lt_layerPanel">&nbsp;</i></div></button></li>
				<li><button id="alignPanel" class="disabled" title="Align Panel"><div><i class="lt_alignPanel">&nbsp;</i></div></button></li>
				<li> 
				  <button id="tool_group" class="disabled" title="Group"><div><i class="lt_tool_group">&nbsp;</i></div></button>
				  <button id="tool_ungroup" title="Ungroup" class="disabled" style="display:none"><div><i class="lt_tool_ungroup">&nbsp;</i></div></button>
				</li>            
				
				<li>
				  <button id="tool_clone" title="Duplicate" class="disabled"><div><i class="lt_tool_clone">&nbsp;</i></div></button>
				</li>
				<li>
				  <button id="tool_cut" title="Cut" class="disabled"><div><i class="lt_tool_cut">&nbsp;</i></div></button>
				</li>
				<li>
				  <button id="tool_copy" title="Copy" class="disabled"><div><i class="lt_tool_copy">&nbsp;</i></div></button>
				</li>
				
				<li>
				  <button id="tool_clear" title="Delete All"><div><i class="lt_tool_clear">&nbsp;</i></div></button>
				</li>
				<li>
				  <button title="Studio Help" id="Help"><div><i class="lt_tool_help">&nbsp;</i></div></button>
				</li>
			</div>-->
                </ul>
              </section>
              <section class="slide-share-panel">
                <nav id="pickside-panel" class="">
                  <div id="previousSide" class="button"> <a href="javascript:void(0);"> <i class="fa fa-angle-left"></i> </a> </div>
                  <!-- Object align Panel -->
                  <div class="object-align">
                    <ul id="productSides"  >
                      <?php //echo $text_area; class="pickcolor-list" ?>
                    </ul>
                  </div>
                  <div id="nextSide" class="button"> <a  href="javascript:void(0);"> <i class="fa fa-angle-right"></i> </a> </div>
                </nav>
                <div class="next_previous_slide">
                  <ul>
                    <!--<li id="previousSide">
					<a href="#">
							<i class="fa fa-angle-left"></i>
					</a>
				</li>

				<li id="nextSide">
					<a  href="#">
						<i class="fa fa-angle-right"></i>
					</a>
				</li>-->
                  </ul>
                </div>
              </section>
              <section class="center-share-panel"> 
                <!--<div class="button-area">
            <ul>			
              <li>
                <button id="zoomButton" title="Set Zoomlevel">
					<div>
						<span id="productZoomCaption">
								<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 20 20" enable-background="new 0 0 20 20" xml:space="preserve">
									<g>
										<path  fill="#0695D1" d="M19.782,18.562l-4.601-4.485c1.36-1.498,2.212-3.443,2.212-5.597C17.394,3.803,13.492,0,8.697,0 C3.902,0,0,3.803,0,8.479c0,4.676,3.901,8.479,8.697,8.479c1.965,0,3.759-0.662,5.216-1.738l4.647,4.532 C18.729,19.918,18.95,20,19.172,20c0.221,0,0.441-0.082,0.61-0.248C20.12,19.425,20.12,18.892,19.782,18.562z M8.698,15.273 c-3.844,0-6.97-3.047-6.97-6.794c0-3.746,3.126-6.796,6.97-6.796c3.842,0,6.969,3.05,6.969,6.796C15.667,12.227,12.54,15.273,8.698,15.273z M9.136,5.475H8.009v2.35H5.615V9h2.394v2.375h1.127V9h2.394V7.825H9.136V5.475z"/>
									</g>
								</svg>
						</span>
					</div>
                </button>
              </li>
            </ul>
		  </div>
		  <div id="zoomOptions" style="display:none">
			<input class="inputBoxMedium" id="zoom" size="3" value="100" type="text" style="display:none" />
			<ul>
			  <li>400%</li>
			  <li>200%</li>
			  <li>100%</li>
			  <li>50%</li>
			  <li>25%</li>
			  <li id="fit_to_canvas" data-val="canvas">Fit to canvas</li>
			  <li id="fit_to_sel" data-val="selection">Fit to selection</li>
			  <li id="fit_to_all" data-val="content">Fit to screen</li>
			</ul>
		  </div>--> 
              </section>
              <section class="right-share-panel">
                <div class="button-area">
                  <ul>
                    <li>
                      <button title="Save Design" id="save_template"> <span class="d_savenote"><i class="fa fa-floppy-o"></i></span> </button>
                    </li>
                    <li>
                      <div title="Import SVG" id="import_svg_btn" style="padding-top:7px;">
                        <input type="file" id="import_svg" autocomplete="off">
                        <div>
                          <label for="import_svg" class="btn btn-larges"><i class="fa fa-inbox"></i></label>
                        </div>
                      </div>
                    </li>
                    <li>
                      <button title="Exit" id="exit_studio"> <span class="d_exit"><i class="fa fa-sign-out"></i></span> </button>
                    </li>
                  </ul>
                </div>
              </section>
            </section>
            
            <!-- SVG Editor -->
            <form id="manage_side" method="post">
              <input type="hidden" value="2" id="number_of_pages" name="number_of_pages" autocomplete="off">
              <!--<input type="hidden" value="1" id="current_page" name="current_page" autocomplete="off">-->
              <div style="display:none;" title="" id="pages_data"> <?php echo $text_area; ?> </div>
              <textarea id="svg_source_textarea" style="display:none;" spellcheck="false"></textarea>
            </form>
            <div id="svg_editor">
              <div id="rulers" style="display:none"><!-- Ruler will display if showRulers is true in svgEditor.setConfig   -->
                <div id="ruler_corner"></div>
                <div id="ruler_x">
                  <div>
                    <canvas height="15"></canvas>
                  </div>
                </div>
                <div id="ruler_y">
                  <div>
                    <canvas width="15"></canvas>
                  </div>
                </div>
              </div>
              <div id="workarea">
                <style id="styleoverrides" type="text/css" media="screen" scoped>
</style>
                <div id="svgcanvas" style="position:relative">
                  <div id="product-image" > <svg id="productSvg" height="485" width="400" viewBox="0 0 400 485" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <defs>
                      <filter id="colorMat" color-interpolation-filters="sRGB">
                        <feColorMatrix values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0" type="matrix" id="feColorMatrix"></feColorMatrix>
                      </filter>
                    </defs>
                    <?php					
					/*if($isMultiColor == 'yes'){				
						$filter = '';
					}else{
						$filter = 'url(#colorMat)';
					}
					for($i=1; $i<=$no_of_side; $i++){
						do{
							if($Image[$i]!='')
							{
								list($imagewidth, $imageheight, $type, $attr) = getimagesize($Image[$i]);
							}
						}while($imagewidth == '');
						$ratio = $imagewidth/$imageheight;
						if($imagewidth >= $imageheight)	{
							$imagewidth = 400; 
							$imageheight = $imagewidth/$ratio;
							if($imageheight > 485)	{
								$imageheight = 485; 
								$imagewidth = $ratio*$imageheight;
							}
						}
						else
						{
							$imageheight= 485;	
							$imagewidth = $ratio*$imageheight;
							if($imagewidth > 400)	{
								$imagewidth = 400; 
								$imageheight = $imagewidth/$ratio;
							}
						}
						$display_style = '';
						if($i != 1) $display_style = "display:none;"*/ ?>
                    <!--<image  class="main_image" filter="<?php //echo $filter; ?>" id="img_<?php //echo $i;?>" xlink:href="<?php //echo $Image[$i]; ?>" height="<?php //echo $imageheight."px"?>" width="<?php //echo $imagewidth."px"?>" style="position:relative;<?php //echo $display_style;?>"/>-->
                    <?php //} ?>
                    </svg> </div>
                </div>
              </div>
            </div>
          </section>
          <div class="clear"></div>
          <nav class="" id="pickcolor-panel">
            <div class="">
              <?php /*?><label class="label"><?php echo Mage::helper('design')->__('PICK COLOR');?></label><?php */?>
              <label class="label"><?php echo('PICK COLOR');?></label>
              <button id="tool_pick_color_close"></button>
              <div id="prevColor"><i class="fa fa fa-angle-left"></i></div>
              <div class="gallery">
                <ul class="pickcolor-list">
                  <?php //echo $pickcolorarray;?>
                </ul>
                <!-- show colors -->
                <input type="hidden" value="<?php //echo $colorId; ?>" name="current_product_colorid" id="current_product_colorid" />
                <input type="hidden" value="<?php //echo $currentcolohash; ?>" name="current_product_colorhash" id="current_product_colorhash" />
              </div>
              <div id="nextColor"><i class="fa fa fa-angle-right"></i></div>
            </div>
          </nav>
          <div id="objectLock" class="dragablePanel" align="center" style="display:none;">
            <div class="move-object-panel">
              <div class="mop-heading">Lock / Unlock</div>
              <div class="lock-unlock-range" align="center"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="clear"></div>
    </div>
    <div id="use_panel" style="display:none">
      <div class="push_button" id="tool_unlink_use" title="Break link to reference element (make unique)"></div>
    </div>
    <div id="dialog_box">
      <div id="dialog_box_overlay"></div>
      <div id="dialog_container">
        <div id="dialog_content" align="center"></div>
        <div id="dialog_buttons" align="center"></div>
      </div>
    </div>
    <div class="upload_custom_window global-pop-windows" id="art_clipart"  style="display:none;">
      <div class="global-pop-windows-overlay"></div>
      <div class="global-pop-windows-container global-max-width">
        <div class="closebar_button">
          <button  onClick="closeMe(this);"><i class="fa fa-close"></i></button>
        </div>
        <p class="heading">CLIPART PANEL</p>
        <div class="popup-content">
          <div class="proimage">
            <p id="productColor" class="popupheading clr p5">Colors</p>
            <div align="center">
              <ul class="colorpalet">
                <?php //echo $pickcolor_multi; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="svg_image_upload" style="display:none;">
      <div id="svg_image_upload_overlay"></div>
      <div id="svg_image_upload_container" >
        <div id="tool_image_upload_back" class="toolbar_button">
          <button id="tool_image_upload_cancel"></button>
        </div>
        <p id="addImagePopupcaption" class="headingtwo">ADD IMAGE</p>
      </div>
    </div>
    
    <!--<div id="svg_beforeaddtocart">
  <div id="svg_beforeaddtocart_overlay"></div>
  <div id="svg_beforeaddtocart_container" class="ui-draggable">
    <div id="tool_beforeaddtocart_back" class="toolbar_button">
      <button id="tool_beforeaddtocart_cancel"></button>
    </div>
    <p id="addToCartCaption" class="headingtwo">ADD TO CART</p>
    
  </div>
</div>-->
    <div id="imageEffect_window" style="display:none;">
      <div class="window_overlay"></div>
      <div class="window_container global-pop-windows-container" style="width:852px; max-height:660px;top:15px;">
        <div class="closebar_button">
          <button onclick="closeMe(this);"><i class="fa fa-close"></i></button>
        </div>
        <p class="new-heading pop-heading-line" id="imageEffectCaption">Image Effect</p>
        <div>
          <div>
            <div id="ie_preview"></div>
            <div class="filters-button" style="margin-top:15px;">
              <div class="tabCon"> <span id="tabShapes">Mask</span> <span id="tabFilters">Effects</span> <span id="tabColors">Custom Effects</span> </div>
              <div class="clearer">&nbsp;</div>
              <div>
                <div id="ie_shapes" class="photo_filters_option"></div>
                <div id="ie_filters" class="photo_filters_option" style="display:none;padding-top: 10px;padding-bottom: 10px;"></div>
                <div id="ie_colorChanger" class="photo_filters_option" style="display:none;">
                  <div class="colorCon"> </div>
                </div>
              </div>
            </div>
            <div class="buttonHolder" align="center"> </div>
            <div id="ie_colorpicker" class="imageEffect_borderpicker"> </div>
          </div>
        </div>
      </div>
    </div>
    <div id="pickDesignColor_window" style="display:none;">
      <div class="window_overlay" style="z-index:21;"></div>
      <div class="window_container" style="width: 700px; height:556px; z-index:21;">
        <div class="toolbar_button" >
          <button onclick="closeMe(this);"> X </button>
        </div>
        <p class="headingtwo" id="pickDesignColorCaption">Pick Color From Design</p>
        <div>
          <div style="text-align:center;">
            <canvas id="pickDesignColorCanvas" width="500" height="400"></canvas>
          </div>
          <div class="pickDesignColorContainer">
            <div><span id="colorUnderCursor">Color Under Cursor:</span><span class="colorSwatchMouseMove"></span></div>
            <div><span id="selectedColor">Selected Color:</span><span class="colorSwatch"></span></div>
            <div>
              <input id="pickDesignColorButton" type="button" class="" value="Pick Color"/>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="image_quality_window" style="display:none;">
      <div class="window_overlay"></div>
      <div class="window_container">
        <div class="toolbar_button" >
          <button onclick="closeMe(this);"> X </button>
        </div>
        <p class="headingtwo" id="imageQualityInfoCaption">About Your Image Print Quality</p>
        <div class="window_content">
          <div id="imageQualityInfoContent"> 
            <!--content goes here--> 
            The image quality indicator lets you know if the image you have loaded has the right resolution for printing. If your image quality is poor we recommend that you upload a new image with higher resolution.
            
            Our graphics team checks every image submitted for resolution and quality requirements </div>
        </div>
      </div>
    </div>
    <div id="upload_info_hd_window" style="display:none;">
      <div class="window_overlay"></div>
      <div class="window_container">
        <div class="toolbar_button" >
          <button onclick="closeMe(this);"> X </button>
        </div>
        <p class="headingtwo" id="imageGalleryPopupInfoCaption">Shows images uploaded by user</p>
        <div class="window_content">
          <div id="imageGalleryPopupInfoContent" style="font-size:16px; text-align:left;"> 
            <!--content goes here--> 
            <B>For anonymous users:</B> The list is maintained for current session of design studio only.<br />
            <br />
            <B>For registered users after login:</B> It maintains the list of all images uploaded by user in several logged-in sessions. User can also attach a high resolution vector source file in allowed formats CDR/PSD/AI/PDF/EPS along with the raster image by clicking on “HD” button. You can see a right symbol with “Upload image” button to indicate images who already have the high resolution image uploaded, which can be further replaced if needed. The high resolution source file is sent to the admin once an order is placed for reference.<br />
            <br />
            PLEASE LOG-IN TO MAINTAIN YOUR IMAGE GALLERY AND ATTACH HIGH RESOLUTION SOURCE FILES WITH UPLOADED IMAGES. </div>
        </div>
      </div>
    </div>
    <div id="upload_info_window" style="display:none;">
      <div class="window_overlay"></div>
      <div class="window_container">
        <div class="toolbar_button" >
          <button onclick="closeMe(this);">X</button>
        </div>
        <!--<p class="headingtwo" id="uploadImageInfoPopupCaption">Upload Info</p>-->
        
        <div class="window_content">
          <div> 
            <!--content goes here-->
            <div class="buttons">
              <div class="image-upload-image" align="left"></div>
              <div class="image-upload-section" align="left">
                <div class="image-upload-block">
                  <div id="supportedFormatCaption"><b class="image-instruction-heading">Supported formats</b> 1) jpeg 2) jpg 3) png </div>
                </div>
                <div class="image-upload-block">
                  <div id="optimalResolutionCaption"><b class="image-instruction-heading">Optimal resolution</b>1500 x 1500 Pixel</div>
                </div>
                <div class="image-upload-block">
                  <div id="recommendedSizeCaption"><b class="image-instruction-heading">Recommended size</b> Less than 5 mb</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- zubair-->
    <div class="change_product global-pop-windows" style="display:none;" id="change-products-setting">
      <div class="global-pop-windows-overlay"></div>
      <div class="global-pop-windows-container change-product-container">
        <p id="changeproductCaption" class="heading">PRODUCT</p>
        
        <!--<p class="" id="prodCon_label">Browse By Category</p>-->
        <div class="closebar_button">
          <button  onClick="closeMe(this);"><i class="fa fa-close"></i></button>
        </div>
        <div class="popup-content">
          <div id="addproduct-panel">
            <div class="box-outer">
              <div class="aproduct-data">
                <label class="label">Choose Product</label>
                <button id="tool_choose_prod_close"></button>
                
                <!-- commented to hide the dropdown for product category--> 
                <!--<div class="input-area">
              <div class="field-raw">
                <div class="product-type">
                  <select id="prod_type" class="select-small">
                  </select>
                </div>
              </div>
            </div>--> 
              </div>
              <div id="producttree">
                <p class="" id="prodCat_label">Browse By Category</p>
                <div id="product-content"></div>
              </div>
              <!--<div  id="chooseProdPopup" class="window-sbtitle">Choose Product</div>-->
              <div id="productresult">
                <ul id="productconatiner" class="clearfix">
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="upload_custom_window global-pop-windows" style="display:none;" id="text_popup">
      <div class="global-pop-windows-overlay"></div>
      <div class="global-pop-windows-container text-popup-width">
        <div class="closebar_button">
          <button onClick="closeMe(this);"><i class="fa fa-close"></i></button>
        </div>
        <p class="heading" id="addTextPopupCaption">Add Text</p>
        <textarea rows="1" cols="42" id="add_text"> </textarea>
        <button id="btnAddText" title="Add Text">Add Text</button>
      </div>
    </div>
    <div class="upload_custom_window global-pop-windows" style="display:none;" id="images-upload">
      <div class="global-pop-windows-overlay"></div>
      <div class="global-pop-windows-container global-max-width">
        <div class="closebar_button">
          <button onClick="closeMe(this);svgCanvas.clearSelection();"><i class="fa fa-close"></i></button>
        </div>
        <p class="heading" id="uploadImageInfoPopupCaption">Upload</p>
        <span class="heading" id="uploadImageInstructionCaption">Upload</span> 
        <!--<div class="buttons">
		<button id="galleryBtn" class="button"> <i class="fa fa-picture-o"></i><span id="galleryBtnPopup">GALLERY</span></button>
        <button id="uploadImgBtn" class="button"><i class="fa fa-cloud-upload"></i><span id="uploadBtnPopup">UPLOAD</span></button>
      </div>-->
        <div class="popup-content">
          <ul class="imgUploadInstruction">
            <li id="imageUploadPrecaution1">- We'll store up to ( 150 ) photos for you in your Account.</li>
            <li id="imageUploadPrecaution2">- Optimal resolution would be 1500 x 1500 Pixel and</li>
            <li id="imageUploadPrecaution3">- Supported formats ( jpeg, jpg,  png - up to ( 30 ) MB each)</li>
          </ul>
          <div class="image-upload-panel">
            <div class="upload-instruction" id="uploadInstLabel"><!--<b><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;If you have the rights to use these images.</b>-->
              <input type="checkbox" id="ihavetheright">
              <label id="rightsCaption"><b>I have the rights to use these images.</b></label>
            </div>
            <div class="upload-images">
              <div class="social-media-import-section" id="media_btnCon"> <a  id="galleryBtn" class="import_galleryBtn sc-media-uploadbtn">Gallery</a>
                <div align="center" class="uploadimage-btn sc-media-uploadbtn">
                  <button  type="button" id="upload_img_show">
                  <div id="uploadImageCaption"> </div>
                  </button>
                </div>
                <?php //if($configurefeature['6'] == 1) { ?>
                <a  id="import_flickr_btn" class="import_flickr sc-media-uploadbtn">Flickr</a> <a  id="import_picasa_btn" class="import_picasa sc-media-uploadbtn">Picasa</a> <a id="import_instagram_btn" class="import_instagram sc-media-uploadbtn">Instagram</a>
                <?php //} ?>
                <?php //if($configurefeature['4'] == 1) { ?>
                <a class="add_Qrcode sc-media-uploadbtn" id="qrCode">QRCode</a>
                <?php //} ?>
              </div>
              <div id="images_loaded"  class="image-storage-block">
                <div class="range-blocks">
                  <div class="caption-section">
                    <div class="inst_image" id="inst_image_hd">i</div>
                  </div>
                </div>
                <div class="uploadimage-area"> 
                  <!--<div id="pl_fileuploader">x</div>-->
                  <div id="gallery_warn_msg_con"><i class="fa fa-picture-o"></i><br />
                    <p id="gallery_warn_msg">There is no image in gallery. You can upload image by clicking on Upload button.</p>
                    <br/>
                    <!--<input type="button" value="Login" name="btn_login_gallery" id="btn_login_gallery" class="button" autocomplete="off">--> 
                  </div>
                  <div id="uploader" style="display:none;">
                    <div id="filelist" class="panel">No runtime found.</div>
                    <p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
                  </div>
                  <div class="tabcntent">
                    <ul id="flickerresult">
                    </ul>
                    <form id="qrcodeform" name="qrcodeform" method="POST" >
                      <div class="qrcode-section">
                        <div class="caption-section">
                          <ul id="qrcodecontainer" class="t-shirt-list">
                            <label id="qrcodeDataTypeCaption" class="caption">Select Data Type</label>
                            <select id="qrdatatype" name="qrdatatype" onchange="changeqrform()">
                              <option id="websiteUrl" selected="selected" value="1">Website URL'</option>
                              <option id="youtubeVideo" value="2">YouTube Video</option>
                              <option id="plaintext" value="3">Plain Text</option>
                              <option id="emailAdd" value="4">Email Address</option>
                              <!--<option value="5">Contatct Details (Vcard)</option>-->
                              <option id="telephone" value="6">Telephone Number</option>
                              <option id="emailMsg" value="7">Email Message</option>
                              <option id="socialMedia" value="8">Social Media</option>
                              <option id="gmap" value="9">Google Maps Location</option>
                            </select>
                            <div id="qr1">
                              <div class="inputfield">
                                <label id="websiteCaption" class="caption">Website URL:</label>
                                <input class="required-entry validate-url" type="text" name="websiteurl" id="websiteurl"/>
                              </div>
                            </div>
                            <div id="qr2">
                              <div class="inputfield">
                                <label id="videoIdCaption" class="caption">Video ID:</label>
                                <input class="validate-group  validate-number" type="text" name="youtube_video_id" id="youtube_video_id"/>
                              </div>
                              <div class="inputfield">
                                <label id="videoUrlCaption" class="caption">Video URL:</label>
                                <input class="validate-group validate-url" type="text" name="youtube_video_url" id="youtube_video_url"/>
                              </div>
                            </div>
                            <div id="qr3">
                              <div class="inputfield">
                                <label id="textCaption" class="caption">TEXT:</label>
                                <textarea class="required-entry" type="text" style="width:208px;" name="plaintextdata" id="plaintextdata"></textarea>
                              </div>
                            </div>
                            <div id="qr4">
                              <div class="inputfield">
                                <label id="emailaddreCaption" class="caption">Email Address:</label>
                                <input class="required-entry validate-email" type="text" name="email_address" id="emailaddress4"/>
                              </div>
                            </div>
                            <div id="qr6">
                              <div class="inputfield">
                                <label id="telephoneCaption" class="caption">Telephone Number:</label>
                                <input class="required-entry validate-phoneStrict" type="text" name="telephone_no" id="telephoneno6"/>
                              </div>
                            </div>
                            <div id="qr7">
                              <div class="inputfield">
                                <label id="subjectCaption" class="caption">Subject:</label>
                                <input class="required-entry" type="text" name="subject" id="subject7"/>
                              </div>
                              <div class="inputfield">
                                <label id="bodyCaption" class="caption">Body:</label>
                                <textarea class="required-entry" name="message" style="width:208px;" id="body7" ></textarea>
                              </div>
                            </div>
                            <div id="qr8">
                              <div class="inputfield">
                                <label id="socialMediaCaption" class="caption">Social Media:</label>
                                <select  name="socialmedia8" id="socialmedia8" onchange="changetheprofilename()">
                                  <option id="twitterProfileCaption" value="sm1">Twitter Profile</option>
                                  <option id="facebookProfileCaption" value="sm2">Facebook Profile</option>
                                  <option id="myspaceProfileCaption" value="sm3">Myspace Profile</option>
                                  <option id="linkedlinProfileCaption" value="sm4">LinkedIn Profile</option>
                                </select>
                              </div>
                              <div class="inputfield">
                                <label id="profileCaption" class="caption">Profile:</label>
                                <input class="required-entry" type="text" name="Twitter Profile" id="profile8"/>
                              </div>
                            </div>
                            <div id="qr9">
                              <div class="inputfield">
                                <label id="gmaplocationCaption" class="caption">Google Maps Location:</label>
                                <input type="text" class="required-entry" name="google_map_location" id="googlemaplocation9"/>
                              </div>
                            </div>
                          </ul>
                          <div class="inputfield">
                            <label id="rqcodeColorCaption" class="caption">Color Code:</label>
                            <input type="text" name="qrcolorcode" id="qrcolorcode" class="color" value="#000000" >
                            <div class="color_tool" id="tool_qr_color">
                              <div class="color_block">
                                <div id="qr_stroke_bg"></div>
                                <div id="qr_color" class="color_block" title="Change Qr color"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div id="qr_colorpicker_con" style="display:none"> 
                          <!--<button id="qr_cpclose" onclick="closeQrColorPanel();">
							<i class="fa fa-close"></i>
						</button>-->
                          <div id="qr_colorpicker" class="colorpicker"></div>
                          <!-- added by Gul --> 
                        </div>
                        <!-- <button id="tool_qrCode" onclick="qrcodeFormSubmit();"  type="submit" class="button">Generate</button>  -->
                        <input id="tool_qrCode" type="submit" class="button" value="Generate">
                        <!--<div>
                      <ul id="QRcodeImage">
                      </ul>
                    </div>--> 
                      </div>
                    </form>
                    <div id="flickr_window">
                      <div id="flickr_window_container" class="global-pop-windows-container flickr_window_container">
                        <div id="tool_save_design_window_back" class="toolbar_button no-display">
                          <button id="tool_flickr_window_cancel"></button>
                        </div>
                        <div class="flickr_symbol">&nbsp;</div>
                        <p id="flickrUploadCaption" class="heading">Are Your Photos Stored in Your Flickr Account?</p>
                        <div class="save-design-table">
                          <div class="uploader-path"> <i class="fa fa-search"></i>
                            <input type="text" size="25" id="flicker_import" autocomplete="off">
                            <input type="button" value="GO!" class="enter" id="flicker_go">
                          </div>
                          <!--<div id="import_error"></div>-->
                          
                          <div id="flickr_pager"></div>
                          <div class="flickr_holder"></div>
                        </div>
                      </div>
                    </div>
                    <div id="instagram_window" style="display:none">
                      <div id="instagram_window_container" class="global-pop-windows-container flickr_window_container">
                        <div id="tool_save_design_window_back" class="toolbar_button no-display">
                          <button id="tool_instagram_window_cancel"></button>
                        </div>
                        <div class="instagram_symbol">&nbsp;</div>
                        <p id="instagramUploadCaption" class="headingtwo">upload your Instagram photos</p>
                        <div class="save-design-table"> 
                          <!--<a class="prev srbutton2">prev</a> <a class="next srbutton2">next</a>-->
                          <div class="pager"> <a class="prev start-page">&larr; prev</a> <a class="next end-page">next &rarr;</a> </div>
                          <!--<div id="import_error"></div>-->
                          <div class="instagram_holder"></div>
                        </div>
                      </div>
                    </div>
                    <div id="picasa_window">
                      <div id="picasa_window_container" class="global-pop-windows-container picasa_window_container">
                        <div id="tool_save_design_window_back" class="toolbar_button no-display">
                          <button id="tool_picasa_window_cancel" class="no-margin"></button>
                        </div>
                        <div class="picasa_symbol">&nbsp;</div>
                        <p id="picasaUploadCaption" class="heading">Are Your Photos Stored in Your Picasa Account?</p>
                        <div class="save-design-table">
                          <div class="uploader-path"> <i class="fa fa-search"></i>
                            <input type="text" size="25" id="picasa_import" autocomplete="off">
                            <input type="button" value="GO!" class="enter" id="picasa_go">
                          </div>
                          <!--<div id="import_error"></div>-->
                          <div class="picasa_holder"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="image_panel">
              <div class="toolset_image" style="display:none;">
                <label><span id="iwidthLabel" class="icon_label"></span>
                  <input id="image_width" class="attr_changer" title="Change image width" size="3" data-attr="width"/>
                </label>
                <label><span id="iheightLabel" class="icon_label"></span>
                  <input id="image_height" class="attr_changer" title="Change image height" size="3" data-attr="height"/>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="upload_custom_window" style="display:none;" id="navaart">
      <div class="global-pop-windows-overlay"></div>
      <div class="global-pop-windows-container clipart-container">
        <div class="closebar_button">
          <button onClick="closeMe(this);"><i class="fa fa-close"></i></button>
        </div>
        
          <div class="caption-tabs">
            <div class="window-sbtitle" id="chooseartpopup">Choose Art</div>
          </div>
        <div class="occupied-shape">
          <div id="tools_left" class="tools_panel">
            <div class="tools_left_label">Shape Tool</div>
            <div class="tool_button" id="tool_fhpath" title="Pencil Tool"></div>
            <div class="tool_button" id="tool_line" title="Line Tool"></div>
            <div class="tool_button flyout_current" id="tools_rect_show" title="Square/Rect Tool">
              <div class="flyout_arrow_horiz"></div>
            </div>
            <div class="tool_button flyout_current" id="tools_ellipse_show" title="Ellipse/Circle Tool">
              <div class="flyout_arrow_horiz"></div>
            </div>
            <div style="display: none; position:relative; border:1px solid">
              <div id="tool_rect" title="Rectangle"></div>
              <div id="tool_square" title="Square"></div>
              <div id="tool_fhrect" title="Free-Hand Rectangle"></div>
              <div id="tool_ellipse" title="Ellipse"></div>
              <div id="tool_circle" title="Circle"></div>
              <div id="tool_fhellipse" title="Free-Hand Ellipse"></div>
            </div>
          </div>
        </div>
        
        <!--<p class="heading" id="clipartcaption">CLIPART</p>-->
        
        <p class="subcategoryheading" id="clipCat_label">Browse By Category</p>
        
        <div class="popup-content">
          <div class="add-art-panel">
            <div class="clipartcategory">
              
              <div id="clipart-content"></div>
            </div>
            <div class="field-raw" id="clipartCategory">
              <div id="clipartSubCategory"></div>
            </div>
          </div>
          <div class="add-art-product">

            <div id="clipartscroller">
              <ul id="clipartcontainer" class="art-list">
                Item Not Found
              </ul>
            </div>
          </div>
        </div>
        
        <!--adding shape glyout--> 
        
      </div>
    </div>
    <nav class="upload_custom_window" id="photoBox-panel" style="display:none;">
      <div class="global-pop-windows-overlay"></div>
      <div class="global-pop-windows-container photobox-container">
        <div class="closebar_button">
          <button onclick="closeMe(this);"><i class="fa fa-close"></i></button>
        </div>
        <div id="photoBoxLabel" class="heading">Photo Box</div>
        <div>
          <div> <br class="clearer">
            <div class="tool_button" onclick="photoCollageExt.drawShapeOnCanvas('heart');"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><svg viewBox="-15 -15 330 330">
              <path fill="none" stroke="#000000" stroke-width="10" d="m150,73c61,-175 300,0 0,225c-300,-225 -61,-400 0,-225z"/>
              </svg></svg> </div>
            <div class="tool_button" title="cloud" onclick="photoCollageExt.drawShapeOnCanvas('cloud');"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><svg viewBox="-15 -15 330 330">
              <path fill="none" stroke="#000000" stroke-width="10" d="m182.05086,34.31005c-0.64743,0.02048 -1.27309,0.07504 -1.92319,0.13979c-10.40161,1.03605 -19.58215,7.63722 -24.24597,17.4734l-2.47269,7.44367c0.53346,-2.57959 1.35258,-5.08134 2.47269,-7.44367c-8.31731,-8.61741 -19.99149,-12.59487 -31.52664,-10.72866c-11.53516,1.8662 -21.55294,9.3505 -27.02773,20.19925c-15.45544,-9.51897 -34.72095,-8.94245 -49.62526,1.50272c-14.90431,10.44516 -22.84828,28.93916 -20.43393,47.59753l1.57977,7.58346c-0.71388,-2.48442 -1.24701,-5.01186 -1.57977,-7.58346l-0.2404,0.69894c-12.95573,1.4119 -23.58103,11.46413 -26.34088,24.91708c-2.75985,13.45294 2.9789,27.25658 14.21789,34.21291l17.54914,4.26352c-6.1277,0.50439 -12.24542,-0.9808 -17.54914,-4.26352c-8.66903,9.71078 -10.6639,24.08736 -4.94535,35.96027c5.71854,11.87289 17.93128,18.70935 30.53069,17.15887l7.65843,-2.02692c-2.46413,1.0314 -5.02329,1.70264 -7.65843,2.02692c7.15259,13.16728 19.01251,22.77237 32.93468,26.5945c13.92217,3.82214 28.70987,1.56322 41.03957,-6.25546c10.05858,15.86252 27.91113,24.19412 45.81322,21.38742c17.90208,-2.8067 32.66954,-16.26563 37.91438,-34.52742l1.82016,-10.20447c-0.27254,3.46677 -0.86394,6.87508 -1.82016,10.20447c12.31329,8.07489 27.80199,8.52994 40.52443,1.18819c12.72244,-7.34175 20.6609,-21.34155 20.77736,-36.58929l-4.56108,-22.7823l-17.96776,-15.41455c13.89359,8.70317 22.6528,21.96329 22.52884,38.19685c16.5202,0.17313 30.55292,-13.98268 36.84976,-30.22897c6.29684,-16.24631 3.91486,-34.76801 -6.2504,-48.68089c4.21637,-10.35873 3.96622,-22.14172 -0.68683,-32.29084c-4.65308,-10.14912 -13.23602,-17.69244 -23.55914,-20.65356c-2.31018,-13.45141 -11.83276,-24.27162 -24.41768,-27.81765c-12.58492,-3.54603 -25.98557,0.82654 -34.41142,11.25287l-5.11707,8.63186c1.30753,-3.12148 3.01521,-6.03101 5.11707,-8.63186c-5.93959,-8.19432 -15.2556,-12.8181 -24.96718,-12.51096z"/>
              </svg></svg> </div>
            <div class="tool_button" title="star" onclick="photoCollageExt.drawShapeOnCanvas('star_points_5');"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><svg viewBox="-15 -15 330 330">
              <path fill="none" stroke="#000000" stroke-width="10" d="m1,116.58409l113.82668,0l35.17332,-108.13487l35.17334,108.13487l113.82666,0l-92.08755,66.83026l35.17514,108.13487l-92.08759,-66.83208l-92.08757,66.83208l35.17515,-108.13487l-92.08758,-66.83026z"/>
              </svg></svg> </div>
            <div class="tool_button" title="circle" onclick="photoCollageExt.drawExtraShapes('circle');"> <svg height="24" width="24"><svg viewBox="-15 -15 330 330">
              <circle cx="140" cy="170" r="140"  fill="none" stroke="#000000" stroke-width="10" />
              </svg></svg> </div>
            <div class="tool_button" title="ellipse" onclick="photoCollageExt.drawExtraShapes('ellipse');"> <svg height="24" width="24"><svg viewBox="-15 -15 330 330">
              <ellipse cx="140" cy="170" rx="150" ry="100"  fill="none" stroke="#000000" stroke-width="10" />
              </svg></svg> </div>
            <div class="tool_button" title="rectangle" onclick="photoCollageExt.drawExtraShapes('rectangle');"> <svg height="24" width="24"><svg viewBox="-15 -15 330 330">
              <rect width="300" height="150" y="70" fill="none" stroke="#000000" stroke-width="10" />
              </svg></svg> </div>
            <div class="tool_button" title="square" onclick="photoCollageExt.drawExtraShapes('square');"> <svg height="24" width="24"><svg viewBox="-15 -15 330 330">
              <rect width="300" height="300" fill="none" stroke="#000000" stroke-width="10" />
              </svg></svg> </div>
          </div>
        </div>
      </div>
    </nav>
  </div>
  <div class="products-settings-panel global-pop-windows" style="display:none;" id="svg_docprops" >
    <div class="global-pop-windows-overlay"></div>
    <div class="global-pop-windows-container product-info-width">
      <p id="productNameInfo" class="heading"></p>
      <div class="closebar_button">
        <button onClick="closeMe(this);"><i class="fa fa-close"></i></button>
      </div>
      <div class="upload_custom_window">
        <div class="popup-content">
          <div class="proimage">
            <p id="productColor" class="pboxsubheading">Available Colors</p>
            <div align="center">
              <ul class="colorpalet">
                <?php //echo $pickcolor_multi; ?>
              </ul>
            </div>
          </div>
          <div class="prodescription">
            <div class="descriptioncontent">
              <p id="productInformation"class="pboxsubheading">Detail Information</p>
              <p id="productSku"class="cotxt">SKU</p>
              <!--<p id="productShortDesc"class="cotxt">Short Description</p>-->
              <p id="productDesc"class="cotxt">Long Description</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id ="cart_panel" class="products-settings-panel global-pop-windows" style="display:none;" >
    <div class="global-pop-windows-overlay"></div>
    <div class="global-pop-windows-container checkout-box-width">
      <div class="closebar_button">
        <button onClick="closeMe(this);"><i class="fa fa-close"></i></button>
      </div>
      <p class="heading" id="productsettingCaption">PRODUCT SETTING1</p>
      <div class="sambhav">
        <div id="addtocart-panel">
          <div id="cartarea" class="quantitysize">
            <div class="printingMethod-area">
              <div class="caption-section">
                <div id="printingMethodCaption" class="caption"><i class="fa fa-print"></i>Printing Method</div>
              </div>
            </div>
            <div class="caption-section">
              <div id="sizeQtyCaption" class="caption"><i class="fa fa-tag"></i>Size/Quantity</div>
            </div>
            <div class="border-area">
              <div class="object-inputs" id="objectinputsize">
                <?php //echo $sizecolorarray; ?>
              </div>
              <div id="designInfo" class="captionSmall">
                <div id="price_break"> </div>
                <div class="errornotinstock" id="errornotinstock"></div>
              </div>
              <div class="clear"></div>
            </div>
            <div class="caption-section">
              <div id="addNoteCaption" class="caption"><i class="fa fa-book"></i>Add Note</div>
            </div>
            <div>
              <textarea name="addnote" id="addnote"></textarea>
            </div>
            <div class="costing">
              <div class="buttons">
                <div id="addcart" class="button" style="display:none;">ADD TO CART</div>
              </div>
              <div class="clear"></div>
            </div>
          </div>
        </div>
        <div class="search">
          <div class="message" id="addtocartmessage"></div>
          <div class="proceedbtn">
            <button class="button" type="button" id="back_to_cart" onclick="$j('#svg_beforeaddtocart').hide();backtocart();">Back</button>
            <button class="button" type="button" id="cartProceed" onclick="$j('#svg_beforeaddtocart').hide();addtocart();">Proceed</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="svg_login_window" class="global-pop-windows">
    <div class="global-pop-windows-overlay"></div>
    <div id="svg_login_window_container" class="global-pop-windows-container global-max-width">
      <div id="tool_login_window_back" class="toolbar_button">
        <button id="tool_login_window_cancel"></button>
      </div>
      <p id="loginRegisterPopupCaption" class="heading">Login/Register</p>
      <div id="error_msg"></div>
      <div class="login-table">
        <div class="login" style="width:48%; float:left;">
          <label id="loginCaption" class="table-hd-cap">Log In</label>
          <form>
            <fieldset>
              <div class="div-table">
                <div class="div-table-row">
                  <div class="div-table-cell">
                    <label id="lEmailCaption">E-mail Address</label>
                  </div>
                  <div class="div-table-cell">
                    <input type="text" name="email_id" id="email_id" />
                  </div>
                </div>
                <div class="div-table-row">
                  <div class="div-table-cell">
                    <label id="lPasswordCaption">Password</label>
                  </div>
                  <div class="div-table-cell">
                    <input type="password" name="password" id="password" />
                  </div>
                </div>
                <div class="div-table-row">
                  <div class="div-table-cell">
                    <label><a id="forgetPasswordCaption" href="<?php //echo Mage::getUrl('customer/account/forgotpassword/');  ?>" target="_blank" >Forgot Password?</a></label>
                  </div>
                </div>
                <div class="div-table-row">
                  <div class="div-table-cell">&nbsp;</div>
                  <div class="div-table-cell">
                    <div class="proceedbtn">
                      <input type="button" class="button" id="btn_login" name="btn_login" value="Login" />
                      <input type="button" class="button" id="btn_cancel" name="btn_cancel" value="Cancel" />
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>
          </form>
        </div>
        <div class="register" style="width:48%; float:left;">
          <label id="registerCaption" class="table-hd-cap">Create an account</label>
          <form>
            <fieldset>
              <div class="div-table">
                <div class="div-table-row">
                  <div class="div-table-cell">
                    <label id="fNameCaption">First Name</label>
                  </div>
                  <div class="div-table-cell">
                    <input type="text" name="first_name" id="first_name" />
                  </div>
                </div>
                <div class="div-table-row">
                  <div class="div-table-cell">
                    <label id="lNameCaption">Last Name</label>
                  </div>
                  <div class="div-table-cell">
                    <input type="text" name="last_name" id="last_name" />
                  </div>
                </div>
                <div class="div-table-row">
                  <div class="div-table-cell">
                    <label id="rEmailCaption">E-mail Address</label>
                  </div>
                  <div class="div-table-cell">
                    <input type="text" name="reg_email_id" id="reg_email_id" />
                  </div>
                </div>
                <div class="div-table-row">
                  <div class="div-table-cell">
                    <label id="rPasswordCaption">Password</label>
                  </div>
                  <div class="div-table-cell">
                    <input type="password" name="reg_password" id="reg_password" />
                  </div>
                </div>
                <div class="div-table-row">
                  <div class="div-table-cell">
                    <label id="rConfirmPasswordCaption">Confirm Password</label>
                  </div>
                  <div class="div-table-cell">
                    <input type="password" name="conf_password" id="conf_password" />
                  </div>
                </div>
                <div class="div-table-row">
                  <div class="div-table-cell">&nbsp;</div>
                  <div class="div-table-cell">
                    <div class="proceedbtn">
                      <input type="button" id="btn_submit" name="btn_submit" value="Submit" class="button"/>
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div id="svg_save_design_window" class="save_your_design global-pop-windows">
    <div class="global-pop-windows-overlay"></div>
    <div class="global-pop-windows-container save_design_window_container">
      <div id="tool_save_design_window_back" class="toolbar_button">
        <button id="tool_save_design_window_cancel"></button>
      </div>
      <p id="saveDesignCaption" class="heading">Save Your Design</p>
      <div class="save-design-table">
        <div class="save-design">
          <fieldset>
            <legend  id="designDetailsCaption">Design Details</legend>
            <div class="div-table">
              <div class="div-table-row">
                <div class="div-table-cell">
                  <input type="text" name="design_name" id="design_name" placeholder="Design Name" />
                  <!--<label id="designNameCaption">Design Name</label>--> 
                </div>
                <div class="div-table-cell">
                  <button name="btn_save_design" class="button" name="btn_save_design" type="button" id="btn_save_design">
                  Submit
                  </button>
                </div>
              </div>
            </div>
          </fieldset>
        </div>
      </div>
    </div>
  </div>
  <div id="facebook_window"style="display:none">
    <div id="facebook_window_overlay"></div>
    <div id="facebook_window_container" class="ui-draggable">
      <div id="tool_save_design_window_back" class="toolbar_button">
        <button id="tool_facebook_window_cancel"></button>
      </div>
      <p id="facebookUploadCaption" class="headingtwo">upload your facebook photos</p>
      <div class="save-design-table">
        <select id="facebook_select">
        </select>
        <div class="fb_holder"></div>
      </div>
    </div>
  </div>
  <div id="preview_window" class="upload_custom_window global-pop-windows">
    <div class="global-pop-windows-overlay"></div>
    <div class="global-pop-windows-container global-max-width" id="preview_window_container">
      <div class="closebar_button">
        <button onclick="closeMe(this);"><i class="fa fa-close"></i></button>
      </div>
      <p id="previewPopupCaption" class="heading">Preview</p>
      <div id="tool_save_design_window_back" class="toolbar_button"> <a href="#" onclick="javascript:downloadPreview(); void(0);" id="downloadPreviewCaption" class="download-preview"><i class="fa fa-cloud-download"></i>&nbsp;Download Preview </a> </div>
      <div class="save-design-table">
        <div class="preview_holder"></div>
      </div>
    </div>
  </div>
  <div id="importTemplate_window" style="display:none;">
    <div class="window_overlay"></div>
    <div class="window_container">
      <div class="toolbar_button" >
      <button onclick="closeMe(this);">
      X
      </div>
      <p class="headingtwo" id="editTextPopupCaption">Edit Text</p>
      <div>
      <div id="editor-container"></div>
      <p id="importSvgInstruction">Please remove red colored text and click on button below.</p>
      <button id="btnAddUpdateSvg" class="btn" title="Add Text">Add/Update Template</button>
    </div>
  </div>
</div>
<script type="text/javascript">

<!--var qrcodeform =  new VarienForm('qrcodeform', false);-->
/*svgEditor.setConfig({
	dimensions: [design_area_width[1], design_area_height[1]],
	position: [parseFloat(pos_x[1]),parseFloat(pos_y[1])],
	canvas_expansion: 0,
	showRulers: false,
	initFill: {color:'0000FF'},
	initStroke: {width: 1, color:'#000000'},
	initBorder: {color:'#000000'},
	bkgd_color:'none',
	no_save_warning:true,
	extensions:extensionArray,
	show_outside_canvas:0
	//lang:currentStore
});*/
</script> 
<!-- load facebook plugin --> 
<!--<script src="//connect.facebook.net/en_US/all.js"></script> --> 
<script type="text/javascript" src="//connect.facebook.net/<?php echo $locale; ?>/all.js"></script> 
<script type="text/javascript" src="<?php echo $jspath . 'facebook.js'; ?>"></script> 
<script type="text/javascript">$j('#common-panel').draggable({ containment: "#container_dt" })</script> 
<script>
   $j('#colorpicker').farbtastic();
$j('#qr_colorpicker').farbtastic();
</script>
</div>
<!--<style>
.primarycolor, .layer-caption-section, .right-panel button, .object-align button, .login-button, .save-design-button, .prcadcart, .button, .plupload_total_file_size, .plupload_cell .plupload_upload_status, .plupload_total_status, .uploadHdButton, #previousSide a, #nextSide a,#addtocart_btn, #products-settings, #white, #font-selector > li:hover, #dialog_buttons input[type="button"],#picasa_go, #instagram_go, #flicker_go, #qr_cpclose { fill:<?php echo $svgcolor; ?>; color:<?php echo $svgcolor; ?>;background: <?php echo $primary_color; ?>; }
.secondarycolor, .right-panel button.active, .slide-share-panel #productSides button:hover, .right-panel button:hover,.next_previous_slide ul li:hover, .priceTag,.right-panel button:hover,.next_previous_slide ul li:hover, .priceTag,.right-panel button:hover .spicon_class svg {background:<?php echo $secondary_value; ?>; color: <?php echo $svgcolor_hover; ?>;fill:<?php echo $svgcolor_hover; ?>;}
.right-panel button .spicon_class svg {fill:<?php echo $svgcolor; ?>; color:<?php echo $svgcolor; ?>;}
.right-share-panel ul li button,.right-share-panel ul li,.currentFontSize{color:<?php echo $primary_color; ?>;}
.right-share-panel ul li button:hover,.right-share-panel ul li:hover{color:<?php echo $secondary_value; ?>;}
#cpclose{color: <?php echo $primary_color; ?>}
#white {box-shadow: 0 1px 0 <?php echo $primary_color; ?>;}
#fb_go, #picasa_go, #instagram_go, #flicker_go, #qr_cpclose, #flicker_import, #fb_window_container input[type="text"], 
#picasa_window_container input[type="text"], #instagram_window_container input[type="text"], 
#flickr_window_container input[type="text"] {border:1px solid <?php echo $primary_color; ?>; color:<?php echo $primary_color; ?>}
#align_bottom, #align_middle, #align_top, #align_right, #align_center, #align_left, .tool_button, 
.push_button, .tool_button_current, .push_button_pressed, #tool_group, #tool_ungroup, #tool_cut, #tool_copy, #tool_clone, 
.borderalignmentpanel #borderalignment i,.border-align-panel #cpclose, .layer_row .visibility label, 
.layer_row .visibility input[type="checkbox"] + label, .layer_row .security input[type="checkbox"],
.layer_row .security label, .layer_row .moveUp {fill:#484848; color:#484848;}

#align_bottom:hover, #align_middle:hover, #align_top:hover, #align_right:hover, #align_center:hover,#align_left:hover,.tool_button:hover, 
.push_button:hover, .tool_button_current:hover, .push_button_pressed:hover, #tool_group:hover,#tool_ungroup:hover,#tool_cut:hover, 
#tool_copy:hover, #tool_clone:hover,#tool_flipHoriz:hover svg, #tool_flipVert:hover svg,
.borderalignmentpanel #borderalignment:hover i,
.layer_row .visibility label:hover,
.layer_row .visibility input[type="checkbox"] + label:hover, 
.layer_row .security input[type="checkbox"]:hover,
.layer_row .security label:hover,
.push_button img.svg_icon:hover,
.layer_row .moveUp:hover {fill:<?php echo $primary_color; ?>; color:<?php echo $primary_color; ?>;}


.border_size_option .tool_button.selected,
.border_size_option .tool_button:hover,
#nameFonts ul li.selected,
#name-font-selector > li:hover, #number-font-selector > li:hover,
#name-font-selector > li.selected, #number-font-selector > li.selected,
#textNameShapeDD .shape_con .dropdownName li.selected,
#textNumberShapeDD .shape_con .dropdownNumber li.selected,
#font-selector-con ul li.selected,
.textFontSizePanel .shape_con .selected,
#textShapeDD .shape_con .dropdown li:hover,
#textShapeDD .shape_con .dropdown li.selected  {border:1px solid <?php echo $primary_color; ?>;} 
</style>-->