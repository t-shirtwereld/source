<div class="pc_contten">
    <div class="pc_top">
        <h1>Import Quantity Ranges</h1>
	<div class="pc_rgt"><a href="<?php echo BASE_ADMIN_URL . 'qranges'; ?>">Quantity Ranges</a></div>
    </div>

    <?php
    $error_msg = '';
    if (isset($error) && $error != '') {
	$error_msg = $error;
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
	<div class="pc_contten box-box box-download">
	    <form action="<?php echo BASE_ADMIN_URL . 'qranges/upload_importcsv'; ?>" method="post" class="col-two" enctype="multipart/form-data">
		<div class="input-area" style="margin-bottom:30px;">
		    <div class="field-set">
			<label>Upload CSV</label>			    
			<div class="input-field">
			    <input type="file" name="qranges_csv" value=""/>  
			    <p>Note: First need to download sample format, fill the records and use the same format to import Quantity Ranges</p>
			</div>
		    </div>
		    <div class="input-area">
			<div class="field-set">
			    <label>&nbsp;</label>
			    <div class="input-field">
				<input type="submit" class="btn" value="Import Quantity Ranges" />
			    </div>
			</div>
		    </div>
		</div>
	    </form>
	    <a class="download-btn" href="<?php echo base_url('assets/images/importcsvsample/Quantity_Range_data.csv'); ?>">Download Sample CSV Format</a>
	</div>
    </div>
</div>
<div class="clearfix"></div>
