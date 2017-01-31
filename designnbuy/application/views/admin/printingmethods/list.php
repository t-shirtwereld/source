<div class="pc_contten">
    <div class="pc_top">
        <h1>Printing Methods</h1>
	<div class="pc_rgt"><a href="<?php echo BASE_ADMIN_URL . 'printingmethods/addprintingmethod'; ?>">Add Printing Method</a></div>
    </div>

    <div class="pc_search">
	<form name="search" action="<?php echo BASE_ADMIN_URL . 'printingmethods/index/'; ?>" method="get" style="float:right;">
	    <div class="top_dropdown">
		<div class="input-field">
		    <select id="pricing_logic" name="pricing_logic" onchange="this.form.submit();">
			<option value="">Select Pricing Logic</option>
			<option value="1" <?php
if ($pricing_logic == '1') {
    echo "selected='selected'";
}
?>>Fixed</option>
			<option value="2" <?php
				if ($pricing_logic == '2') {
				    echo "selected='selected'";
				}
?>>No. of Colors</option>
			<option value="3" <?php
				if ($pricing_logic == '3') {
				    echo "selected='selected'";
				}
?>>Artwork Sizes</option>
		    </select>
		</div>
		<div class="input-field">
		    <select id="printable_color_type" name="printable_color_type" onchange="this.form.submit();">
			<option value="">Select Printable Color Picker Type</option>
			<option value="1" <?php
				if ($printable_color_type == '1') {
				    echo "selected='selected'";
				}
?>>Full</option>
			<option value="2" <?php
				if ($printable_color_type == '2') {
				    echo "selected='selected'";
				}
?>>Custom</option>
		    </select>
		</div>
	    </div>
	    <div class="switcher">
		<input id="global_search" name="keyword" class="input-text" type="text" placeholder="Search" value="<?php echo $keyword; ?>" />
		<button id="search" class="btn" name="send" title="Search" type="submit">
		    <img src="<?php echo base_url('assets/images/search-icn.svg'); ?>" alt="">		    
		</button>
		<a href="<?php echo BASE_ADMIN_URL . 'printingmethods'; ?>" style="float: right; margin: 0px 8px; line-height: 36px;"><img src="<?php echo base_url('assets/images/recet.png'); ?>" alt=""></a>
	    </div>	    
	</form>	
    </div>

    <?php if ($this->session->flashdata('msg')) { ?>
        <div id="messages_product_view">
    	<ul class="messages">
    	    <li class="success-msg">
		    <?php echo $this->session->flashdata('msg'); ?>
    	    </li>
    	</ul>
        </div>
    <?php } ?>

    <table id="sortable" class="pc_reference table-shadow" cellpadding="0" cellspacing="0">
	<thead>
	    <tr>
		<th style="width:10%;">Id</th>
		<th style="width:40%;">Name</th>
		<th style="width:14%;">Printing Logic</th>
		<th style="width:14%;">Printable Color</th>
		<th style="width:12%;">Status</th>
		<th style="width:10%;">Actions</th>
	    </tr>
	</thead>
	<tbody>
	    <?php
	    if (!empty($printingmethods)) {
		foreach ($printingmethods as $printingmethod) {
		    ?>
		    <tr id="<?php echo $printingmethod['printing_method_id']; ?>">
			<td><?php echo $printingmethod['printing_method_id']; ?></td>
			<td><?php echo $printingmethod['name']; ?></td>
			<td>
			    <?php
			    if ($printingmethod['pricing_logic'] == '1') {
				echo "Fixed";
			    } else if ($printingmethod['pricing_logic'] == '2') {
				echo "No. of colors";
			    } else {
				echo "Artwork Size";
			    }
			    ?>
			</td>
			<td>
			    <?php
			    if ($printingmethod['printable_color_type'] == '1') {
				echo "Full";
			    } else {
				echo "Custom";
			    }
			    ?>
			</td>
			<td><?php echo $printingmethod['status'] == 1 ? 'Active' : 'Inactive'; ?></td>
			<td><a href="<?php echo BASE_ADMIN_URL . 'printingmethods/editprintingmethod/' . $printingmethod['printing_method_id']; ?>">Edit</a>&nbsp;&nbsp;&nbsp;<a class="delete" href="<?php echo BASE_ADMIN_URL . 'printingmethods/deleteprintingmethod/' . $printingmethod['printing_method_id']; ?>">Delete</a></td>
		    </tr>
		    <?php
		}
	    } else {
		?>
    	    <tr><td colspan="6" style="text-align:center;">No Records Found!</td></tr>
	    <?php } ?>
	</tbody>
	<tfoot></tfoot>
    </table>
    <div class="pc_box">
	<div class="pc_pagination">
	    <ul>
		<?php
		foreach ($links as $link) {
		    echo "<li>" . $link . "</li>";
		}
		?>
	    </ul>
	</div>
	<div class="top_dropdown">
	    <div class="input-field">View
		<select id="limit" name="limit">
		    <option value="25" <?php if ($limit == '25') {
		    echo "selected='selected'";
		} ?>>25</option>
		    <option value="50" <?php if ($limit == '50') {
		    echo "selected='selected'";
		} ?>>50</option>
		    <option value="100" <?php if ($limit == '100') {
		    echo "selected='selected'";
		} ?>>100</option>
		    <option value="200" <?php if ($limit == '200') {
		    echo "selected='selected'";
		} ?>>200</option>
		    <option value="<?php echo $total_rows; ?>" <?php if ($limit == $total_rows) {
		    echo "selected='selected'";
		} ?>>All</option>		    
		</select>		
		Per Page</div>
	</div>
    </div>
</div>
<script>
    $( document ).ready(function() {
	
	$('#messages_product_view').fadeIn().delay(10000).fadeOut();
	// bind change event to select
	$('#limit').bind('change', function () {
	    var limit_val = $(this).find(":selected").val();
	    var url = "<?php echo BASE_ADMIN_URL . 'printingmethods/index?pricing_logic='.$pricing_logic.'&printable_color_type='.$printable_color_type.'&keyword='.$keyword.'&limit='; ?>"+limit_val;

	    if (url) { // require a URL
		window.location = url; // redirect
	    }
	    return false;
	});
	$('td').each(function(){
	    $(this).css('width', $(this).width() +'px');
	});
    
<?php if (empty($keyword) && empty($pricing_logic) && empty($printable_color_type) && empty($_GET['per_page'])) { ?>
    	   $(function() {
    	       $( "#sortable tbody" ).sortable({
    		   placeholder: "ui-state-highlight",
    		   update: function(event, ui) {
    		       var stringDiv = "";
    		       $("#sortable tbody").children().each(function(i) {
    			   var li = $(this);
    			   i = i + 1;
    			   stringDiv += " "+li.attr("id") + '=' + i + '&';
    		       });
    		       $.ajax({
    			   type: "POST",
    			   async:true,
    			   url: "<?php echo BASE_ADMIN_URL . 'printingmethods/updateSortableRow'; ?>",
    			   data: stringDiv,
    			   error: function(){
    			       alert("theres an error with AJAX");
    			   }  
    		       });
    		   }
    	       });
    	       $( "#sortable tbody" ).disableSelection();
    	   });
<?php } ?>
	    });
</script>