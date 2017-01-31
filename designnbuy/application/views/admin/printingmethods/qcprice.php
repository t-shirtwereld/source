<table id="qcprices" class="pc_reference table-shadow quantity-color" cellpadding="0" cellspacing="0">
    <thead>
	<tr>
	    <th>Quantity Count</th>
	    <th>No.Of.Color</th>
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
    <?php $qc_row = 0; ?>
    <?php foreach ($printingmethod_row['qcPrice'] as $qc) { ?>
        <tbody id="qc-row<?php echo $qc_row; ?>">
    	<tr>
    	    <td>
    		<select name="qcPrice[<?php echo $qc_row; ?>][quantity_range_id]">
			<?php foreach ($qranges as $qr) { ?>
			    <?php if ($qr['qrange_id'] === $qc['quantity_range_id']) { ?>
	    		    <option value="<?php echo $qr['qrange_id']; ?>" selected="selected"><?php echo $qr['quantity_range_from'] . ' - ' . $qr['quantity_range_to']; ?></option>
			    <?php } else { ?>
	    		    <option value="<?php echo $qr['qrange_id']; ?>"><?php echo $qr['quantity_range_from'] . ' - ' . $qr['quantity_range_to']; ?></option>
			    <?php } ?>
			<?php } ?>
    		</select>
    	    </td>
    	    <td>
    		<select name="qcPrice[<?php echo $qc_row; ?>][color_counter_id]">
			<?php foreach ($colorcounters as $cc) { ?>
			    <?php if ($cc['color_counter_id'] === $qc['color_counter_id']) { ?>
	    		    <option value="<?php echo $cc['color_counter_id']; ?>" selected="selected"><?php echo $cc['color_counter']; ?></option>
			    <?php } else { ?>
	    		    <option value="<?php echo $cc['color_counter_id']; ?>"><?php echo $cc['color_counter']; ?></option>
			    <?php } ?>
			<?php } ?>
    		</select>
    	    </td>
    	    <td>
    		<input type="text" name="qcPrice[<?php echo $qc_row; ?>][first_side_price]" id="first_side_qcprice<?php echo $qc_row; ?>" value="<?php echo $qc['first_side_price']; ?>" style="width: 55px;"> 
    	    </td>
    	    <td>
    		<input type="text" name="qcPrice[<?php echo $qc_row; ?>][second_side_price]" id="second_side_qcprice<?php echo $qc_row; ?>" value="<?php echo $qc['second_side_price']; ?>" style="width: 55px;">
    	    </td>
    	    <td>
    		<input type="text" name="qcPrice[<?php echo $qc_row; ?>][third_side_price]" id="third_side_qcprice<?php echo $qc_row; ?>" value="<?php echo $qc['third_side_price']; ?>" style="width: 55px;"> 
    	    </td>
    	    <td>
    		<input type="text" name="qcPrice[<?php echo $qc_row; ?>][fourth_side_price]" id="fourth_side_qcprice<?php echo $qc_row; ?>" value="<?php echo $qc['fourth_side_price']; ?>" style="width: 55px;">
    	    </td>
    	    <td>
    		<input type="text" name="qcPrice[<?php echo $qc_row; ?>][fifth_side_price]" id="fifth_side_qcprice<?php echo $qc_row; ?>" value="<?php echo $qc['fifth_side_price']; ?>" style="width: 55px;">
    	    </td>
    	    <td>
    		<input type="text" name="qcPrice[<?php echo $qc_row; ?>][sixth_side_price]" id="sixth_side_qcprice<?php echo $qc_row; ?>" value="<?php echo $qc['sixth_side_price']; ?>" style="width: 55px;">
    	    </td> 	
    	    <td>
    		<input type="text" name="qcPrice[<?php echo $qc_row; ?>][seventh_side_price]" id="seventh_side_qcprice<?php echo $qc_row; ?>" value="<?php echo $qc['seventh_side_price']; ?>" style="width: 55px;">
    	    </td>
    	    <td>
    		<input type="text" name="qcPrice[<?php echo $qc_row; ?>][eighth_side_price]" id="eighth_side_qcprice<?php echo $qc_row; ?>" value="<?php echo $qc['eighth_side_price']; ?>" style="width: 55px;">
    	    </td>
    	    <td><a onclick="$('#qc-row<?php echo $qc_row; ?>').remove();" style="cursor:pointer;">Delete</a></td>
    	</tr>
        </tbody>
	<?php
	$qc_row++;
    }
    ?>
    <tfoot>
	<tr>
	    <td colspan="10"></td>
	    <td class="left"><a onclick="addQCPrice();" style="cursor:pointer;">Add QC Price</a></td>
	</tr>
    </tfoot>
