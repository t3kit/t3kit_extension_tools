<?php
defined('TYPO3_MODE') or die();

//
$init = function ($_EXTKEY) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\DmitryDulepov\Realurl\Utility::class]['className'] = \T3kit\T3kitExtensionTools\Xclass\Realurl\Utility::class;
};

$init($_EXTKEY);
unset($init);