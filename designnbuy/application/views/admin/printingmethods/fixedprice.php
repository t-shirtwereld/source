<table id="fixedprices" class="pc_reference table-shadow quantity-color" cellpadding="0" cellspacing="0">
    <thead>
	<tr>
	    <th>Quantity Count</th>
	    <th>Side1 Price</th>
	    <th>Side2 Price</th>
	    <th>Side3 Price</th>
	    <th>Side4 Price</th>
	    <th>Side5 Price</th>
	    <th>Side6 Price</th>
	    <th>Side7 Price</th>
	    <th>Side8 Price</th>
	    <th class="width-sm">Actions</th>
	</tr>
    </thead>
    <?php $fp_row = 0; ?>
    <?php foreach ($printingmethod_row['fixedPrice'] as $fp) { ?>
        <tbody id="qa-row<?php echo $fp_row; ?>">
    	<tr>
    	    <td>
    		<select name="fixedPrice[<?php echo $fp_row; ?>][quantity_range_id]">
			<?php foreach ($qranges as $qr) { ?>
			    <?php if ($qr['qrange_id'] === $fp['quantity_range_id']) { ?>
	    		    <option value="<?php echo $qr['qrange_id']; ?>" selected="selected"><?php echo $qr['quantity_range_from'] . ' - ' . $qr['quantity_range_to']; ?></option>
			    <?php } else { ?>
	    		    <option value="<?php echo $qr['qrange_id']; ?>"><?php echo $qr['quantity_range_from'] . ' - ' . $qr['quantity_range_to']; ?></option>
			    <?php } ?>
			<?php } ?>
    		</select>
    	    </td>
    	    <td>
    		<input type="text" name="fixedPrice[<?php echo $fp_row; ?>][first_side_price]" id="first_side_fpprice<?php echo $fp_row; ?>" value="<?php echo $fp['first_side_price']; ?>" style="width: 55px;"> 
    	    </td>
    	    <td>
    		<input type="text" name="fixedPrice[<?php echo $fp_row; ?>][second_side_price]" id="second_side_fpprice<?php echo $fp_row; ?>" value="<?php echo $fp['second_side_price']; ?>" style="width: 55px;">
    	    </td>
    	    <td>
    		<input type="text" name="fixedPrice[<?php echo $fp_row; ?>][third_side_price]" id="third_side_fpprice<?php echo $fp_row; ?>" value="<?php echo $fp['third_side_price']; ?>" style="width: 55px;"> 
    	    </td>
    	    <td>
    		<input type="text" name="fixedPrice[<?php echo $fp_row; ?>][fourth_side_price]" id="fourth_side_fpprice<?php echo $fp_row; ?>" value="<?php echo $fp['fourth_side_price']; ?>" style="width: 55px;">
    	    </td>
    	    <td>
    		<input type="text" name="fixedPrice[<?php echo $fp_row; ?>][fifth_side_price]" id="fifth_side_fpprice<?php echo $fp_row; ?>" value="<?php echo $fp['fifth_side_price']; ?>" style="width: 55px;">
    	    </td>
    	    <td>
    		<input type="text" name="fixedPrice[<?php echo $fp_row; ?>][sixth_side_price]" id="sixth_side_fpprice<?php echo $fp_row; ?>" value="<?php echo $fp['sixth_side_price']; ?>" style="width: 55px;">
    	    </td> 	
    	    <td>
    		<input type="text" name="fixedPrice[<?php echo $fp_row; ?>][seventh_side_price]" id="seventh_side_fpprice<?php echo $fp_row; ?>" value="<?php echo $fp['seventh_side_price']; ?>" style="width: 55px;">
    	    </td>
    	    <td>
    		<input type="text" name="fixedPrice[<?php echo $fp_row; ?>][eighth_side_price]" id="eighth_side_fpprice<?php echo $fp_row; ?>" value="<?php echo $fp['eighth_side_price']; ?>" style="width: 55px;">
    	    </td>
    	    <td><a onclick="$('#qa-row<?php echo $fp_row; ?>').remove();" style="cursor:pointer;">Delete</a></td>
    	</tr>
        </tbody>
	<?php
	$fp_row++;
    }
    ?>
    <tfoot>
	<tr>
	    <td colspan="9"></td>
	    <td class="left"><a onclick="addFixedPrice();" style="cursor:pointer;">Add Fixed Price</a></td>
	</tr>
    </tfoot>
