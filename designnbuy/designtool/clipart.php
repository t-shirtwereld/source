<?php
require '../app/Mage.php';	
Mage::app();	
ini_set('display_errors', 1);
echo $clipartPath = Mage::getBaseDir('media'). DS .'clipart'. DS. 'images'. DS;
echo "<br/>";
echo $clipartMovePath = Mage::getBaseDir('media'). DS .'clipart'. DS . 'import'. DS;
$collection = Mage::getModel('clipartmanagement/clipart')->getCollection();
foreach($collection as $clipart)		
{	
	// rename($clipartPath.$clipart->getClipartName(), $clipartMovePath.$clipart->getClipartName());
	//echo $clipart->getClipartName();
	if (copy($clipartPath.$clipart->getClipartImage(),$clipartMovePath.$clipart->getClipartImage())) {
	  unlink($clipartPath.$clipart->getClipartImage());
	}
}
?>