<div class="pc_contten">
    <div class="pc_top">
        <h1>Edit Language</h1>
	<div class="pc_rgt"><a href="<?php echo BASE_ADMIN_URL . 'language'; ?>">Languages List</a></div>
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
	    <form id="editForm" action="<?php echo BASE_ADMIN_URL . 'language/submitdata'; ?>" method="post" class="col-two">
		<input type="hidden" name="language_id" value="<?php echo $language_row['language_id']; ?>" />
		<div class="input-area" style="margin-bottom:30px;">
		    <div class="field-set">
			<label>Language Name</label>
			<div class="field-form">
			    <div class="input-field validationclass">
				<input type="text" name="name" value="<?php echo htmlspecialchars($language_row['name']); ?>" placeholder="" />
			    </div>
			</div>
		    </div>
		    <div class="field-set">
			<label>Iso Code</label>
			<div class="input-field">
			    <input name="iso_code" type="text" value="<?php echo $language_row['iso_code']; ?>" placeholder=""/>
			</div>
		    </div>
		    <div class="field-set">
			<label>Language Code</label>
			<div class="input-field">
			    <input name="language_code" type="text" value="<?php echo $language_row['language_code']; ?>" placeholder=""/>
			</div>
		    </div>
		    <div class="field-set">
			<label>Language Abbreviation</label>
			<div class="input-field">
			    <input name="connector" type="text" value="<?php echo $language_row['connector']; ?>" placeholder=""/>
			</div>
		    </div>
		    <div class="field-set">
			<label>Right To Left</label>
			<div class="input-field">
			   <fieldset class="switch_1">
				    <label class="off_1">No<input type="radio" class="on_off_1" name="is_rtl" value="0"/></label>
				    <label class="on_1">Yes<input type="radio" class="on_off_1" name="is_rtl" value="1"/></label>
				</fieldset>
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
	</div>
    </div>
</div>
<div class="clearfix"></div> 
<script>
    $(document).ready(function() {	   
	$("#editForm").validate({
	    rules: {
		name: {
		    required:true
		},
		iso_code: {
		    required:true
		},
		language_code: {
		    required:true
		},
		connector: {
		    required:true
		}
	    }
	});
	
	$('.switch').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off').css('display','none');
	$('.on, .off').css('text-indent','-10000px');
<?php if ($language_row['status'] != '0') { ?>
    	$('.switch').css('background-position', 'left');
	$("input[name=status][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch').css('background-position', 'right');
	$("input[name=status][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=status]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch').css('background-position', 'left'); }	   

	});
	
	$('.switch_1').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off_1').css('display','none');
	$('.on_1, .off_1').css('text-indent','-10000px');
<?php if ($language_row['is_rtl'] != '0') { ?>
    	$('.switch_1').css('background-position', 'left');
	$("input[name=is_rtl][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch_1').css('background-position', 'right');
	$("input[name=is_rtl][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=is_rtl]").change(function() {
	    var button1 = $(this).val();

	    if(button1 == '0'){ $('.switch_1').css('background-position', 'right'); }
	    if(button1 == '1'){ $('.switch_1').css('background-position', 'left'); }	   

	});
    });
</script>