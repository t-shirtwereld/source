<div class="pc_contten">
    <div class="pc_top">
        <h1>Add Font</h1>
	<div class="pc_rgt" style="margin-left: 10px;"><a href="<?php echo BASE_ADMIN_URL . 'fonts'; ?>">Font Listing</a></div>
	<div class="pc_rgt"><a href="<?php echo BASE_ADMIN_URL . 'fonts/importcsv'; ?>">Import Fonts</a></div>

    </div>

    <?php if (validation_errors()) { ?>
        <div id="messages_product_view">
    	<ul class="messages">
    	    <li class="error-msg">
		    <?php echo validation_errors(); ?>
    	    </li>
    	</ul>
        </div>
    <?php } ?>

    <div>&nbsp;</div>

    <div>
	<div class="pc_contten box-box">
	    <form id="addFontForm" action="<?php echo BASE_ADMIN_URL . 'fonts/submitdata'; ?>" method="post" class="col-two" enctype="multipart/form-data">
		<input type="hidden" name="font_id" value="" />
		<div class="input-area" style="margin-bottom:30px;">
		    <div class="field-set">
			<div class="field-set"><label>Name</label></div>
			<div class="field-form">
			    <div class="input-field validationclass">
				<input type="text" name="font_name" value="" placeholder="" />
			    </div>
			</div>
		    </div>
		    <div class="field-set">
			<label>Upload WOFF Font</label>
			<div class="input-field">
			    <input id="file_upload" name="file_upload" type="file"/>
			</div>
		    </div>
		    <div class="field-set">
			<label>Upload JS Font</label>
			<div class="input-field">
			    <input id="js_upload" name="js_upload" type="file"/>
			</div>
		    </div>
		    <div class="field-set">
			<label>Status</label>
			<div class="input-field" style="margin-top:10px;">
			    <fieldset class="switch">
				<label class="off">Inactive<input type="radio" class="on_off" name="status" value="0"/></label>
				<label class="on">Active<input type="radio" class="on_off" name="status" value="1"/></label>
			    </fieldset>
			</div>
		    </div>
		</div>
		<div class="input-area">
		    <div class="field-set">
			<label>&nbsp;</label>
			<div class="input-field">
			    <input type="submit" class="btn" value="Save" />
			</div>
		    </div>
		</div>
	    </form>
		<div>
			<span id="font_note"><ul class="messages">
			<li class="notice-msg" style="margin-top:11px;">
				<ul>
					<li>1. To create JS Font file <a target="_base" href="http://cufon.shoqolate.com/">click here</a>.</li>
					<li>2. Follow the instructions on the web page.</li>
					<li>3. Find "Customization (for 3rd-party scripts only)" section, replace "Cufon.registerFont" with "Raphael.registerFont". Like the image below<br><img alt="in Customization (for 3rd-party scripts only) section, replace Cufon.registerFont with Raphael.registerFont" src="<?php echo base_url('designtool/cufon_instruction.jpg'); ?>">
					</li>									
				</ul>
			</li>
		</ul></span>
		</div>
	</div>
    </div>
</div>
<div class="clearfix"></div> 
<script>
    $(document).ready(function() {	   
	$("#addFontForm").validate({
	    rules: {
		font_name: {
		    required:true,
		    maxlength: 50
		},
		file_upload: {
		    required:true,
		    extension: 'woff|WOFF'
		},
		js_upload: {
		    required:true,
		    extension: 'js'
		}
	    },
	    messages: {
		font_name: {
		    maxlength: "Maximum 50 characters allowed."
		},
		file_upload: {
		    extension: 'Please upload WOFF file only..!'
		},
		js_upload: {
		    extension: 'Please upload Js file only..!'
		}
	    }
	}); 
	
	$('.switch').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off').css('display','none');
	$('.on, .off').css('text-indent','-10000px');
	$('.switch').css('background-position', 'right');
	$("input[name=status][value=0]").attr('checked', 'checked');

	$("input[name=status]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch').css('background-position', 'left'); }	   

	});
    });
</script>