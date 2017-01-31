/**
 * jQuery Image Gallery
 * 
 * @name jq-ig.js
 * @author Watchara Sriswasdi - http://www.eakkew.com/
 * @version 1.2
 * @copyright (c) 2011 eakkew(www.eakkew.com)
 * @Visit http://jq-ig.googlecode.com/ for more informations, and duscussions about this plugin
 */

(function( $ ){
  
  $.fn.jqig = function( options ) {  
    var GALLERY = this;
    var settings = {
      username:"username", 
      access:"public",
      pagination : {
        enable: true,
        position: 'both', // specific position of pagination either {"both", "top", "bottom"}
        imgPerPage: 15,  // config for render image per page
        pageNum: 1 // init rendered page
      },
      thumbsize: 104, // Thumbnail size can be varied by following value 32, 48, 64, 72, 104, 144, 150, 160
      selectedAlbum: {},
      albums: [],
      removeAlbumTypes: ["ScrapBook", "Blogger"],  //-- Albums with this type in the gphoto$albumType will not be shown. Known types are Blogger, ScrapBook, ProfilePhotos, Buzz, CameraSync
      labels: {
        paginationNext: 'next &rarr;',
        paginationPrevious: '&larr; prev',
        paginationMore: '...',
      }, // @TODO
      enableImageCaption: false, // @TODO implement logic
    };
    var BASEURL = "http://picasaweb.google.com/data/feed/api/user/";
    /**
     * get JSON object by specific albumId and page
     * @albumId is id of picasa album
     * @page is page to be retrieved. Leave this if pagination is disable
    */
    function getAlbum(albumId, page) {
      // change style of album in album-container
      $(".jqig-selected-album").attr("class", "jqig-album");
      $("#"+albumId).attr("class", "jqig-selected-album");
      
      
      // field validation
      if (page == null || page < 1) page = 1;
      settings.pagination.pageNum = parseInt(page);
      
      albumUrl = BASEURL + settings.username + "/albumid/"+albumId+"?kind=photo&access=public&alt=json&thumbsize="+ settings.thumbsize+"c" 
        + (( !settings.pagination.enable ) ? "" : "&max-results=" + settings.pagination.imgPerPage)
        + "&start-index="+ (settings.pagination.imgPerPage * (page-1) +1);
      
      // for debug mode
      $.getJSON(albumUrl, function(data) {createAlbum(data);});
      // hide JSON object
      //$.getJSON(albumUrl, "callback=?", createAlbum);
    }

    /**
      * create elements in image gallery, #gallery, from request in getAlbum.
      * @data is JSON object
    */
    function createAlbum(data){
    // init elements
      var galleryTop = $('<div class="gallery-top"/>');
      var verticalScrollbar = $('<div class="vertical-scroll-bar-wrapper"/>');
      var galleryBottom = $('<div class="gallery-bottom bottom"/>');
      $("#gallery").empty();
      $('#gallery').append(galleryTop).append(verticalScrollbar).append($('<div class="gallery-images-wrapper"><div class="gallery-images"/></div>')).append(galleryBottom);
      
      // for Pagination
      if (settings.pagination.enable){
        // init paginator DOM and variable
        settings.pagination.MAXPAGE = Math.ceil(data.feed.gphoto$numphotos.$t / settings.pagination.imgPerPage);
        if (settings.pagination.position == "both" || settings.pagination.position == "top") 
          galleryTop.append('<div class="paginator pager"></div>'); 
        if (settings.pagination.position == "both" || settings.pagination.position == "bottom") 
          galleryBottom.append($('<div class="paginator pager"></div>').css("clear","both"));
        // calculate page number to be rendered as a link
        listPage = 3, listStart = -1, listEnd = -1;
        ( settings.pagination.pageNum < 3*listPage ) ? listStart = 1 : listStart = settings.pagination.pageNum - listPage;
        if (settings.pagination.MAXPAGE - settings.pagination.pageNum < 2*listPage ) {
          listEnd = settings.pagination.MAXPAGE;
          if ( settings.pagination.MAXPAGE - settings.pagination.pageNum < 2*listPage && settings.pagination.pageNum > 3*listPage ) 
            listStart = settings.pagination.pageNum - ( 2*listPage - (settings.pagination.MAXPAGE - settings.pagination.pageNum));
        }
        else {
          if (settings.pagination.pageNum < 2*listPage ) { listEnd = 3*listPage;}
          else listEnd = settings.pagination.pageNum + listPage;
        }
        
        var paginator = $('.paginator');
        
        // for previous page elements
        if (settings.pagination.pageNum <= 1) {paginator.append($('<span class="start-page"/>').append(settings.labels.paginationPrevious));}
        else paginator.append($('<a class="previous"/>').append(settings.labels.paginationPrevious).click( function (){ getAlbum(data.feed.gphoto$id.$t, settings.pagination.pageNum-1);} ));
        if (listStart != 1) paginator.append($('<span class="more"/>').append(settings.labels.paginationMore));
        // for list of pages
        for ( i = listStart; i <= listEnd; i++){
          if (i == settings.pagination.pageNum){
            paginator.append($('<span class="selected-page" >'+i+'</span>'));
          }
          else {
            paginator.append($('<a id="'+i+'" >'+i+'</a>').click( function (){ getAlbum(data.feed.gphoto$id.$t, this.id);} ));
          }
        }
        // for next page element
        if (listEnd != settings.pagination.MAXPAGE)  paginator.append($('<span class="more"/>').append(settings.labels.paginationMore));
        if (settings.pagination.pageNum >= settings.pagination.MAXPAGE) {paginator.append($('<span class="end-page"/>').append(settings.labels.paginationNext));}
        else paginator.append($('<a class="next"/>').append(settings.labels.paginationNext).click( function (){ getAlbum(data.feed.gphoto$id.$t, settings.pagination.pageNum+1);} ));
      }
      
      // FOR Getting images into container
      // get Images from JSON Object
      var imagesList = [];

      var imageContainer = $('#gallery .gallery-images');
      $.each(data.feed.entry, function(keyEntry, entry) {
      //img = $('<div class="image-wrapper"/>').append($('<a href="'+entry.content.src+'"/>').append($('<div class="img"/>').append($('<div class="mask"></div>')).append($('<img src="' + entry.media$group.media$thumbnail[0].url + '" />'))));
        img = $('<div class="photo"/>').append($('<img src="' + entry.media$group.media$thumbnail[0].url + '" />'));
        if (settings.enableImageCaption) img.append($('<div class="image-caption"/>').html(entry.media$group.media$description.$t));
        imageContainer.append(img);
      });
      imH = settings.thumbsize+parseFloat($('.image-wrapper img').css('margin-top'));
      imW = settings.thumbsize+parseFloat($('.image-wrapper img').css('margin-left'));
      $('.image-wrapper img').height(imH).width(imW);
      $('.image-wrapper .mask').height(imH).width(imW);
      $('.image-wrapper').width(imW);
      $('.gallery-images').append($("<br>").css("clear","left"));
      
      // FOR Scrollbar decoration
      //scrollpane parts
      var scrollPane = $('.gallery-images-wrapper'), scrollContent = $('.gallery-images');
      
      //change overflow to hidden now that slider handles the scrolling
      scrollPane.css( "overflow", "hidden" );

      // init scrollbar whenever possible
      if (scrollContent.height() > scrollPane.height()) {
        // initial part
        var remainContentHeight = scrollContent.height() - scrollPane.height();
        var proportion = remainContentHeight / scrollContent.height();
        var handleSize = Math.round(scrollPane.height() - (proportion * scrollPane.height()));
        $('.vertical-scroll-bar-wrapper').append($('<div class="vertical-scroll-bar"/>'));
        
        //build slider
        var scrollbar = $( ".vertical-scroll-bar" ).slider({
          orientation: "vertical",
          min: 0,
          max: 100,
          value: 100,
          slide: function( event, ui ) {
            //scrollContent.css( "top", Math.round( ( ui.value - 100) * (scrollContent.height() - scrollPane.height()) /100 ));
            //alert(Math.round( ( 100 - ui.value) * (scrollContent.height() - scrollPane.height()) /100 ));
            scrollContent.scrollTop( Math.round( ( 100 - ui.value) * (scrollContent.height() - scrollPane.height()) /100 ) );
          },
          change: function( event, ui ) {
            //scrollContent.css( "top", Math.round( ( ui.value - 100) * (scrollContent.height() - scrollPane.height()) /100 ));
            //alert(Math.round( ( 100 - ui.value) * (scrollContent.height() - scrollPane.height()) /100 ));
            scrollPane.scrollTop( Math.round( ( 100 - ui.value) * (scrollContent.height() - scrollPane.height()) /100 ) );
          }

        });
        // adjust UI things
        $('.vertical-scroll-bar .ui-slider-handle').css({'height':handleSize, 'margin-bottom':-0.5*handleSize});
        scrollbar.css({'margin-top': 2 + handleSize/2, 'height': scrollbar.height() - handleSize - 3}).hide();
        
      } // EIF
      
      // mousewheel Event
	  /*
      $('.gallery-images-wrapper, .vertical-scroll-bar-wrapper, .vertical-scroll-bar a').mousewheel(function(event, delta){
        var speed = 5;
        var sliderVal = $('.vertical-scroll-bar').slider('value');//read current value of the slider
    
        sliderVal += (delta*speed);//increment the current value

        $('.vertical-scroll-bar').slider('value', sliderVal);//and set the new value of the slider
        
        event.preventDefault();//stop any default behaviour
      });*/
      
      // style scrollbar up to be a fb-like
      if (scrollbar){
        $('.gallery-images-wrapper').hover(
          // hover-in
          function(){scrollbar.stop().fadeTo(300, 1);}, 
          // hover-out
          function(){scrollbar.stop().fadeTo(700, 0);}
        );
      }
    }

    function getAlbums(){
      var url = BASEURL + settings.username + "?kind=album&access=public&alt=json";//&thumbsize=160c";

      // for debug mode
      //$.getJSON(url, function(data) {createAlbums(data);});
		$.getJSON( url, function(data) {
			// console.log(data);
			createAlbums(data);
			hideLoader();
		})
		.done(function() {
		console.log('done');
			hideLoader();//console.log( "second success" );
		})
		.fail(function() {
		console.log('fail');
			type = svgEditor.uiStrings.elementLabel.PICASA_REQUEST_FAILED;
			msg = svgEditor.uiStrings.elementLabel.USER_NOT_FOUND;
			hideLoader();
			
			//var html = '<div style="padding:4px;margin:0 0 2px;background:#222;"><strong>' + type + ': </strong>'+ msg + '</div>';
			$('<div style="padding:4px;margin:0 0 2px;background:#222;"><strong>' + type + ': </strong>'+ msg + '</div>').appendTo('#import_error');			
			$j('div.picasa_holder').empty();
			
		});
      // hide JSON object
      //$.getJSON(url, "callback=?", createAlbums);
      
      // Refresh pageNum for pagination
      settings.pagination.pageNum = 1;
    }

    function createAlbums(data) {
      var items = [];
	selector = $('<select class="select-small" id="picasa_album_selector">');
      $.each(data.feed.entry, function(keyentry, entry) {
        // get rid of some album such as Profile Photos and Scrapbook Photos
        if (entry.gphoto$albumType === undefined || $.inArray(entry.gphoto$albumType.$t, settings.removeAlbumTypes) == -1){
//          items.push('<li id="' + entry.title.$t + '"><a href="javascript:void(0)" onclick="getAlbum(\''+entry.gphoto$id.$t+'\');"> ' + entry.title.$t + '</a></li>');
          // $('#albums-container').append($('<div id="'+entry.gphoto$id.$t+'" class = "jqig-album">'+entry.title.$t+'</div>').click(function (){getAlbum(entry.gphoto$id.$t);}));
		  $(selector).append($('<option value="'+entry.gphoto$id.$t+'" class = "jqig-album">'+entry.title.$t+'</option>').change(function (){getAlbum(entry.gphoto$id.$t);}));
          settings.albums.push(entry.id.$t.split("/albumid/")[1].split("?")[0]);
        }
      });
		$('#albums-container').append(selector);
      // select the first album to be rendered
      getAlbum(settings.albums[0]);

    }

    function init(){
      GALLERY.append('<div id="albums-container"></div><div id="gallery">'+svgEditor.uiStrings.elementLabel.PICASA_ALBUM_HERE+'</div>');
      if ( options ) { 
        $.extend( true, settings, options );
      }
	$(document).on('change',"#picasa_album_selector", function() {
		var element = $(this);
		var albumId = element.attr("id");
		getAlbum(this.value);
	});
      getAlbums();
    }
    init();
  };
  
})( jQuery );