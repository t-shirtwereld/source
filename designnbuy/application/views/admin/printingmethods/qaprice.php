<table id="qaprices" class="pc_reference table-shadow quantity-color" cellpadding="0" cellspacing="0">
    <thead>
	<tr>
	    <th>Quantity Count</th>
	    <th>Square Area Size</th>
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
    <?php $qa_row = 0; ?>
    <?php foreach ($printingmethod_row['qaPrice'] as $qa) { ?>
        <tbody id="qa-row<?php echo $qa_row; ?>">
    	<tr>
    	    <td>
    		<select name="qaPrice[<?php echo $qa_row; ?>][quantity_range_id]">
			<?php foreach ($qranges as $qr) { ?>
			    <?php if ($qr['qrange_id'] === $qa['quantity_range_id']) { ?>
	    		    <option value="<?php echo $qr['qrange_id']; ?>" selected="selected"><?php echo $qr['quantity_range_from'] . ' - ' . $qr['quantity_range_to']; ?></option>
			    <?php } else { ?>
	    		    <option value="<?php echo $qr['qrange_id']; ?>"><?php echo $qr['quantity_range_from'] . ' - ' . $qr['quantity_range_to']; ?></option>
			    <?php } ?>
			<?php } ?>
    		</select>
    	    </td>
    	    <td>
    		<select name="qaPrice[<?php echo $qa_row; ?>][sqarea_id]">
			<?php foreach ($sqareas as $sq) { ?>
			    <?php if ($sq['sqarea_id'] === $qa['sqarea_id']) { ?>
	    		    <option value="<?php echo $sq['sqarea_id']; ?>" selected="selected"><?php echo $sq['square_area']; ?></option>
			    <?php } else { ?>
	    		    <option value="<?php echo $sq['sqarea_id']; ?>"><?php echo $sq['square_area']; ?></option>
			    <?php } ?>
			<?php } ?>
    		</select>
    	    </td>
    	    <td>
    		<input type="text" name="qaPrice[<?php echo $qa_row; ?>][first_side_price]" id="first_side_qaprice<?php echo $qa_row; ?>" value="<?php echo $qa['first_side_price']; ?>" style="width: 55px;"> 
    	    </td>
    	    <td>
    		<input type="text" name="qaPrice[<?php echo $qa_row; ?>][second_side_price]" id="second_side_qaprice<?php echo $qa_row; ?>" value="<?php echo $qa['second_side_price']; ?>" style="width: 55px;">
    	    </td>
    	    <td>
    		<input type="text" name="qaPrice[<?php echo $qa_row; ?>][third_side_price]" id="third_side_qaprice<?php echo $qa_row; ?>" value="<?php echo $qa['third_side_price']; ?>" style="width: 55px;"> 
    	    </td>
    	    <td>
    		<input type="text" name="qaPrice[<?php echo $qa_row; ?>][fourth_side_price]" id="fourth_side_qaprice<?php echo $qa_row; ?>" value="<?php echo $qa['fourth_side_price']; ?>" style="width: 55px;">
    	    </td>
    	    <td>
    		<input type="text" name="qaPrice[<?php echo $qa_row; ?>][fifth_side_price]" id="fifth_side_qaprice<?php echo $qa_row; ?>" value="<?php echo $qa['fifth_side_price']; ?>" style="width: 55px;">
    	    </td>
    	    <td>
    		<input type="text" name="qaPrice[<?php echo $qa_row; ?>][sixth_side_price]" id="sixth_side_qaprice<?php echo $qa_row; ?>" value="<?php echo $qa['sixth_side_price']; ?>" style="width: 55px;">
    	    </td> 	
    	    <td>
    		<input type="text" name="qaPrice[<?php echo $qa_row; ?>][seventh_side_price]" id="seventh_side_qaprice<?php echo $qa_row; ?>" value="<?php echo $qa['seventh_side_price']; ?>" style="width: 55px;">
    	    </td>
    	    <td>
    		<input type="text" name="qaPrice[<?php echo $qa_row; ?>][eighth_side_price]" id="eighth_side_qaprice<?php echo $qa_row; ?>" value="<?php echo $qa['eighth_side_price']; ?>" style="width: 55px;">
    	    </td>
    	    <td><a onclick="$('#qa-row<?php echo $qa_row; ?>').remove();" style="cursor:pointer;">Delete</a></td>
    	</tr>
        </tbody>
	<?php
	$qa_row++;
    }
    ?>
    <tfoot>
	<tr>
	    <td colspan="10"></td>
	    <td class="left"><a onclick="addQAPrice();" style="cursor:pointer;">Add QA Price</a></td>
	</tr>
    </tfoot>
