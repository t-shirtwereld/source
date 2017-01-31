<div class="pc_contten">
    <div class="pc_top">
        <h1>General Configuration</h1>
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
	    <form id="settingForm" action="<?php echo BASE_ADMIN_URL . 'settings/update_general_settings'; ?>" method="post" class="col-two" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?php echo $setting['id']; ?>" />
		<div class="input-area general_setting">
		    <h3 class="legend">General Setting</h3>
		    <div class="field-set">
			<label>Base Unit</label>
			<div class="input-field validationclass">
			    <select name="base_unit">
				<option value="mm" <?php
    if ($setting['base_unit'] == 'mm') {
	echo "selected='selected'";
    }
    ?>>Milimeters</option>
				<option value="cm" <?php
					if ($setting['base_unit'] == 'cm') {
					    echo "selected='selected'";
					}
    ?>>Centimeters</option>
				<option value="in" <?php
					if ($setting['base_unit'] == 'in') {
					    echo "selected='selected'";
					}
    ?>>Inches</option>
				<option value="px" <?php
					if ($setting['base_unit'] == 'px') {
					    echo "selected='selected'";
					}
    ?>>Pixels</option>
			    </select></div>
		    </div>
		   <!--  <div class="field-set">
			<label>Output Format</label>
			<div class="input-field">
			    <select name="output_format">
				<option value="SVG" <?php
					// if ($setting['output_format'] == 'SVG') {
					//     echo "selected='selected'";
					// }
    ?>>SVG</option>
				<option value="BOTH" <?php
					// if ($setting['output_format'] == 'BOTH') {
					//     echo "selected='selected'";
					// }
    ?>>SVG + PDF</option>
			    </select></div>
		    </div> -->
            <input type="hidden" name="output_format" value="SVG">
		    <input type="hidden" name="pdf_output_type" value="RGB">
		    <!-- <div class="field-set">
			<label>PDF Output Type</label>
			<div class="input-field">
			    <select name="pdf_output_type">
				<option value="RGB" <?php
					// if ($setting['pdf_output_type'] == 'RGB') {
					//     echo "selected='selected'";
					// }
    ?>>RGB</option>
				<option value="CMYK" <?php
					// if ($setting['pdf_output_type'] == 'CMYK') {
					//     echo "selected='selected'";
					// }
    ?>>RGB + CMYK</option>
			    </select></div>
		    </div> -->
		    <div class="field-set">
			<label>Image Resolution</label>
			<div class="input-field">
			    <input type="text" name="image_resolution" value="<?php echo $setting['image_resolution']; ?>" placeholder="">
			</div>
		    </div>  
		</div>
		<div class="input-area">
		    <h3 class="legend">Watermark Configuration</h3>
		    <div class="field-set">
			<label>Enabled</label>
			<div class="input-field">
			    <fieldset class="switch">
				<label class="off">Inactive<input type="radio" class="on_off" name="watermark_status" value="0"/></label>
				<label class="on">Active<input type="radio" class="on_off" name="watermark_status" value="1"/></label>
			    </fieldset>
			</div>
		    </div>
		    <div class="field-set">
			<label>Logo</label>
			<div class="input-field">
			    <input type="file" name="watermark_logo" value="" placeholder="" style="margin-bottom: 10px;">
			    <?php if (isset($setting['watermark_logo']) && $setting['watermark_logo'] != '') { ?>
    			    <img src="<?php echo base_url('assets/images/logo/' . $setting['watermark_logo']); ?>" width="75" height="75" style="display: block;" />
			    <?php } ?>
			</div>
		    </div>
		</div>
		<div class="input-area">
		    <h3 class="legend">Global Side Labels</h3>	
		    <div class="product_side_label">
			<div class="field-set">
			    <label>Side 1 Label</label>
			    <?php foreach ($languages as $language) { ?>
    			    <div class="input-field">
    				<input type="text" name="sidelabels[<?php echo $language['language_id']; ?>][side_1_label]" value="<?php echo isset($sidelabels[$language['language_id']]) ? htmlspecialchars($sidelabels[$language['language_id']]['side_1_label']) : ''; ?><?php echo $sidelabels['side_1_label']; ?>" placeholder="" maxlength="12" />
    				<img src="<?php echo base_url('assets/flags/' . $language['image']); ?>" alt="">
    			    </div>
			    <?php } ?>
			</div>

			<div class="field-set">
			    <label>Side 2 Label</label>
			    <?php foreach ($languages as $language) { ?>
    			    <div class="input-field">
    				<input type="text" name="sidelabels[<?php echo $language['language_id']; ?>][side_2_label]" value="<?php echo isset($sidelabels[$language['language_id']]) ? htmlspecialchars($sidelabels[$language['language_id']]['side_2_label']) : ''; ?><?php echo $sidelabels['side_2_label']; ?>" placeholder="" maxlength="12" />
    				<img src="<?php echo base_url('assets/flags/' . $language['image']); ?>" alt="">
    			    </div>
			    <?php } ?>
			</div>
			<div class="field-set">
			    <label>Side 3 Label</label>
			    <?php foreach ($languages as $language) { ?>
    			    <div class="input-field">
    				<input type="text" name="sidelabels[<?php echo $language['language_id']; ?>][side_3_label]" value="<?php echo isset($sidelabels[$language['language_id']]) ? htmlspecialchars($sidelabels[$language['language_id']]['side_3_label']) : ''; ?><?php echo $sidelabels['side_3_label']; ?>" placeholder="" maxlength="12" />
    				<img src="<?php echo base_url('assets/flags/' . $language['image']); ?>" alt="">
    			    </div>
			    <?php } ?>
			</div>
			<div class="field-set">
			    <label>Side 4 Label</label>
			    <?php foreach ($languages as $language) { ?>
    			    <div class="input-field">
    				<input type="text" name="sidelabels[<?php echo $language['language_id']; ?>][side_4_label]" value="<?php echo isset($sidelabels[$language['language_id']]) ? htmlspecialchars($sidelabels[$language['language_id']]['side_4_label']) : ''; ?><?php echo $sidelabels['side_4_label']; ?>" placeholder="" maxlength="12" />
    				<img src="<?php echo base_url('assets/flags/' . $language['image']); ?>" alt="">
    			    </div>
			    <?php } ?>
			</div>
			<div class="field-set">
			    <label>Side 5 Label</label>
			    <?php foreach ($languages as $language) { ?>
    			    <div class="input-field">
    				<input type="text" name="sidelabels[<?php echo $language['language_id']; ?>][side_5_label]" value="<?php echo isset($sidelabels[$language['language_id']]) ? htmlspecialchars($sidelabels[$language['language_id']]['side_5_label']) : ''; ?><?php echo $sidelabels['side_5_label']; ?>" placeholder="" maxlength="12" />
    				<img src="<?php echo base_url('assets/flags/' . $language['image']); ?>" alt="">
    			    </div>
			    <?php } ?>
			</div>
			<div class="field-set">
			    <label>Side 6 Label</label>
			    <?php foreach ($languages as $language) { ?>
    			    <div class="input-field">
    				<input type="text" name="sidelabels[<?php echo $language['language_id']; ?>][side_6_label]" value="<?php echo isset($sidelabels[$language['language_id']]) ? htmlspecialchars($sidelabels[$language['language_id']]['side_6_label']) : ''; ?><?php echo $sidelabels['side_6_label']; ?>" placeholder="" maxlength="12" />
    				<img src="<?php echo base_url('assets/flags/' . $language['image']); ?>" alt="">
    			    </div>
			    <?php } ?>
			</div>
			<div class="field-set">
			    <label>Side 7 Label</label>
			    <?php foreach ($languages as $language) { ?>
    			    <div class="input-field">
    				<input type="text" name="sidelabels[<?php echo $language['language_id']; ?>][side_7_label]" value="<?php echo isset($sidelabels[$language['language_id']]) ? htmlspecialchars($sidelabels[$language['language_id']]['side_7_label']) : ''; ?><?php echo $sidelabels['side_7_label']; ?>" placeholder="" maxlength="12" />
    				<img src="<?php echo base_url('assets/flags/' . $language['image']); ?>" alt="">
    			    </div>
			    <?php } ?>
			</div>
			<div class="field-set">
			    <label>Side 8 Label</label>
			    <?php foreach ($languages as $language) { ?>
    			    <div class="input-field">
    				<input type="text" name="sidelabels[<?php echo $language['language_id']; ?>][side_8_label]" value="<?php echo isset($sidelabels[$language['language_id']]) ? htmlspecialchars($sidelabels[$language['language_id']]['side_8_label']) : ''; ?><?php echo $sidelabels['side_8_label']; ?>" placeholder="" maxlength="12" />
    				<img src="<?php echo base_url('assets/flags/' . $language['image']); ?>" alt="">
    			    </div>
			    <?php } ?>
			</div>
		    </div>
		</div>
		<div class="input-area global_message">
		    <h3 class="legend">Global Message Board Email Notification</h3>
		    <div class="notify_email">
			<?php if (!empty($emails)) { ?>
			    <?php $i = 1; ?>
			    <?php foreach ($emails as $email) { ?>
				<div class="field-set email_length remove-add">
				    <label class="email_number">Email <?php echo $i; ?></label>
				    <div class="input-field relative">
					<input type="text" name="email[]" value="<?php echo $email['email']; ?>" placeholder="Enter Email..">
					<a href="#" class="remove-box">Remove Email</a>
				    </div>
				</div>
				<?php $i++; ?>
			    <?php } ?>
			<?php } ?>			
		    </div>
		    <div class="add-email-form">
			<a id="add-box" href="#">Add New Email Address</a>
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
<script type="text/javascript">
    $(document).ready(function() {	
	$('#messages_product_view').fadeIn().delay(10000).fadeOut();
	
	$("#settingForm").validate({
	    rules: {
		watermark_logo: {
		    extension: 'jpg|png|gif|jpeg'
		},
		side_1_label: {
		    required:true,
		    maxlength: 50
		},
		side_2_label: {
		    required:true,
		    maxlength: 50
		},
		side_3_label: {
		    required:true,
		    maxlength: 50
		},
		side_4_label: {
		    required:true,
		    maxlength: 50
		},
		side_5_label: {
		    required:true,
		    maxlength: 50
		},
		side_6_label: {
		    required:true,
		    maxlength: 50
		},
		side_7_label: {
		    required:true,
		    maxlength: 50
		},
		side_8_label: {
		    required:true,
		    maxlength: 50
		}
	    },
	    messages: {
		watermark_logo: {
		    extension: 'Please upload jpg,png,gif,jpeg file only..!'
		}
	    }
	});
	
	$("[name^=email]").each(function() {
	    $(this).rules('add', {required: true,email: true});
	});
		
	$('.switch').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off').css('display','none');
	$('.on, .off').css('text-indent','-10000px');
<?php if ($setting['watermark_status'] != '0') { ?>
    	$('.switch').css('background-position', 'left');
    	$("input[name=watermark_status][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch').css('background-position', 'right');
    	$("input[name=watermark_status][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=watermark_status]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch').css('background-position', 'left'); }	   

	});
	
	$('#add-box').click(function(){
	    var n = $('.email_length').length + 1;
	    var box_html = $('<div class="field-set email_length remove-add"><label class="email_number">Email ' + n +'</label><div class="input-field relative"><input type="text" name="email[]" value="" placeholder="Enter Email.."><a href="#" class="remove-box">Remove Email</a></div></div>');
	    $('.notify_email').append(box_html);
	    box_html.fadeIn('slow');
	    return false;
	});
    
	$('.notify_email').on('click', '.remove-box', function(){
	    if (confirm("Are you sure?") == true) {
		$(this).parent().parent().fadeOut("slow", function() {
		    $(this).remove();
		    $('.email_number').each(function(index){
			$(this).text('Email ' + (index + 1));
		    });
		});
	    }
	    return false;
	});
    });
    
</script>

