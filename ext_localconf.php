<?php
defined('TYPO3_MODE') or die();

$init = function ($_EXTKEY) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\DmitryDulepov\Realurl\Utility::class]['className'] = \T3kit\T3kitExtensionTools\Xclass\Realurl\Utility::class;

	if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('news') &&
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('seo_basics')) {
		$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['seo_basics']['sitemap']['additionalUrlsHook']['news']
			= \T3kit\T3kitExtensionTools\Hooks\NewsSiteMapXML::class . '->main';
	}
};

$init($_EXTKEY);
unset($init);
