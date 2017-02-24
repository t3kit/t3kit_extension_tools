<?php

if (!defined('TYPO3_MODE'))
	die('Access denied.');

if (TYPO3_MODE === 'BE') {
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'T3kit.' . $_EXTKEY, 'web', // Main area
		'mod1', // Name of the module
		'', // Position of the module
		array(// Allowed controller action combinations
			'Customizer' => 'customizeTheme,saveCustomizedTheme',
		), array(// Additional configuration
			'access' => 'user,group',
			'iconIdentifier' => 'module-themes',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xlf:customizer',
		)
	);
}
