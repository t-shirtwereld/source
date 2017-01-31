<div class="pc_contten">
    <div class="pc_top">
        <h1>Design Studio Configuration</h1>
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

    <?php if ($this->session->flashdata('msg')) { ?>
        <div id="messages_product_view">
    	<ul class="messages">
    	    <li class="success-msg">
		    <?php echo $this->session->flashdata('msg'); ?>
    	    </li>
    	</ul>
        </div>
    <?php } ?>

    <div>&nbsp;</div>

    <div>
	<div class="pc_contten box-box">
	    <form action="<?php echo BASE_ADMIN_URL . 'settings/update_configure_feature'; ?>" method="post" class="col-two">
		<input type="hidden" name="id" value="<?php echo $setting['id']; ?>" />
		<div class="input-area">
		    <div class="field-set">
			<label><h2>Text Controls </h2></label>
			<div class="input-field validationclass">
			    <fieldset class="switch">
				<label class="off">Inactive<input type="radio" class="on_off" name="standard_text" value="0"/></label>
				<label class="on">Active<input type="radio" class="on_off" name="standard_text" value="1"/></label>
			    </fieldset>
			</div>
		    </div>
		    <div class="field-set">
			<label>Advance Text Effects</label>
			<div class="input-field">
			    <fieldset class="switch_5">
				<label class="off_5">Inactive<input type="radio" class="on_off_5" name="text_effects" value="0"/></label>
				<label class="on_5">Active<input type="radio" class="on_off_5" name="text_effects" value="1"/></label>
			    </fieldset>
			</div>
		    </div>
		    <div class="field-set">
			<label><h2>Clipart Controls</h2> </label>
			<div class="input-field">
			    <fieldset class="switch_1">
				<label class="off_1">Inactive<input type="radio" class="on_off_1" name="cliparts" value="0"/></label>
				<label class="on_1">Active<input type="radio" class="on_off_1" name="cliparts" value="1"/></label>
			    </fieldset>
			</div>
		    </div>
		    <div class="field-set">
			<label>Show Clipart</label>
			<div class="input-field">
			    <fieldset class="switch_12">
				<label class="off_12">Inactive<input type="radio" class="on_off_12" name="show_clipart" value="0"/></label>
				<label class="on_12">Active<input type="radio" class="on_off_12" name="show_clipart" value="1"/></label>
			    </fieldset>
			</div>
		    </div>
		    <div class="field-set">
			<label>Show Designidea</label>
			<div class="input-field">
			    <fieldset class="switch_13">
				<label class="off_13">Inactive<input type="radio" class="on_off_13" name="show_designidea" value="0"/></label>
				<label class="on_13">Active<input type="radio" class="on_off_13" name="show_designidea" value="1"/></label>
			    </fieldset>
			</div>
		    </div>
		  <!--  <div class="field-set">
			<label>Editable Design Ideas </label>
			<div class="input-field">
			    <fieldset class="switch_12">
				<label class="off_12">Inactive<input type="radio" class="on_off_12" name="editable_design_ideas" value="0"/></label>
				<label class="on_12">Active<input type="radio" class="on_off_12" name="editable_design_ideas" value="1"/></label>
			    </fieldset>
			</div>
		    </div> -->
		    <div class="field-set">
			<label>Free Hand Shapes</label>
			<div class="input-field">
			    <fieldset class="switch_6">
				<label class="off_6">Inactive<input type="radio" class="on_off_6" name="free_hand_shapes" value="0"/></label>
				<label class="on_6">Active<input type="radio" class="on_off_6" name="free_hand_shapes" value="1"/></label>
			    </fieldset>
			</div>
		    </div>
		    <div class="field-set">
			<label><h2>Upload Image Controls</h2></label>
			<div class="input-field">
			    <fieldset class="switch_2">
				<label class="off_2">Inactive<input type="radio" class="on_off_2" name="image_upload" value="0"/></label>
				<label class="on_2">Active<input type="radio" class="on_off_2" name="image_upload" value="1"/></label>
			    </fieldset>
			</div>
		    </div>
		    <div class="field-set">
			<label>Source File Upload <br><em>(CDR, PDF, EPS, AI, PSD, PS, TIF)</em></label>
			<div class="input-field">
			    <fieldset class="switch_9">
				<label class="off_9">Inactive<input type="radio" class="on_off_9" name="advance_image_upload" value="0"/></label>
				<label class="on_9">Active<input type="radio" class="on_off_9" name="advance_image_upload" value="1"/></label>
			    </fieldset>
			</div>
		    </div>
		    <div class="field-set">
			<label>Import Image</label>
			<div class="input-field">
			    <fieldset class="switch_10">
				<label class="off_10">Inactive<input type="radio" class="on_off_10" name="social_media_image_upload" value="0"/></label>
				<label class="on_10">Active<input type="radio" class="on_off_10" name="social_media_image_upload" value="1"/></label>
			    </fieldset>
			</div>
		    </div>
		    <div class="field-set">
			<label>QR Code</label>
			<div class="input-field">
			    <fieldset class="switch_4">
				<label class="off_4">Inactive<input type="radio" class="on_off_4" name="qr_code" value="0"/></label>
				<label class="on_4">Active<input type="radio" class="on_off_4" name="qr_code" value="1"/></label>
			    </fieldset>
			</div>
		    </div>
		    <div class="field-set">
			<label><h2>Advance Controls</h2></label>
		<!--	<div class="input-field">
			    <fieldset class="switch_13">
				<label class="off_13">Inactive<input type="radio" class="on_off_13" name="advance_control" value="0"/></label>
				<label class="on_13">Active<input type="radio" class="on_off_13" name="advance_control" value="1"/></label>
			    </fieldset>
			</div> -->
		    </div>
		    <div class="field-set">
			<label>Share Designs</label>
			<div class="input-field">
			    <fieldset class="switch_8">
				<label class="off_8">Inactive<input type="radio" class="on_off_8" name="social_media_sharing" value="0"/></label>
				<label class="on_8">Active<input type="radio" class="on_off_8" name="social_media_sharing" value="1"/></label>
			    </fieldset>
			</div>
		    </div>
		    <div class="field-set">
			<label>Preload Template</label>
			<div class="input-field">
			    <fieldset class="switch_11">
				<label class="off_11">Inactive<input type="radio" class="on_off_11" name="preload_template" value="0"/></label>
				<label class="on_11">Active<input type="radio" class="on_off_11" name="preload_template" value="1"/></label>
			    </fieldset>
			</div>
		    </div>

		    <div class="field-set">
			<label>Name Number</label>
			<div class="input-field">
			    <fieldset class="switch_7">
				<label class="off_7">Inactive<input type="radio" class="on_off_7" name="name_number" value="0"/></label>
				<label class="on_7">Active<input type="radio" class="on_off_7" name="name_number" value="1"/></label>
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
	</div>
    </div>
