<?php
defined('TYPO3_MODE') or die();

$init = function ($_EXTKEY) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\DmitryDulepov\Realurl\Utility::class]['className'] = \T3kit\T3kitExtensionTools\Xclass\Realurl\Utility::class;


    // Register data handler hook
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$_EXTKEY] = \T3kit\T3kitExtensionTools\Hook\DataHandlerHook::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][$_EXTKEY] = \T3kit\T3kitExtensionTools\Hook\DataHandlerHook::class;

    /**
     *  Load predefined configuration of fixedPostVars
     */
    $extConf = \T3kit\T3kitExtensionTools\Utility\HelperUtility::getExtConf();

    if (is_array($extConf) && $extConf['fixedPostVarsConfigurationfFile']) {
        $filePath = PATH_site . trim($extConf['fixedPostVarsConfigurationfFile']);
    }

    if (isset($filePath) && file_exists($filePath)) {
        require_once($filePath);
    } else {
        /** @noinspection PhpIncludeInspection */
        @include_once(PATH_site . 'typo3conf/ext/t3kit_extension_tools/Configuration/Realurl/predefined_fixedPostVars_conf.php');
    }
};

$init($_EXTKEY);
unset($init);