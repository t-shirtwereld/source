
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
                    <option value="1" <?php if($product_settings['no_of_sides']=='1'): ?>selected <?php endif; ?>>1</option>
                    <option value="2" <?php if($product_settings['no_of_sides']=='2'): ?>selected <?php endif; ?>>2</option>
                    <option value="3" <?php if($product_settings['no_of_sides']=='3'): ?>selected <?php endif; ?>>3</option>
                    <option value="4" <?php if($product_settings['no_of_sides']=='4'): ?>selected <?php endif; ?>>4</option>
                    <option value="5" <?php if($product_settings['no_of_sides']=='5'): ?>selected <?php endif; ?>>5</option>
                    <option value="6" <?php if($product_settings['no_of_sides']=='6'): ?>selected <?php endif; ?>>6</option>
                    <option value="7" <?php if($product_settings['no_of_sides']=='7'): ?>selected <?php endif; ?>>7</option>
                    <option value="8" <?php if($product_settings['no_of_sides']=='8'): ?>selected <?php endif; ?>>8</option>

                </select>
            </div>
            </div>
            <div class="field-set">
                <label>Pre Template</label>
                <div class="input-field" style="margin-top:4px;">Yes <input type="radio" <?php if($product_settings['is_pretemplate']=='yes'): ?> checked <?php endif; ?> name="is_pretemplate" value="yes" >&nbsp;&nbsp;&nbsp;&nbsp;No <input type="radio" name="is_pretemplate" <?php if($product_settings['is_pretemplate']=='no'): ?> checked <?php endif; ?>  value="no" ></div>
            </div>
            <div class="field-set">
                <label>Printing Methods</label>
                <div class="input-field">
                <?php
                $selecteds = array();
                $selecteds = implode(', ', array_column($selected_printing_methods, 'printing_method_id'));
                $selecteds = explode(', ', $selecteds);
               
                ?>
                <select multiple name="printing_method_id[]">
                <?php foreach($printing_methods as $printingmethod) { ?>
                  <option value="<?php echo $printingmethod['printing_method_id']; ?>" <?php
                        if (in_array($printingmethod['printing_method_id'], $selecteds)) {
                        echo "selected='selected'";
                        }
                        ?> ><?php echo $printingmethod['name']; ?></option>
                    
                    <?php } ?>
                </select></div>
            </div>
            <div class="field-set">
              <label>Base Unit<em class="required">*</em></label>
                <div class="input-field">
               <select  name="base_unit" id="base_unit">
                <option selected="selected" value="cm" <?php if($product_settings['base_unit']=='cm'): ?> selected <?php endif; ?>>cm&nbsp;&nbsp;</option>
                <option value="mm" <?php if($product_settings['base_unit']=='mm'): ?> selected <?php endif; ?> >mm&nbsp;&nbsp;</option>
                <option value="in" <?php if($product_settings['base_unit']=='in'): ?> selected <?php endif; ?> >in&nbsp;&nbsp;</option>
                <option value="px" <?php if($product_settings['base_unit']=='px'): ?> selected <?php endif; ?>>px&nbsp;&nbsp;</option>
                </select>

               </div>
            </div>
            <?php //echo $package; ?>
            <?php if($package=='CANVAS') : ?>
            <div class="field-set">
                <label>Is Canvas Product</label>
                <div style="margin-top:4px;" class="input-field">Yes <input type="radio" <?php if($product_settings['is_canvas']=='yes'): ?> checked <?php endif; ?> value="yes" name="is_canvas">&nbsp;&nbsp;&nbsp;&nbsp;No <input type="radio" value="no" <?php if($product_settings['is_canvas']=='no'): ?> checked <?php endif; ?> name="is_canvas"></div>
            </div>
           <?php endif; ?> 
        </div>
     <?php if($package=='CANVAS') : ?>
        <div class="input-area" <?php if($product_settings['is_canvas']=='yes'): ?> style="margin-bottom:30px;display:block;" <?php endif; ?> <?php if($product_settings['is_canvas']=='no'): ?> style="display:none;" <?php endif; ?> id="advancesettings">
            <h3 class="legend">Advance Configuration</h3>
      <div class="field-set">
                <label>Width<em class="required">*</em></label>
                <div class="input-field"><input type="text" name="width" id="width" value="<?php echo $advance_settings['width'] ?>" placeholder=""></div>
            </div>
            <div class="field-set">
                <label>Height<em class="required">*</em></label>
                <div class="input-field"><input type="text" name="height" id="height" value="<?php echo $advance_settings['height'] ?>" placeholder=""></div>
            </div>
            <div class="field-set">
                <label>Cut Margin <em class="required">*</em></label>
                <div class="input-field">
                    <input type="text" style="width:50px" placeholder="" id="cut_1" value="<?php echo $advance_settings['cut_1'] ?>" name="cut_1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" style="width:50px" placeholder="" id="cut_2" value="<?php echo $advance_settings['cut_2'] ?>" name="cut_2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" style="width:50px" placeholder="" id="cut_3" value="<?php echo $advance_settings['cut_3'] ?>" name="cut_3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" style="width:50px" placeholder="" id="cut_4" value="<?php echo $advance_settings['cut_4'] ?>" name="cut_4">
                </div>
            </div>
            <div class="field-set">
                <label>Bleed Margin  <em class="required">*</em></label>
                <div class="input-field">
                    <input type="text" style="width:50px" placeholder="" id="bleed_1" value="<?php echo $advance_settings['bleed_1'] ?>" name="bleed_1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" style="width:50px" placeholder="" id="bleed_2" value="<?php echo $advance_settings['bleed_2'] ?>" name="bleed_2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" style="width:50px" placeholder="" id="bleed_3" value="<?php echo $advance_settings['bleed_3'] ?>" name="bleed_3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" style="width:50px" placeholder="" id="bleed_4" value="<?php echo $advance_settings['bleed_4'] ?>" name="bleed_4">
                </div>
            </div>
            <div class="field-set">
                <label>Corner Radius<em class="required">*</em></label>
                <div class="input-field"><input type="text" id="corner_radius" name="corner_radius" value="<?php echo $advance_settings['corner_radius'] ?>" placeholder=""></div>
            </div>
            <div class="field-set">
              <label>Background Color Picker Type<em class="required">*</em></label>
                <div class="input-field">
                    <select name="color_picker_type" id="color_picker_type">
                        <option value="">Select color picker</option>
                        <option value="1" <?php if($advance_settings['color_picker_type']=='1'): ?> selected <?php endif; ?>>Full Color Picker</option>
                        <option value="2" <?php if($advance_settings['color_picker_type']=='2'): ?> selected <?php endif; ?>>Printable Colors</option>
                        <option value="3" <?php if($advance_settings['color_picker_type']=='3'): ?> selected <?php endif; ?>>Custom Colors</option>
                    </select>
                 </div>
            </div>
        </div>
    <?php endif; ?>