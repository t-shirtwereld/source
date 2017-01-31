//mit upload image tab
var image_url='';
$j("#image-upload-tab").live("mouseup",function(evt) {
		disableOtherGalleryDivs('image-upload');
		$j(this).addClass('active');
		$j("#image-upload-panel").show();
});

$j("#image-gallery-tab").live("mouseup",function(evt) {

		disableOtherGalleryDivs('move-div');
		$j(this).addClass('active');
		$j("#image-gallery-panel").show();
    mySwiper_gallery.resizeFix();
});
function disableOtherGalleryDivs(button) {
	if(button !== 'image-upload')	{ $j("#image-upload-panel").hide(); $j("#image-upload-tab").removeClass('active');}
	if(button !== 'image-gallery')	{ $j("#image-gallery-panel").hide(); $j("#image-gallery-tab").removeClass('active');}
}
function take_photo(source)
    {
          if(!('camera' in (navigator)))
            {
              app.t("No device found");
              return false;
            }
            var pictureSource=navigator.camera.PictureSourceType;
            var destinationType=navigator.camera.DestinationType;
            (source != 1)?navigator.camera.getPicture(onPhotoDataSuccess, onFail, { quality: 100,
                                           destinationType: destinationType.FILE_URI,sourceType: pictureSource.SAVEDPHOTOALBUM }): navigator.camera.getPicture(onPhotoDataSuccess, onFail, { quality: 70,
                                                                                                                                                               destinationType: destinationType.FILE_URI });
    }
    function onPhotoDataSuccess(imageURI)
    {
        showLoader(true);
        image_url=imageURI;
        var d = new Date();
        var newFileName = d.getTime() + ".jpg";
        // newFileName_inquiry = imageURI.substr(imageURI.lastIndexOf('/') + 1);
        // last_uploaded_imq_img = newFileName;
        var options = new FileUploadOptions();
        //options.headers = {
        //Connection: "close"
        //};
        options.fileKey = "file";
        // options.fileName = newFileName;
        options.fileName= newFileName;

        options.mimeType = "image/jpeg";
        var params = {
        };
        params.file= newFileName;
        //var params = new Object();
        // params.inquiry_id = curr_inq_id;
        //params.fileName = newFileName;
        options.params = params;
        options.chunkedMode = false;
        var ft = new FileTransfer();
       // console.log(options);
       ft.onprogress = function(progressEvent) {
       if (progressEvent.lengthComputable) {
       //$('#progressbox').show();
       var progressbar     = $('#progressbar');
       var fraction=(progressEvent.loaded / progressEvent.total);
       progressbar.width(((fraction)*100) + '%') //update progressbar percent complete
       if(fraction>=0.98)
       {
       	//$('#progressbox').hide();
       }
       } else {
        alert("1");
       // loadingStatus.increment();
       }
       };
       ft.upload(imageURI, encodeURI(mobi_image_upload_url), winInquiry, failInquiry, options);
    }
 function onFail()
 {
   app.t("fail take photo")
 }

 var winInquiry = function (r)
 {
  console.log(r)
    try
    {
        var response = $.parseJSON(r.response);
        console.log(response);
        if(response.status=='SUCCESS')
        {
            image_url = response.data.url;
            append_gallery(image_url);
            app.t('Image uploaded.');
            hideLoader(); 
            showHidePanleElements('image');
            setTimeout( function() { loadImageONCanvas(image_url); }, 100 );
        }
    }
    catch(e)
    { 
        console.log(e);
        console.log("catch error in image uplaod response");
    }


 }
 
 var failInquiry = function (error)
 {
     app.t('Server connection failed.');
     app.c(error);
     app.c("upload error source " + error.source);
     app.c("upload error target " + error.target);
 }
//end mit uplaod image tag
function append_gallery(url)
{
  //image_gallery_ul
  $j('#gallery_container .swiper-wrapper').append("<div class='swiper-slide'><li>"+
          "<a onclick='loadImageONCanvas(this.rel); return false;' class ='imageclass' href='javascript:void(0); return false; ' target='_blank' rel='" + url + "'>"+
          "<img onload='lazyLoaderImg(this)' class ='userImage' data='" + url + "' src='"+url+"' width='70' height='70' >"+
          "</a></li></div>");
  mySwiper_gallery.reInit();
  /*
  mySwiper_gallery = new Swiper('#gallery_container',{
          calculateHeight:true,
          autoResize:true,
          resizeReInit:true,
          loop:false
          });  
*/
}