<?php
require '../app/Mage.php';	
Mage::app();	
ini_set('display_errors', 1);
$backgroundImageCollection = Mage::getModel('w2phtml5background/backgroundimages')->getCollection();
$backgroundImagePath = Mage::getBaseDir('media'). DS .'w2phtml5background'. DS. 'backgrounds'. DS . 'resize'. DS;
$backgroundImageCopyPath = Mage::getBaseDir('media'). DS .'w2phtml5background'. DS . 'temp'. DS . 'backgrounds'. DS . 'resize'. DS;

$w2phtml5backgrounds = Mage::getModel('w2phtml5background/w2phtml5background')->getCollection();
$backgroundData  = array();
$i = 0;
$flag = false;
echo "<pre>";
foreach($w2phtml5backgrounds as $w2phtml5background){
	$j = 0;
	/* if($flag == false){
		$backgroundData[$i]= $w2phtml5background->getEntityId();
	} */
	/* echo "<br />";
	print_r($w2phtml5background->getEntityId());
	echo "<br />";
	echo $w2phtml5background->getName();
	echo "<br />"; */
	//Table: w2phtml5background_w2phtml5background_background
	$backgroundTemplates = Mage::getModel('w2phtml5background/background')->getCollection();
	$backgroundTemplates->AddFieldToFilter('background_id',$w2phtml5background->getEntityId());
	
	foreach($backgroundTemplates as $backgroundTemplate){	
		
		
		// print_r($backgroundTemplate->getData());
		//Table: w2phtml5background_w2phtml5background_background_images
		$backgroundImages = Mage::getModel('w2phtml5background/backgroundimages')->getCollection();
		$backgroundImages->AddFieldToFilter('image_id',$backgroundTemplate->getImageId());
		
		foreach($backgroundImages as $backgroundImage){
			$backgroundData[$i][$j] = array($w2phtml5background->getEntityId(), $w2phtml5background->getName(), $backgroundImage->getWidth(), $backgroundImage->getHeight(), $backgroundImage->getImage());
			/* print_r($backgroundImage->getImage());
			print_r($backgroundImage->getWidth());
			print_r($backgroundImage->getHeight());  */
			// $backgroundImage->delete();						
		}
		$j++;
	} 
	$i++;
}
print_r($backgroundData);
$fp = fopen(Mage::getBaseDir('media'). DS .'w2phtml5background'. DS. 'background1.csv', 'w');
foreach ($backgroundData as $background) {
	print_r($background);
	foreach ($background as $back) {
	  fputcsv($fp, $back);
	}
}
fclose($fp);
/* $arrayFromCSV =  array_map('str_getcsv', file('BackgroundSize.csv'));
echo '<pre>';
print_r($arrayFromCSV);
echo '</pre>';  */



/* 
Mage::getBaseDir('media'). DS .'w2phtml5background'. DS. 'background.csv';
$fp = fopen(Mage::getBaseDir('media'). DS .'w2phtml5background'. DS. 'background.csv', 'w');
$i = 0;
foreach ($backgroundImageCollection as $backgroundImage) {	
	$j = 0;	
	foreach($backgroundImage->getData() as $background){
		$backgroundData[$i][$j] = $background;		
		$j++;
	}	
	$i++;
}
// print_r($list);

foreach ($backgroundData as $background) {
	// print_r($background);
	  fputcsv($fp, $background);
}
 fclose($fp);

$k = 0;
foreach($backgroundImageCollection as $backgroundImage)		
{
	if(!file_exists($backgroundImagePath.$backgroundImage->getImage())){
		
		print_r($backgroundImage->getData());
		
	}
	
}  */

?>