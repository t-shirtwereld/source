<div class="pc_contten">
    <div class="pc_top">
        <h1>Add Clipart</h1>
	<div class="pc_rgt" style="margin-left: 10px;" ><a href="<?php echo BASE_ADMIN_URL . 'cliparts'; ?>">Clipart Listing</a></div>
	<div class="pc_rgt"><a href="<?php echo BASE_ADMIN_URL . 'cliparts/importcsv'; ?>">Import Cliparts </a></div>
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
	    <form id="addClipartForm" action="<?php echo BASE_ADMIN_URL . 'cliparts/submitdata'; ?>" method="post" class="col-two" enctype="multipart/form-data">
		<input type="hidden" name="clipart_id" value="" />
		<div class="input-area" style="margin-bottom:30px;">
		    <div class="field-set">
			<div class="field-lable"><label>Name</label></div>
			<div class="field-form">
			    <?php foreach ($languages as $language) { ?>
    			    <div class="input-field validationclass">
    				<input type="text" class="required"  name="clipart_name[<?php echo $language['language_id']; ?>][name]" value="" placeholder="" />
    				<span><img src="<?php echo base_url('assets/flags/' . $language['image']); ?>" alt=""></span>
    			    </div>
			    <?php } ?>
			</div>
		    </div>
		    <div class="field-set">
			<label>Clipart Categories</label>
			<div class="input-field">
			    <select id="clipart_category_id" name="clipart_category_id" class="required">
				<option value="">Select Clipart Category</option>
				<?php foreach ($categories as $category) { ?>
				    <?php if ($category['status'] != '1') { ?> 
					<option value="<?php echo $category['clipart_category_id']; ?>"><?php echo $category['name'] . ' (Inactive)'; ?></option>
				    <?php } else { ?>
					<option value="<?php echo $category['clipart_category_id']; ?>"><?php echo $category['name']; ?></option>
				    <?php } ?>
				    <?php if (!empty($category['child'])) { ?>
					<?php foreach ($category['child'] as $sub) { ?>
					    <?php if ($sub['status'] != '1') { ?> 
						<option value="<?php echo $sub['clipart_category_id']; ?>"><?php echo '&nbsp;&nbsp;&nbsp;' . $sub['name'] . ' (Inactive)'; ?></option>
					    <?php } else { ?>
						<option value="<?php echo $sub['clipart_category_id']; ?>"><?php echo '&nbsp;&nbsp;&nbsp;' . $sub['name']; ?></option>
					    <?php } ?>

					<?php } ?>
				    <?php } ?>
				<?php } ?>
			    </select>
			</div>
		    </div>
		    <?php if (PACKAGE_TYPE == 'PRO') { ?>
		    <div class="field-set">
			<label>Create As a Design Idea</label>
			<div class="input-field" style="margin-top:10px;">
			    <fieldset class="switch_1">
				<label class="off_1">Inactive<input type="radio" class="on_off_1" name="is_clipart_design" value="0"/></label>
				<label class="on_1">Active<input type="radio" class="on_off_1" name="is_clipart_design" value="1"/></label>
			    </fieldset>
			</div>
		    </div>
    		    <div class="field-set" id="clipart_upload">
    			<label>File</label>
    			<div class="input-field">
    			    <input id="file_upload" name="file_upload" type="file"/>
    			</div>
    		    </div>
    		    <div class="field-set" id="clipart_design">
    			<label>&nbsp;</label>
    			<div class="input-field">
    			    <p>To create design idea, first you have to save design details.</p>
    			</div>
    		    </div>
		    <div class="field-set" id="clipart_price">
			<label>Clipart Price</label>
			<div class="input-field" style="margin-top:4px;">
			    <input type="text" class="required"  name="clipart_price"  value="" placeholder="" />
			</div>
		    </div>
		    <?php } else { ?>
    		    <input type="hidden" name="is_clipart_design" value="0" />
    		    <div class="field-set" id="clipart_upload">
    			<label>File</label>
    			<div class="input-field">
    			    <input id="file_upload" name="file_upload" type="file"/>
    			</div>
    		    </div>
		    <div class="field-set" id="clipart_price">
			<label>Clipart Price</label>
			<div class="input-field" style="margin-top:4px;">
			    <input type="text" class="required"  name="clipart_price"  value="" placeholder="" />
			</div>
		    </div>
		    <?php } ?>
		    
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
	</div>
    </div>
</div>
<div class="clearfix"></div> 
<script>
    $(document).ready(function() {	   
	$("#addClipartForm").validate({
	    rules: {
		clipart_category_id: {
		    required:true
		},
		clipart_price: {
		    number:true
		},
		file_upload: {
		    required:true,
		    extension: 'svg|SVG'
		}
	    },
	    messages: {
		file_upload: {
		    extension: 'Please upload SVG file only..!'
		}
	    }
	});  
	$("[name^=clipart_name]").each(function() {
	    $(this).rules('add', {required: true,maxlength: 50, messages: {maxlength: "Maximum 50 characters allowed."}});
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
	$("input[name=is_clipart_design][value=0]").attr('checked', 'checked');

	$("input[name=is_clipart_design]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch_1').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch_1').css('background-position', 'left'); }	   

	});
	
	$("#clipart_upload").show();
	$("#clipart_design").hide();
	$("#show_as_design").hide();
	$("#clipart_price").show();
	
	$("input[name$='is_clipart_design']").click(function() {
	    var is_design = $(this).val();
	    // if (this.value == '1'); { No semicolon and I used === instead of ==
	    if (is_design === '1'){
		$("#clipart_design").show();
		$("#show_as_design").show();
		$("#clipart_upload").hide();
		$("#clipart_price").hide();
	    } else {
		$("#clipart_upload").show();
		$("#show_as_design").hide();
		$("#clipart_design").hide();
		$("#clipart_price").show();
	    }
	});

    });
</script>
