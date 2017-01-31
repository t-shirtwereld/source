<div class="pc_contten">
    <div class="pc_top">
        <h1>Printable Colors</h1>
	<div class="pc_rgt" style="margin-left: 10px;"><a href="<?php echo BASE_ADMIN_URL . 'printablecolors/addprintablecolor'; ?>">Add Printable Color</a></div>
	<div class="pc_rgt"><a href="<?php echo BASE_ADMIN_URL . 'printablecolors/importcsv'; ?>">Import Printable Colors</a></div>
    </div>

    <div class="pc_search">
	<form name="search" action="<?php echo BASE_ADMIN_URL . 'printablecolors/index/'; ?>" method="get" style="float:right;">
	    <div class="top_dropdown">
		<div class="input-field" >
		    <select name="category_id" onchange="this.form.submit();">
			<option value="">Select Color Category</option>
			<?php foreach ($categories as $category) { ?>
    			<option value="<?php echo $category['printablecolor_category_id']; ?>" <?php if ($category['printablecolor_category_id'] == $category_id) echo "selected=selected"; ?>><?php echo $category['category_name']; ?></option>
			<?php } ?>
		    </select>
		</div>
	    </div>
	    <div class="switcher">
		<input id="global_search" name="keyword" class="input-text" type="text" placeholder="Search" value="<?php echo $keyword; ?>" />
		<button id="search" class="btn" name="send" title="Search" type="submit"><img src="<?php echo base_url('assets/images/search-icn.svg'); ?>" alt=""></button>
		<a href="<?php echo BASE_ADMIN_URL . 'printablecolors'; ?>" style="float: right; margin: 0px 8px; line-height: 36px;"><img src="<?php echo base_url('assets/images/recet.png'); ?>" alt=""></a>
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
		<th style="width:26%;">Name</th>
		<th style="width:20%;">Category</th>          
	<!--	<th style="width:12%;">C &nbsp; M &nbsp; Y &nbsp; K</th> -->
		<th style="width:18%;">RGB Color Code</th>
		<th style="width:13%;">States</th>
		<th style="width:13%;">Actions</th>
	    </tr>
	</thead>
	<tbody>
	    <?php
	    if (!empty($printablecolors)) {
		foreach ($printablecolors as $p) {
		    ?>
		    <tr id="<?php echo $p['printablecolor_id']; ?>">
			<td><?php echo $p['printablecolor_id']; ?></td>		    
			<td><?php echo $p['name']; ?></td> 
			<td><?php echo $p['category_name']; ?></td>
		<!--	<td><?php //echo $p['c']; ?>&nbsp;<?php //echo $p['m']; ?>&nbsp;<?php //echo $p['y']; ?>&nbsp;<?php //echo $p['k']; ?></td>  -->
			<td><span class="color-box" style="background:<?php echo $p['color_code']; ?>;">&nbsp;</span>&nbsp;&nbsp;<?php echo $p['color_code']; ?></td>  
			<td><?php echo $p['status'] == 1 ? 'Active' : 'Inactive'; ?></td>
			<td><a href="<?php echo BASE_ADMIN_URL . 'printablecolors/editprintablecolor/' . $p['printablecolor_id']; ?>">Edit</a>&nbsp;&nbsp;&nbsp;<a class="delete" href="<?php echo BASE_ADMIN_URL . 'printablecolors/deleteprintablecolor/' . $p['printablecolor_id']; ?>">Delete</a></td>
		    </tr>
		    <?php
		}
	    } else {
		?>
    	    <tr><td colspan="7" style="text-align:center;">No Records Found!</td></tr>
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
		    <option value="25" <?php
		if ($limit == '25') {
		    echo "selected='selected'";
		}
		?>>25</option>
		    <option value="50" <?php
			    if ($limit == '50') {
				echo "selected='selected'";
			    }
		?>>50</option>
		    <option value="100" <?php
			    if ($limit == '100') {
				echo "selected='selected'";
			    }
		?>>100</option>
		    <option value="200" <?php
			    if ($limit == '200') {
				echo "selected='selected'";
			    }
		?>>200</option>
		    <option value="<?php echo $total_rows; ?>" <?php
			    if ($limit == $total_rows) {
				echo "selected='selected'";
			    }
		?>>All</option>		    
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
	    var url = "<?php echo BASE_ADMIN_URL . 'printablecolors/index?category_id='.$category_id.'&keyword='.$keyword.'&limit='; ?>"+limit_val;

	    if (url) { // require a URL
		window.location = url; // redirect
	    }
	    return false;
	});
	$('td').each(function(){
	    $(this).css('width', $(this).width() +'px');
	});
    
<?php if (empty($keyword) && empty($category_id) && empty($_GET['per_page'])) { ?>
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
    			url: "<?php echo BASE_ADMIN_URL . 'printablecolors/updateSortableRow'; ?>",
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

