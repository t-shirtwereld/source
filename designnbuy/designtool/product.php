<script type="text/javascript" src="../js/html5/jquery.js"></script>
<?php  
ini_set('display_errors', 1);
require '../app/Mage.php';
Mage::app();

$categories = Mage::getModel('catalog/category')
	->getCollection()
	->addAttributeToSelect('name')
	->addAttributeToSort('id', 'asc')
	->addFieldToFilter('is_active', array('eq'=>'1'));

$first = array();
$children = array();
echo "<pre>";
foreach ($categories->getItems() as $cat) {
	// print_r($cat->getData());
	if ($cat->getLevel() == 2) {
		$first[$cat->getId()] = $cat->getName();
	} else if ($cat->getParentId()) {
		
		$children[$cat->getParentId()][] = $cat->getName();
	}
}

// 
// print_r($first);
// print_r('children');


$tree = array('first' => $first, 'children' => $children);
print_r($tree); 
?> 
<script type="text/javascript">
	var $ = jQuery.noConflict();
    var children = '<?php echo json_encode($tree['children']) ?>';
	children = $.parseJSON(children);
	console.log(children);
    function showCat(obj, level) {
        var catId = obj.value;
        level += 1;
        if ($('cat_container_' + level)) {
            $('cat_container_' + level).remove();
        }
        if (children[catId]) {
            var options = children[catId];
			
            var html = '<select id="cat_' + catId + '" onchange="showCat(this, ' + level + ')">';
            for (var i = 0; i < options.length; i++) {
                html += '<option value="' + options[i].entity_id + '">' + options[i].name + '</option>';
            }
            html += '</select>';
            html = '<div id="cat_container_' + level + '">' + html + '</div>';

            $('#sub_cat').html(html);
        }
    }
</script>
<select id="first_cat" onchange="showCat(this, 2)">
    <?php foreach ($tree['first'] as $cat): ?>
        <option value="<?php echo $cat->getId() ?>"><?php echo $cat->getName() ?></option>
    <?php endforeach ?>
</select>
<div id="sub_cat"></div>