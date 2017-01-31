$j(function() {
	var uploader;	
	$j("#upload_img_show").mouseup(function(){		
		if($j("#upload_img_show").attr("disabled") != "disabled"){			
			$j('#svg_image_upload').show();
			$j( ".plupload_message" ).remove();
			
			// disableOther('editpanel');
			// $j("#addtimage-panel").removeClass('cbp-spmenu-open');
			// $j("#edit-panel").addClass('cbp-spmenu-open');
			// $j('#images_loaded').show();
			var uploader = $j('#uploader').plupload('getUploader');			
			uploader.refresh();  //this fixes IE10 not being able to click to add files
			return false;
		}
	});
	uploader = $j("#uploader").plupload({
		// General settings
		container : 'windows',
		runtimes : 'html5',
		url : jspath+'plupload/upload.php',
		unique_names: true,
		// User can upload no more then 20 files in one go (sets multiple_queues to false)
		max_file_count: 20,
		
		chunk_size: '1mb',
		resize: false,
		multipart_params : {
			"isFront" : isFront
		},
		// Resize images on clientside if we can
		/*resize : {
			width : 200, 
			height : 200, 
			quality : 90,
			crop: true // crop to exact dimensions
		},*/
		
		filters : {
			max_img_resolution: 10000, // 1MP = 1 million pixels // WidthXHeight
			// Maximum file size
			max_file_size : '15mb',
			// Specify what files to browse for
			mime_types: [
				{title : "Image files", extensions : "jpg,jpeg,png"}
			],
			prevent_duplicates: false,
		},

		// Rename files by clicking on their titles
		rename: true,
		
		// Sort files
		sortable: false,

		// Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
		dragdrop: true,

		// Views to activate
		views: {
			list: true,
			thumbs: true, // Show thumbs
			active: 'thumbs'
		},

		// Flash settings
		// flash_swf_url : jspath+'Moxie.swf',

		// Silverlight settings
		// silverlight_xap_url : jspath+'Moxie.xap',	
		// PreInit events, bound before the internal events
        preinit : {
            Init: function(up, info) {
                log('[Init]', 'Info:', info, 'Features:', up.features);
            },
 
            UploadFile: function(up, file) {
                log('[UploadFile]', file);
 
                // You can override settings before the file is uploaded
                // up.setOption('url', 'upload.php?id=' + file.id);
                // up.setOption('multipart_params', {param1 : 'value1', param2 : 'value2'});
            }
        },
 
        // Post init events, bound after the internal events
        init : {
			PostInit: function() {
				// Called after initialization is finished and internal event handlers bound
				//log('[PostInit]');				
				
				/*document.getElementById('uploader_browse').onclick = function() {
					uploader.start();
					return false;
				};*/
			},

			Browse: function(up) {
                // Called when file picker is clicked
                //log('[Browse]');	
            },

            Refresh: function(up) {
                // Called when the position or dimensions of the picker change
               // log('[Refresh]');
            },
 
            StateChanged: function(up) {
                // Called when the state of the queue is changed
                //log('[StateChanged]', up.state == plupload.STARTED ? "STARTED" : "STOPPED");
            },
 
            QueueChanged: function(up) {
				up.refresh();
                // Called when queue is changed by adding or removing files
                //log('[QueueChanged]');
            },

			OptionChanged: function(up, name, value, oldValue) {
				// Called when one of the configuration options is changed
				//log('[OptionChanged]', 'Option Name: ', name, 'Value: ', value, 'Old Value: ', oldValue);
			},

			BeforeUpload: function(up, file) {
				// Called right before the upload for a given file starts, can be used to cancel it if required
				//log('[BeforeUpload]', 'File: ', file);
			},
 
            UploadProgress: function(up, file) {
                // Called while file is being uploaded
               // log('[UploadProgress]', 'File:', file, "Total:", up.total);
            },

			FileFiltered: function(up, file) {
				// Called when file successfully files all the filters
                //log('[FileFiltered]', 'File:', file);
			},
 
            FilesAdded: function(up, files) {
                // Called when files are added to queue
                //log('[FilesAdded]');
 
                plupload.each(files, function(file) {
                    //log('  File:', file);
                });
            },
 
            FilesRemoved: function(up, files) {
                // Called when files are removed from queue
                //log('[FilesRemoved]');
 
                plupload.each(files, function(file) {
                    //log('  File:', file);
                });
            },
 
            FileUploaded: function(up, file, info) {
                // Called when file has finished uploading
                //log('[FileUploaded] File:', file, "Info:", info);
				var loaderImage = 'imageLoader.gif';
				var obj = jQuery.parseJSON(info.response);				
				if(obj.status) { 					
					$j('#imageresult').append("<li class='imgloader'><a onclick='loadImageONCanvas(this); return false;' class ='imageclass' href='javascript:void(0); return false; ' target='_blank' rel='" + mediapath + "uploadedImage/"  + file.target_name + "'><img onload='lazyLoaderImg(this)' class ='lazy' data='" + mediapath + "uploadedImage/"  + file.target_name + "' src='" + mediapath + "uploadedImage/"  + file.target_name + "' border='0'></a></li>");
				}
            },
 
            ChunkUploaded: function(up, file, info) {
                // Called when file chunk has finished uploading
                //log('[ChunkUploaded] File:', file, "Info:", info);
            },

			UploadComplete: function(up, files) {			
				
				// Called when all files are either uploaded or failed				
                //log('[UploadComplete]');
				var file_count = up.files.length;				
				for(i = 0; i < file_count; i++) {	
					if (up.files[0] && up.files[0] !== undefined) {
						up.removeFile(up.files[0]);
					}
				}
				$j('#svg_image_upload', window.parent.document).hide();							
				
			},

			Destroy: function(up) {
				// Called when uploader is destroyed
                //log('[Destroy] ');
			},
 
            Error: function(up, err) {
                // Called when error occurs
               // log('[Error] ', err
				up.refresh();
            }
        }
	});
	
	function log() {
        var str = "";
 
        plupload.each(arguments, function(arg) {
            var row = "";
 
            if (typeof(arg) != "string") {
                plupload.each(arg, function(value, key) {
                    // Convert items in File objects to human readable form
                    if (arg instanceof plupload.File) {
                        // Convert status to human readable
                        switch (value) {
                            case plupload.QUEUED:
                                value = 'QUEUED';
                                break;
 
                            case plupload.UPLOADING:
                                value = 'UPLOADING';
                                break;
 
                            case plupload.FAILED:
                                value = 'FAILED';
                                break;
 
                            case plupload.DONE:
                                value = 'DONE';
                                break;
                        }
                    }
 
                    if (typeof(value) != "function") {
                        row += (row ? ', ' : '') + key + '=' + value;
                    }
                });
 
                str += row + " ";
            } else {
                str += arg + " ";
            }
        });
 
        //var log = document.getElementById('console');
        //log.innerHTML += str + "\n";
    }	
	// Handle the case when form was submitted before uploading has finished
	/*$j('#form').submit(function(e) {
		// Files in queue upload them first
		if ($j('#uploader').plupload('getFiles').length > 0) {

			// When all files are uploaded submit form
			$j('#uploader').on('complete', function() {
				$j('#form')[0].submit();
			});

			$j('#uploader').plupload('start');
		} else {
			alert("You must have at least one file in the queue.");
		}
		return false; // Keep the form from submitting
	});	*/
	
	plupload.addFileFilter('max_img_resolution', function(maxRes, file, cb) {
	  var self = this, img = new o.Image();
	 
	  function finalize(result) {
		// cleanup
		img.destroy();
		img = null;
		
		// if rule has been violated in one way or another, trigger an error
		if (!result) {
		  self.trigger('Error', {
			code : plupload.IMAGE_DIMENSIONS_ERROR,
			message : "Resolution exceeds the allowed limit of " + maxRes  + " pixels.",
			file : file
		  });
		   file.status = plupload.FAILED;		  
		}
		cb(result);
	  }
	 
	  img.onload = function() {
		// check if resolution cap is not exceeded]		
		var size = Math.max(this.width,this.height);		
		finalize(size < maxRes);		
	  };
	 
	  img.onerror = function() {
		finalize(false);
	  };
	 
	  img.load(file.getSource());
	});

	uploader.init();
	uploader.trigger('Refresh')
	$j('#uploader > div.plupload input').css('z-index','99999');

});
