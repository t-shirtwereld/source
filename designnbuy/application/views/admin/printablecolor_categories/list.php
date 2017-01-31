<div class="pc_contten">
    <div class="pc_top">
        <h1>Color Categories</h1>
	<div class="pc_rgt" style="margin-left: 10px;"><a href="<?php echo BASE_ADMIN_URL . 'printablecolor_categories/addcategory'; ?>">Add Color Category</a></div>
	<div class="pc_rgt"><a href="<?php echo BASE_ADMIN_URL . 'printablecolor_categories/importcsv'; ?>">Import Color Categories</a></div>
    </div>

    <div class="pc_search">
	<div class="switcher">
	    <form name="search" action="<?php echo BASE_ADMIN_URL . 'printablecolor_categories/index/'; ?>" method="get">
		<input id="global_search" name="keyword" class="input-text" type="text" placeholder="Search" value="<?php echo $keyword; ?>" />
		<button id="search" class="btn" name="send" title="Search" type="submit"><img src="<?php echo base_url('assets/images/search-icn.svg'); ?>" alt=""></button>
		<a href="<?php echo BASE_ADMIN_URL . 'printablecolor_categories'; ?>" style="float: right; margin: 0px 8px; line-height: 36px;"><img src="<?php echo base_url('assets/images/recet.png'); ?>" alt=""></a>
	    </form>
	</div>
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
		<th style="width:70%;">Name</th>
		<th style="width:10%;">Status</th>
		<th style="width:10%;">Actions</th>
	    </tr>
	</thead>
	<tbody>
	    <?php
	    if (!empty($categories)) {
		foreach ($categories as $cat) {
		    ?>
		    <tr id="<?php echo $cat['printablecolor_category_id']; ?>">
			<td><?php echo $cat['printablecolor_category_id']; ?></td>
			<td><?php echo $cat['category_name']; ?></td> 
			<td><?php echo $cat['status'] == 1 ? 'Active' : 'Inactive'; ?></td>
			<td><a href="<?php echo BASE_ADMIN_URL . 'printablecolor_categories/editcategory/' . $cat['printablecolor_category_id']; ?>">Edit</a>&nbsp;&nbsp;&nbsp;<a class="delete" href="<?php echo BASE_ADMIN_URL . 'printablecolor_categories/deletecategory/' . $cat['printablecolor_category_id']; ?>">Delete</a></td>
		    </tr>
		    <?php
		}
	    } else {
		?>
    	    <tr><td colspan="4" style="text-align:center;">No Records Found!</td></tr>
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
	    var url = "<?php echo BASE_ADMIN_URL . 'printablecolor_categories/index?keyword='.$keyword.'&limit='; ?>"+limit_val;

	    if (url) { // require a URL
		window.location = url; // redirect
	    }
	    return false;
	});
	$('td').each(function(){
	    $(this).css('width', $(this).width() +'px');
	});
<?php if (empty($keyword) && empty($_GET['per_page'])) { ?>
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
    				url: "<?php echo BASE_ADMIN_URL . 'printablecolor_categories/updateSortableRow'; ?>",
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

