<div class="pc_contten">
    <div class="pc_top">
        <h1>Add Clipart Category</h1>
	<div class="pc_rgt" style="margin-left: 10px;" ><a href="<?php echo BASE_ADMIN_URL . 'clipart_categories'; ?>">Clipart Categories</a></div>
	<div class="pc_rgt"><a href="<?php echo BASE_ADMIN_URL . 'clipart_categories/importcsv'; ?>">Import Clipart Categories</a></div>
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
	    <form id="addCategoryForm" action="<?php echo BASE_ADMIN_URL . 'clipart_categories/submitdata'; ?>" method="post" class="col-two">
		<input type="hidden" name="clipart_category_id" value="" />
		<div class="input-area" style="margin-bottom:30px;">
		    <div class="field-set">
			<div class="field-lable"><label>Name</label></div>
			<div class="field-form">
			    <?php foreach ($languages as $language) { ?>
    			    <div class="input-field validationclass">
    				<input type="text" name="category_name[<?php echo $language['language_id']; ?>][name]" value="" placeholder="" />
    				<span><img src="<?php echo base_url('assets/flags/' . $language['image']); ?>" alt=""></span>
    			    </div>
			    <?php } ?>
			    <div class="input-field">
			    </div>
			</div>

			<!-- <div class="field-set">
			    <label>Parent Category</label>
			    <div class="input-field">
				<select name="parent_category_id">
				    <option value="0">Select Parent Category</option>
				    <?php foreach ($parentCategories as $parentcategory) { ?>
					<?php if($parentcategory['status'] != '1') { ?> 
					    <option value="<?php echo $parentcategory['clipart_category_id']; ?>"><?php echo $parentcategory['name'].' ( Inactive )'; ?></option>
					<?php } else { ?>
					    <option value="<?php echo $parentcategory['clipart_category_id']; ?>"><?php echo $parentcategory['name']; ?></option>
					<?php } ?>
				    <?php } ?>

				</select>
			    </div> 
			</div>  -->
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

		</div>
	    </form>
	</div>
    </div>
</div>
<div class="clearfix"></div>
<script>
    $(document).ready(function() {	   
	$("#addCategoryForm").validate();  
	$("[name^=category_name]").each(function() {
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
    });
</script>
