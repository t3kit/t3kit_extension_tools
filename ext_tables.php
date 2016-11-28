<?php
defined('TYPO3_MODE') or die();

//
$init = function ($_EXTKEY) {
    // find and replace DrawItem hook
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('gridelements')) {
        // remove typo3 drag and drop lib to avoid conflict with gridelements
        if (TYPO3_MODE === 'BE') {
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][$_EXTKEY] = \T3kit\T3kitExtensionTools\Hooks\PageRenderPreProcess::class . '->preRender';
        }
    }


};

$init($_EXTKEY);
unset($init);