</div>

<div class="clearfix"></div>
<script>
    $(document).ready(function() {
	$('#messages_product_view').fadeIn().delay(10000).fadeOut();
	
	$('.switch').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off').css('display','none');
	$('.on, .off').css('text-indent','-10000px');
<?php if ($setting['standard_text'] != '0') { ?>
    	$('.switch').css('background-position', 'left');
    	$("input[name=standard_text][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch').css('background-position', 'right');
    	$("input[name=standard_text][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=standard_text]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch').css('background-position', 'left'); }	   

	});
	
	$('.switch_1').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off_1').css('display','none');
	$('.on_1, .off_1').css('text-indent','-10000px');
<?php if ($setting['cliparts'] != '0') { ?>
    	$('.switch_1').css('background-position', 'left');
    	$("input[name=cliparts][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch_1').css('background-position', 'right');
    	$("input[name=cliparts][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=cliparts]").change(function() {
	    var button1 = $(this).val();

	    if(button1 == '0'){ $('.switch_1').css('background-position', 'right'); }
	    if(button1 == '1'){ $('.switch_1').css('background-position', 'left'); }	   

	});
	
	$('.switch_2').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off_2').css('display','none');
	$('.on_2, .off_2').css('text-indent','-10000px');
<?php if ($setting['image_upload'] != '0') { ?>
    	$('.switch_2').css('background-position', 'left');
    	$("input[name=image_upload][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch_2').css('background-position', 'right');
    	$("input[name=image_upload][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=image_upload]").change(function() {
	    var button2 = $(this).val();

	    if(button2 == '0'){ $('.switch_2').css('background-position', 'right'); }
	    if(button2 == '1'){ $('.switch_2').css('background-position', 'left'); }	   

	});
	
	$('.switch_3').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off_3').css('display','none');
	$('.on_3, .off_3').css('text-indent','-10000px');
<?php if ($setting['printingmethods'] != '0') { ?>
    	$('.switch_3').css('background-position', 'left');
    	$("input[name=printingmethods][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch_3').css('background-position', 'right');
    	$("input[name=printingmethods][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=printingmethods]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch_3').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch_3').css('background-position', 'left'); }	   

	});
	
	$('.switch_4').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off_4').css('display','none');
	$('.on_4, .off_4').css('text-indent','-10000px');
<?php if ($setting['qr_code'] != '0') { ?>
    	$('.switch_4').css('background-position', 'left');
    	$("input[name=qr_code][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch_4').css('background-position', 'right');
    	$("input[name=qr_code][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=qr_code]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch_4').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch_4').css('background-position', 'left'); }	   

	});
	
	$('.switch_5').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off_5').css('display','none');
	$('.on_5, .off_5').css('text-indent','-10000px');
<?php if ($setting['text_effects'] != '0') { ?>
    	$('.switch_5').css('background-position', 'left');
    	$("input[name=text_effects][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch_5').css('background-position', 'right');
    	$("input[name=text_effects][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=text_effects]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch_5').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch_5').css('background-position', 'left'); }	   

	});
	
	$('.switch_6').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off_6').css('display','none');
	$('.on_6, .off_6').css('text-indent','-10000px');
<?php if ($setting['free_hand_shapes'] != '0') { ?>
    	$('.switch_6').css('background-position', 'left');
    	$("input[name=free_hand_shapes][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch_6').css('background-position', 'right');
    	$("input[name=free_hand_shapes][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=free_hand_shapes]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch_6').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch_6').css('background-position', 'left'); }	   

	});
	
	$('.switch_7').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off_7').css('display','none');
	$('.on_7, .off_7').css('text-indent','-10000px');
<?php if ($setting['name_number'] != '0') { ?>
    	$('.switch_7').css('background-position', 'left');
    	$("input[name=name_number][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch_7').css('background-position', 'right');
    	$("input[name=name_number][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=name_number]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch_7').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch_7').css('background-position', 'left'); }	   

	});
	
	
	$('.switch_8').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off_8').css('display','none');
	$('.on_8, .off_8').css('text-indent','-10000px');
<?php if ($setting['social_media_sharing'] != '0') { ?>
    	$('.switch_8').css('background-position', 'left');
    	$("input[name=social_media_sharing][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch_8').css('background-position', 'right');
    	$("input[name=social_media_sharing][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=social_media_sharing]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch_8').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch_8').css('background-position', 'left'); }	   

	});
	
	
	$('.switch_9').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off_9').css('display','none');
	$('.on_9, .off_9').css('text-indent','-10000px');
<?php if ($setting['advance_image_upload'] != '0') { ?>
    	$('.switch_9').css('background-position', 'left');
    	$("input[name=advance_image_upload][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch_9').css('background-position', 'right');
    	$("input[name=advance_image_upload][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=advance_image_upload]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch_9').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch_9').css('background-position', 'left'); }	   

	});
	
	$('.switch_10').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off_10').css('display','none');
	$('.on_10, .off_10').css('text-indent','-10000px');
<?php if ($setting['social_media_image_upload'] != '0') { ?>
    	$('.switch_10').css('background-position', 'left');
    	$("input[name=social_media_image_upload][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch_10').css('background-position', 'right');
    	$("input[name=social_media_image_upload][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=social_media_image_upload]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch_10').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch_10').css('background-position', 'left'); }	   

	});
	
	$('.switch_11').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off_11').css('display','none');
	$('.on_11, .off_11').css('text-indent','-10000px');
<?php if ($setting['preload_template'] != '0') { ?>
    	$('.switch_11').css('background-position', 'left');
    	$("input[name=preload_template][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch_11').css('background-position', 'right');
    	$("input[name=preload_template][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=preload_template]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch_11').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch_11').css('background-position', 'left'); }	   

	});
	
	$('.switch_12').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off_12').css('display','none');
	$('.on_12, .off_12').css('text-indent','-10000px');
<?php  if ($setting['show_clipart'] != '0') { ?>
    	$('.switch_12').css('background-position', 'left');
    	$("input[name=show_clipart][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch_12').css('background-position', 'right');
    	$("input[name=show_clipart][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=show_clipart]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch_12').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch_12').css('background-position', 'left'); }	   

	}); 
	
	$('.switch_13').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off_13').css('display','none');
	$('.on_13, .off_13').css('text-indent','-10000px');
<?php  if ($setting['show_designidea'] != '0') { ?>
    	$('.switch_13').css('background-position', 'left');
    	$("input[name=show_designidea][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch_13').css('background-position', 'right');
    	$("input[name=show_designidea][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=show_designidea]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch_13').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch_13').css('background-position', 'left'); }	   

	}); 
	
    });
</script>