</table>
<script type="text/javascript"><!--
    var fp_row = <?php echo $fp_row; ?>;
    function addFixedPrice() {
	html  = '<tbody id="qa-row' + fp_row + '">';
	html += '<tr>'; 		
	html += '<td><select name="fixedPrice[' + fp_row + '][quantity_range_id]">';
<?php foreach ($qranges as $qr) { ?>
    	html +=   '<option value="<?php echo $qr['qrange_id']; ?>"><?php echo $qr['quantity_range_from'] . ' - ' . $qr['quantity_range_to']; ?></option>';
<?php } ?>
	html += '</select></td>';
	html += '<td><input type="text" name="fixedPrice[' + fp_row + '][first_side_price]" id="first_side_fpprice' + fp_row + '" value="0.00" style="width: 55px;"/></td>';
	html += '<td><input type="text" name="fixedPrice[' + fp_row + '][second_side_price]" id="second_side_fpprice' + fp_row + '" value="0.00" style="width: 55px;"/></td>';
	html += '<td><input type="text" name="fixedPrice[' + fp_row + '][third_side_price]" id="third_side_fpprice' + fp_row + '" value="0.00" style="width: 55px;"/></td>';
	html += '<td><input type="text" name="fixedPrice[' + fp_row + '][fourth_side_price]" id="fourth_side_fpprice' + fp_row + '" value="0.00" style="width: 55px;"></td>';
	html += '<td><input type="text" name="fixedPrice[' + fp_row + '][fifth_side_price]" id="fifth_side_fpprice' + fp_row + '" value="0.00" style="width: 55px;"></td>';
	html += '<td><input type="text" name="fixedPrice[' + fp_row + '][sixth_side_price]" id="sixth_side_fpprice' + fp_row + '" value="0.00" style="width: 55px;"></td>';
	html += '<td><input type="text" name="fixedPrice[' + fp_row + '][seventh_side_price]" id="seventh_side_fpprice' + fp_row + '" value="0.00" style="width: 55px;"></td>';
	html += '<td><input type="text" name="fixedPrice[' + fp_row + '][eighth_side_price]" id="eighth_side_fpprice' + fp_row + '" value="0.00" style="width: 55px;"></td>';
	html += '<td><a onclick="$(\'#qa-row' + fp_row + '\').remove();" style="cursor:pointer;">Delete</a></td>';
	html += '</tr>';	
	html += '</tbody>';
	
	$('#fixedprices tfoot').before(html);
	$('input[id^="first_side_fpprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="second_side_fpprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="third_side_fpprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="fourth_side_fpprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="fifth_side_fpprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="sixth_side_fpprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="seventh_side_fpprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="eighth_side_fpprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	fp_row++;
	
    }
    //--></script> 
<script>
    $(document).ready(function() {
<?php
$row = 0;
foreach ($printingmethod_row['fixedPrice'] as $fp) {
    ?>
    	    var row = <?php echo $row; ?>;
    	    $( "#first_side_fpprice" + row).rules( "add", { number: true });
    	    $( "#second_side_fpprice" + row).rules( "add", { number: true });
    	    $( "#third_side_fpprice" + row).rules( "add", { number: true });
    	    $( "#fourth_side_fpprice" + row).rules( "add", { number: true });
    	    $( "#fifth_side_fpprice" + row).rules( "add", { number: true });
    	    $( "#sixth_side_fpprice" + row).rules( "add", { number: true });
    	    $( "#seventh_side_fpprice" + row).rules( "add", { number: true });
    	    $( "#eighth_side_fpprice" + row).rules( "add", { number: true });
    <?php $row++;
}
?>
    });
</script>