<div class="pc_contten">
    <div class="pc_top">
        <h1>Add Quantity Range</h1>
	<div class="pc_rgt" style="margin-left: 10px;"><a href="<?php echo BASE_ADMIN_URL . 'qranges'; ?>">Quantity Range Listing</a></div>
	<div class="pc_rgt"><a href="<?php echo BASE_ADMIN_URL . 'qranges/importcsv'; ?>">Import Quantity Ranges</a></div>

    </div>

    <?php
    $error_msg = '';
    if ($this->session->flashdata('error')) {
	$error_msg = $this->session->flashdata('error');
    } else {
	$error_msg = validation_errors();
    }
    ?>
    <?php if ($error_msg != '') { ?>
        <div id="messages_product_view">
    	<ul class="messages">
    	    <li class="error-msg">
		    <?php echo $error_msg; ?>
    	    </li>
    	</ul>
        </div>
    <?php } ?>

    <div>&nbsp;</div>

    <div>
	<div class="pc_contten box-box">
	    <form id="addForm" action="<?php echo BASE_ADMIN_URL . 'qranges/submitdata'; ?>" method="post" class="col-two">
		<input type="hidden" name="qrange_id" value="" />
		<div class="input-area" style="margin-bottom:30px;">
		    <div class="field-set">
			<div class="field-lable"><label>From</label></div>
			<div class="field-form">
			    <div class="input-field validationclass">
				<input type="text" name="quantity_range_from" value="" placeholder="" />
			    </div>
			</div>
		    </div>
		    <div class="field-set">
			<div class="field-lable"><label>To</label></div>
			<div class="field-form">
			    <div class="input-field">
				<input type="text" name="quantity_range_to" value="" placeholder="" />
			    </div>
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
	$("#addForm").validate({
	    rules: {
		quantity_range_from: {
		    required:true,
		    number:true,
		    min: 1,
		    maxlength:5
		},
		quantity_range_to: {
		    required:true,
		    number:true,
		    min: 1,
		    maxlength:5
		}
	    },
	    messages: {
		quantity_range_from: {
		    maxlength:"Maximum 5 numbers allowed."
		},
		quantity_range_to: {
		    maxlength:"Maximum 5 numbers allowed."
		}
	    }
	});
    });
</script>