</table>
<script type="text/javascript"><!--
    var qa_row = <?php echo $qa_row; ?>;
    function addQAPrice() {
	html  = '<tbody id="qa-row' + qa_row + '">';
	html += '<tr>'; 		
	html += '<td><select name="qaPrice[' + qa_row + '][quantity_range_id]">';
<?php foreach ($qranges as $qr) { ?>
    	html +=   '<option value="<?php echo $qr['qrange_id']; ?>"><?php echo $qr['quantity_range_from'] . ' - ' . $qr['quantity_range_to']; ?></option>';
<?php } ?>
	html += '</select></td>';

	html += '<td>';                                 
	html += '<select name="qaPrice[' + qa_row + '][sqarea_id]">';
<?php foreach ($sqareas as $sq) { ?>
    	html += '<option value="<?php echo $sq['sqarea_id']; ?>"><?php echo $sq['square_area']; ?></option>';
<?php } ?>
	html += '</select></td>';

	html += '<td><input type="text" name="qaPrice[' + qa_row + '][first_side_price]" id="first_side_qaprice' + qa_row + '" value="0.00" style="width: 55px;"/></td>';
	html += '<td><input type="text" name="qaPrice[' + qa_row + '][second_side_price]" id="second_side_qaprice' + qa_row + '" value="0.00" style="width: 55px;"/></td>';
	html += '<td><input type="text" name="qaPrice[' + qa_row + '][third_side_price]" id="third_side_qaprice' + qa_row + '" value="0.00" style="width: 55px;"/></td>';
	html += '<td><input type="text" name="qaPrice[' + qa_row + '][fourth_side_price]" id="fourth_side_qaprice' + qa_row + '" value="0.00" style="width: 55px;"></td>';
	html += '<td><input type="text" name="qaPrice[' + qa_row + '][fifth_side_price]" id="fifth_side_qaprice' + qa_row + '" value="0.00" style="width: 55px;"></td>';
	html += '<td><input type="text" name="qaPrice[' + qa_row + '][sixth_side_price]" id="sixth_side_qaprice' + qa_row + '" value="0.00" style="width: 55px;"></td>';
	html += '<td><input type="text" name="qaPrice[' + qa_row + '][seventh_side_price]" id="seventh_side_qaprice' + qa_row + '" value="0.00" style="width: 55px;"></td>';
	html += '<td><input type="text" name="qaPrice[' + qa_row + '][eighth_side_price]" id="eighth_side_qaprice' + qa_row + '" value="0.00" style="width: 55px;"></td>';
	html += '<td><a onclick="$(\'#qa-row' + qa_row + '\').remove();" style="cursor:pointer;">Delete</a></td>';
	html += '</tr>';	
	html += '</tbody>';
	
	$('#qaprices tfoot').before(html);
	$('input[id^="first_side_qaprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="second_side_qaprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="third_side_qaprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="fourth_side_qaprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="fifth_side_qaprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="sixth_side_qaprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="seventh_side_qaprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	$('input[id^="eighth_side_qaprice"]').each(function () {
	    $(this).rules('add', {
		number: true
	    });
	});
	qa_row++;
	
    }
    //--></script> 
<script>
    $(document).ready(function() {
<?php
$row = 0;
foreach ($printingmethod_row['qaPrice'] as $qa) {
    ?>
    	    var row = <?php echo $row; ?>;
    	    $( "#first_side_qaprice" + row).rules( "add", { number: true });
    	    $( "#second_side_qaprice" + row).rules( "add", { number: true });
    	    $( "#third_side_qaprice" + row).rules( "add", { number: true });
    	    $( "#fourth_side_qaprice" + row).rules( "add", { number: true });
    	    $( "#fifth_side_qaprice" + row).rules( "add", { number: true });
    	    $( "#sixth_side_qaprice" + row).rules( "add", { number: true });
    	    $( "#seventh_side_qaprice" + row).rules( "add", { number: true });
    	    $( "#eighth_side_qaprice" + row).rules( "add", { number: true });
    <?php $row++;
}
?>
    });
</script>