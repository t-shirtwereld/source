
<input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
<input type="hidden" name="product_setting_id" value="<?php echo $product_settings['product_setting_id']; ?>">
<input type="hidden" name="advance_configuration_id" value="<?php echo $advance_settings['advance_configuration_id']; ?>">

<div class="input-area" style="margin-bottom:30px;">
    <h3 class="legend">Basic Configuration</h3>
    <div class="field-set">
	<label>No.Of.Side</label>
	<div class="input-field">
	    <select name="no_of_sides" id="no_of_sides">
		<option value="">Select no of side</option>
		<option value="1" <?php if ($product_settings['no_of_sides'] == '1'): ?>selected <?php endif; ?>>1</option>
		<option value="2" <?php if ($product_settings['no_of_sides'] == '2'): ?>selected <?php endif; ?>>2</option>
		<option value="3" <?php if ($product_settings['no_of_sides'] == '3'): ?>selected <?php endif; ?>>3</option>
		<option value="4" <?php if ($product_settings['no_of_sides'] == '4'): ?>selected <?php endif; ?>>4</option>
		<option value="5" <?php if ($product_settings['no_of_sides'] == '5'): ?>selected <?php endif; ?>>5</option>
		<option value="6" <?php if ($product_settings['no_of_sides'] == '6'): ?>selected <?php endif; ?>>6</option>
		<option value="7" <?php if ($product_settings['no_of_sides'] == '7'): ?>selected <?php endif; ?>>7</option>
		<option value="8" <?php if ($product_settings['no_of_sides'] == '8'): ?>selected <?php endif; ?>>8</option>

	    </select>
	</div>
    </div>
    <?php if (PACKAGE_TYPE == 'PRO' && $configurefeature['9'] == '1') { ?>
    <div class="field-set">
	<label>Pre Template</label>
	<div class="input-field" style="margin-top:4px;">Yes <input type="radio" <?php if ($product_settings['is_pretemplate'] == 'yes'): ?> checked <?php endif; ?> name="is_pretemplate" value="yes" >&nbsp;&nbsp;&nbsp;&nbsp;No <input type="radio" name="is_pretemplate" <?php if ($product_settings['is_pretemplate'] == 'no'): ?> checked <?php endif; ?>  value="no" ></div>
    </div>
    <?php } else { ?>
	<input type="hidden" name="is_pretemplate" value="no"/>
    <?php } ?>
    <div class="field-set">
	<label>Printing Methods</label>
	<div class="input-field">
	    <?php
	    $selecteds = array();
	    $selecteds = implode(', ', array_column($selected_printing_methods, 'printing_method_id'));
	    $selecteds = explode(', ', $selecteds);
	    ?>
	    <select multiple name="printing_method_id[]">
		<?php foreach ($printing_methods as $printingmethod) { ?>
    		<option value="<?php echo $printingmethod['printing_method_id']; ?>" <?php
		if (in_array($printingmethod['printing_method_id'], $selecteds)) {
		    echo "selected='selected'";
		}
		    ?> ><?php echo $printingmethod['name']; ?><?php if ($printingmethod['status'] == '0'): ?>(Inactive)<?php endif; ?></option>

		<?php } ?>
	    </select></div>
    </div>
    <div class="field-set">
	<label>Base Unit</label>
	<div class="input-field">
	    <select  name="base_unit" id="base_unit">
		<option value="" <?php if ($product_settings['base_unit'] == ''): ?> selected <?php endif; ?>>--Select Base Unit--</option>
                <option value="cm" <?php if ($product_settings['base_unit'] == 'cm'): ?> selected <?php endif; ?>>cm&nbsp;&nbsp;</option>
                <option value="mm" <?php if ($product_settings['base_unit'] == 'mm'): ?> selected <?php endif; ?> >mm&nbsp;&nbsp;</option>
                <option value="in" <?php if ($product_settings['base_unit'] == 'in'): ?> selected <?php endif; ?> >in&nbsp;&nbsp;</option>
                <option value="px" <?php if ($product_settings['base_unit'] == 'px'): ?> selected <?php endif; ?>>px&nbsp;&nbsp;</option>
	    </select>

	</div>
    </div>
    <?php if (PACKAGE_TYPE == 'PRO' && $configurefeature['10'] == '1') { ?>
    <div class="field-set">
	<label>Name Number</label>
	<div class="input-field" style="margin-top:4px;">
	    <?php if(isset($product_settings['name_number'])) { ?>
	    Yes <input type="radio" <?php if ($product_settings['name_number'] == '1') { ?> checked <?php } ?> name="name_number" value="1" >&nbsp;&nbsp;&nbsp;&nbsp;
	    No <input type="radio" name="name_number" <?php if ($product_settings['name_number'] == '0'){ ?> checked <?php } ?>  value="0" >
	    <?php } else { ?>
	    Yes <input type="radio" name="name_number" value="1" >&nbsp;&nbsp;&nbsp;&nbsp;
	    No <input type="radio" name="name_number" value="0" checked>
	    <?php } ?>
	</div>
    </div>
	<?php } else { ?>
	    <input type="hidden" name="name_number" value="0"/>
	<?php } ?>
    <?php if ($product_settings['no_of_sides']) { ?>
        <h3 class="legend">Product Side Labels</h3>
        <div class="field-set">
    	<label>Use Global Side Labels</label>
    	<div class="input-field">
    	    Yes <input type="radio" value="1" name="global_side_label" <?php if ($product_settings['global_side_label'] == '1'): ?> checked <?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;
    	    No <input type="radio" value="0" name="global_side_label" <?php if ($product_settings['global_side_label'] == '0'): ?> checked <?php endif; ?>>
    	</div>
        </div>
        <div id="display_global_side_label" class="product_side_label">
	    <?php for ($i = 1; $i <= $product_settings['no_of_sides']; $i++) { ?>
		<div class="field-set">
		    <label>Side <?php echo $i; ?> Label</label>
		   <?php foreach ($languages as $language) { ?>
		    <div class="input-field">
			<input type="text" name="sidelabel[<?php echo $language['language_id']; ?>][side_<?php echo $i; ?>_label]" value="<?php echo isset($sidelabel[$language['language_id']]) ? htmlspecialchars($sidelabel[$language['language_id']]['side_'.$i.'_label']) : ''; ?><?php echo $sidelabels['side_'.$i.'_label']; ?>" placeholder="" maxlength="12" />
			<img src="<?php echo base_url('assets/flags/' . $language['image']); ?>" alt="">
		    </div>
		     <?php } ?>
		</div>
	    <?php } ?>
        </div>
</div>
<?php } ?>