</table>
<script type="text/javascript"><!--
    var qc_row = <?php echo $qc_row; ?>;
    function addQCPrice() {
	html  = '<tbody id="qc-row' + qc_row + '">';
	html += '<tr>'; 		
	html += '<td><select name="qcPrice[' + qc_row + '][quantity_range_id]">';
<?php foreach ($qranges as $qr) { ?>
    	html +=   '<option value="<?php echo $qr['qrange_id']; ?>"><?php echo $qr['quantity_range_from'] . ' - ' . $qr['quantity_range_to']; ?></option>';
<?php } ?>
	html += '</select></td>';

	html += '<td>';                                 
	html += '<select name="qcPrice[' + qc_row + '][color_counter_id]">';
<?php foreach ($colorcounters as $cc) { ?>
    	html += '<option value="<?php echo $cc['color_counter_id']; ?>"><?php echo $cc['color_counter']; ?></option>';
<?php } ?>
	html += '</select></td>';

	html += '<td><input type="text" name="qcPrice[' + qc_row + '][first_side_price]" id="first_side_qcprice' + qc_row + '" value="0.00" style="width: 55px;"/></td>';
	html += '<td><input type="text" name="qcPrice[' + qc_row + '][second_side_price]" id="second_side_qcprice' + qc_row + '" value="0.00" style="width: 55px;"/></td>';
	html += '<td><input type="text" name="qcPrice[' + qc_row + '][third_side_price]" id="third_side_qcprice' + qc_row + '" value="0.00" style="width: 55px;"/></td>';
	html += '<td><input type="text" name="qcPrice[' + qc_row + '][fourth_side_price]" id="fourth_side_qcprice' + qc_row + '" value="0.00" style="width: 55px;"></td>';
	html += '<td><input type="text" name="qcPrice[' + qc_row + '][fifth_side_price]" id="fifth_side_qcprice' + qc_row + '" value="0.00" style="width: 55px;"></td>';
	html += '<td><input type="text" name="qcPrice[' + qc_row + '][sixth_side_price]" id="sixth_side_qcprice' + qc_row + '" value="0.00" style="width: 55px;"></td>';
	html += '<td><input type="text" name="qcPrice[' + qc_row + '][seventh_side_price]" id="seventh_side_qcprice' + qc_row + '" value="0.00" style="width: 55px;"></td>';
	html += '<td><input type="text" name="qcPrice[' + qc_row + '][eighth_side_price]" id="eighth_side_qcprice' + qc_row + '" value="0.00" style="width: 55px;"></td>';
	html += '<td><a onclick="$(\'#qc-row' + qc_row + '\').remove();" style="cursor:pointer;">Delete</a></td>';
	html += '</tr>';	
	html += '</tbody>';
	
	$('#qcprices tfoot').before(html);
	$('input[id^="first_side_qcprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="second_side_qcprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="third_side_qcprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="fourth_side_qcprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="fifth_side_qcprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="sixth_side_qcprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="seventh_side_qcprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="eighth_side_qcprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	qc_row++;
	
    }
    //--></script> 
<script>
    $(document).ready(function() {
<?php
$row = 0;
foreach ($printingmethod_row['qcPrice'] as $qc) {
    ?>
    	    var row = <?php echo $row; ?>;
    	    $( "#first_side_qcprice" + row).rules( "add", { number: true });
    	    $( "#second_side_qcprice" + row).rules( "add", { number: true });
    	    $( "#third_side_qcprice" + row).rules( "add", { number: true });
    	    $( "#fourth_side_qcprice" + row).rules( "add", { number: true });
    	    $( "#fifth_side_qcprice" + row).rules( "add", { number: true });
    	    $( "#sixth_side_qcprice" + row).rules( "add", { number: true });
    	    $( "#seventh_side_qcprice" + row).rules( "add", { number: true });
    	    $( "#eighth_side_qcprice" + row).rules( "add", { number: true });
    <?php $row++;
}
?>
    });
</script>