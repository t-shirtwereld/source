<script>    
    $(document).ready(function() {
	$.validator.addMethod('greaterThan', function(value, element, param) {
	    return (  Number(value) >  Number($(param[0]).val()) );
	}, "Max quantity must be greater than Min quantity");
	  
	$("#addform").validate({
	    ignore: "",
	    rules: {
		pricing_logic: "required",
		printable_color_type: "required",
		min_qty:{
		    required: true,
		    number: true,
		    min: 1
		},
		max_qty:{
		    required: true,
		    number: true,
		    greaterThan: $("#min_qty")
		    
		},
		image_price:{
		    required: '#is_image_upload:checked',
		    number: true
		},
		artwork_setup_price_type:"required",
		artwork_setup_price:{
		    required: true,
		    number: true
		},
		name_price:{
		    number: true 
		},
		number_price:{
		    number: true
		}
	    },
	    invalidHandler: function(form, validator) {
		var errors = validator.numberOfInvalids();

		if (errors) {
		    $("#error-message").show().text("Warning: Please check the form carefully for errors!");
		    $("#error-message").addClass('error-msg');
		} else {
		    $("#error-message").hide();
		}
	    },
	    messages: {
		pricing_logic: "This is required field.",
		printable_color_type: "This is required field.",
		min_qty: {
		    required : "This is required field.",
		    number : "Please enter only numeric values"
		},
		max_qty: {
		    required : "This is required field.",
		    number : "Please enter only numeric values"
		},
		image_price: {
		    required : "This is required field.",
		    number : "Please enter only numeric values"
		},
		artwork_setup_price_type: "This is required field.",
		artwork_setup_price: {
		    required : "This is required field.",
		    number : "Please enter only numeric values"
		},
		name_price:{
		    number: "Please enter only numeric values" 
		},
		number_price:{
		    number: "Please enter only numeric values"
		}
	    }
	}); 
	var myRule = {
	    required: true,
	    maxlength: 50,
	    messages: {
		required: "This is required field.",
		maxlength: "Maximum 50 characters allowed."
	    }
	}   
	$(".printing_method_name").each(function() {
	    $(this).rules('add', myRule);
	});
    
	var myRule1 = {
	    required: function(element) {
		return $('input[name="is_alert"]:checked').val() == '1';
	    }
	}
	$(".alert_message").each(function() {
	    $(this).rules('add', myRule1);
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
	
	$('.switch_1').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off_1').css('display','none');
	$('.on_1, .off_1').css('text-indent','-10000px');
	$('.switch_1').css('background-position', 'right');
	$("input[name=is_image_upload][value=0]").attr('checked', 'checked');
	
	$("input[name=is_image_upload]").change(function() {
	    var button1 = $(this).val();
	  
	    if(button1 == '0'){ $('.switch_1').css('background-position', 'right'); }
	    if(button1 == '1'){ $('.switch_1').css('background-position', 'left'); }	   

	});
		
	$('.switch_2').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off_2').css('display','none');
	$('.on_2, .off_2').css('text-indent','-10000px');
	$('.switch_2').css('background-position', 'right');
	$("input[name=is_alert][value=0]").attr('checked', 'checked');
	
	$("input[name=is_alert]").change(function() {
	    var button2 = $(this).val();
	  
	    if(button2 == '0'){ $('.switch_2').css('background-position', 'right'); }
	    if(button2 == '1'){ $('.switch_2').css('background-position', 'left'); }	   

	});
	
	$("#printablecolorCategories").hide();
	$( "#printable_color_type").change(function(){
	    if ($("#printable_color_type").val() == '2') {
		$("#printablecolorCategories").show();
	    } else { 
		$("#printablecolorCategories").hide();
	    }
	});
	    
    });
</script> 
<div class="pc_contten">
    <div class="pc_top">
        <h1>Add Printing Method</h1>
	<div class="pc_rgt"><a href="<?php echo BASE_ADMIN_URL . 'printingmethods'; ?>">Printing Methods</a></div>
    </div>

    <div>&nbsp;</div>

    <div id="error-message"></div>

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
	    <form id="addform" action="<?php echo BASE_ADMIN_URL . 'printingmethods/submitdata'; ?>" method="post" >
		<input type="hidden" name="printing_method_id" value="" />
		<div id="tabs-container">
		    <ul class="tabs-menu">
			<li class="current"><a href="#tab-1">Basic Information</a></li>
		    </ul>
		    <div class="tab">
			<div id="tab-1" class="tab-content">
			    <div class="col-two">
				<div class="input-area" style="margin-bottom:30px;">
				    <div class="field-set">
					<div class="field-lable"><label>Name</label></div>
					<div class="field-form">
					    <?php foreach ($languages as $language) { ?>
    					    <div class="input-field validationclass">
    						<input type="text" class="printing_method_name" name="printingmethod_description[<?php echo $language['language_id']; ?>][name]" value="" placeholder="" />
    						<span><img src="<?php echo base_url('assets/flags/' . $language['image']); ?>" alt=""></span>
    					    </div>
					    <?php } ?>
					</div>
				    </div>
				    <div class="field-set">
					<label>Pricing Logic</label>
					<div class="input-field">
					    <select name="pricing_logic">
						<option value="">Select Pricing Logic</option>
						<option value="1">Fixed</option>
						<option value="2">No. of Colors</option>
						<option value="3">Artwork Sizes</option>
					    </select>
					</div>
				    </div>

				    <div class="field-set">
					<label>Printable Color Picker Type</label>
					<div class="input-field">
					    <select id="printable_color_type" name="printable_color_type">
						<option value="">Select Printable Color Picker Type</option>
						<option value="1">Full</option>
						<option value="2">Custom</option>
					    </select>
					</div>
				    </div>
				    <div class="field-set" id="printablecolorCategories">
					<label>Printable Color</label>
					<div class="input-field">
					    <select name="printablecolorCategories[]" multiple>
						<?php foreach ($color_categories as $cc) { ?>
						    <?php if ($cc['status'] != '1') { ?>
							<option value="<?php echo $cc['printablecolor_category_id']; ?>"><?php echo $cc['category_name'] . ' (Inactive)'; ?></option>
						    <?php } else { ?>
							<option value="<?php echo $cc['printablecolor_category_id']; ?>"><?php echo $cc['category_name']; ?></option>
						    <?php } ?>

						<?php } ?>
					    </select></div>
				    </div>

				    <div class="field-set">
					<label>Min Qty :</label>
					<div class="input-field">
					    <input type="text" name="min_qty" id="min_qty" value="" placeholder="">
					    <div class="message-txt">Min. Order Qunatity should not be less than Product Settings.</div>
					</div>
				    </div>
				    <div class="field-set">
					<label>Max Qty :</label>
					<div class="input-field">
					    <input type="text" name="max_qty" value="" placeholder="">
					    <div class="message-txt">Max. Order Qunatity should not be more than Product Settings.</div>
					</div>
				    </div>
				    <div class="field-set">
					<label>Enable Image Upload</label>
					<div class="input-field" style="margin-top:4px;">
					    <fieldset class="switch_1">
						<label class="off_1">No<input type="radio" class="on_off_1" name="is_image_upload" value="0"/></label>
						<label class="on_1">Yes<input type="radio" class="on_off_1" name="is_image_upload" value="1"/></label>
					    </fieldset>
					</div>
				    </div>
				    <div class="field-set">
					<label>Charge For Image Upload :</label>
					<div class="input-field"><input type="text" name="image_price" value="" placeholder=""></div>
				    </div>
				    <div class="field-set">
					<label>Artwork Setup Price Type</label>
					<div class="input-field">
					    <select name="artwork_setup_price_type">
						<option value="">Select Artwork Setup Price Type</option>
						<option value="1">Fixed</option>
						<option value="2">Per Color</option>
					    </select>
					    <div class="message-txt">Consider this as screen setup fee for Screen Printing or digitization fee for embroidery. It can be defined as fixed fee or based on no. Of colors in artwork.</div>
					</div>
				    </div>
				    <div class="field-set">
					<label>Artwork Setup Fee :</label>
					<div class="input-field"><input type="text" name="artwork_setup_price" value="" placeholder=""></div>
				    </div>
				     <?php if(PACKAGE_TYPE == 'PRO') { ?>
				    <div class="field-set">
					<label>Name Price:</label>
					<div class="input-field"><input type="text" name="name_price" value="" placeholder=""></div>
				    </div>
				    <div class="field-set">
					<label>Number Price:</label>
					<div class="input-field"><input type="text" name="number_price" value="" placeholder=""></div>
				    </div>
				     <?php } else { ?>
					<input type="hidden" name="name_price" value="0" />
					<input type="hidden" name="number_price" value="0" />  
				    <?php } ?>
				    <div class="field-set">
					<label>Is Alert</label>
					<div class="input-field" style="margin-top:4px;">
					    <fieldset class="switch_2">
						<label class="off_2">No<input type="radio" class="on_off_2" name="is_alert" value="0"/></label>
						<label class="on_2">Yes<input type="radio" class="on_off_2" name="is_alert" value="1"/></label>
					    </fieldset>
					</div>
					<div class="field-set">
					    <label>Alert Message :</label>
					    <div class="field-form">
						<?php foreach ($languages as $language) { ?>
    						<div class="input-field">
    						    <textarea style="width:100%;" class="alert_message" cols="50" style="width:100%;margin-bottom:15px;" rows="4" name="printingmethod_description[<?php echo $language['language_id']; ?>][alert_message]"></textarea>
    						    <span><img src="<?php echo base_url('assets/flags/' . $language['image']); ?>" alt=""></span>
    						</div>
						<?php } ?>
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
				</div>
			    </div>
			</div>
		    </div>
		    <div class="clearfix"></div>
		    <input type="submit" class="btn submit" value="Submit" />
	    </form>
	</div>
    </div>
</div>
<div class="clearfix"></div>