<?php
if (version_compare(phpversion(), '5.2.0', '<')===true) {
    echo  '<div style="font:12px/1.35em arial, helvetica, sans-serif;"><div style="margin:0 0 25px 0; border-bottom:1px solid #ccc;"><h3 style="margin:0; font-size:1.7em; font-weight:normal; text-transform:none; text-align:left; color:#2f2f2f;">Whoops, it looks like you have an invalid PHP version.</h3></div><p>Magento supports PHP 5.2.0 or newer. <a href="http://www.magentocommerce.com/install" target="">Find out</a> how to install</a> Magento using PHP-CGI as a work-around.</p></div>';
    exit;
}
 
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
 
$mageFilename = 'app/Mage.php';
 
if (!file_exists($mageFilename)) {
    echo $mageFilename." was not found";
    exit;
}
 
require_once $mageFilename;
 
Mage::app();
 
$executionPath = null;
 
/*
 * determine Magento Edition
 */
if (file_exists('LICENSE_EE.txt')) {
    $edition = 'EE';
}elseif (file_exists('LICENSE_PRO.html')) {
    $edition = 'PE';
} else {
    $edition = 'CE';    
}
 
if(($edition=='EE' && version_compare(Mage::getVersion(), '1.11.0.0.', '<')===true)
        || ($edition=='PE' && version_compare(Mage::getVersion(), '1.11.0.0.', '<')===true)
        || ($edition=='CE' && version_compare(Mage::getVersion(), '1.6.0.0.', '<')===true)
  ){
   $executionPath = 'old'; 
} else {
   $executionPath = 'new';  
}
 
$xpathEntity = 'global/models/sales_entity/entities//table';
 
if ($executionPath == 'old') {
    $xpathResource = 'global/models/sales_mysql4/entities//table';
} else {
    $xpathResource = 'global/models/sales_resource/entities//table';
}
 
$salesEntitiesConf = array_merge(
    Mage::getSingleton('core/config')->init()->getXpath($xpathEntity), 
    Mage::getSingleton('core/config')->init()->getXpath($xpathResource)
);
 
$resource = Mage::getSingleton('core/resource');
$connection = $resource->getConnection('core_write');
 
 
/*
 * If you want delete System/Order Statuses (Status and State) you
 * should comments below lines (46-51)
 */
$skipTables = array (
        $resource->getTableName('sales_order_status'),
        $resource->getTableName('sales_order_status_state'),
        $resource->getTableName('sales_order_status_label')
    );
$salesEntitiesConf = array_diff($salesEntitiesConf, $skipTables);
 
 
/*
 
Multiple RDBMS Support in Magento CE 1.6+ / EE 1.11+
    http://www.magentocommerce.com/images/uploads/RDBMS_Guide2.pdf
 
2.2. Adapters:
 
... The new Varien_DB_Adapter_Interface was added to sign a contract that all 
developed adapters must execute in order to get Magento working on an actual 
database. The interface describes the list of methods and constants that can be used by resource models...
 
Used below in the loop:
 
 * If $executionPath == 'old'
    * Varien_Db_Adapter_Pdo_Mysql::showTableStatus()
    * Varien_Db_Adapter_Pdo_Mysql::truncate()  
 * Else
    * Varien_Db_Adapter_Interface::isTableExists()
    * Varien_Db_Adapter_Interface::truncateTable()
 
*/
 
while ($table = current($salesEntitiesConf) ){
    $table = $resource->getTableName($table);
 
    if ($executionPath == 'old') {
        $isTableExists = $connection->showTableStatus($table);
    } else {
        $isTableExists = $connection->isTableExists($table);
    }
    if ($isTableExists) {
        try {
            if ($executionPath == 'old') {
                $connection->truncate($table);
            } else {
                $connection->truncateTable($table);
            }
 
            printf('Successfully truncated the <i style="color:green;">%s</i> table.<br />', $table);
        } catch(Exception $e) {
            printf('Error <i style="color:red;">%s</i> occurred truncating the <i style="color:red;">%s</i> table.<br />', $e->getMessage(), $table);
        }
    }
 
    next($salesEntitiesConf);
}
 
exit('All done...');

?>