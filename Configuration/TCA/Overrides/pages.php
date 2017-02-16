<?php

$init = function () {
    $ll = 'LLL:EXT:t3kit_extension_tools/Resources/Private/Language/locallang_db.xlf:';

    $tempColumns = [
        'tx_t3kitextensiontools_fixed_post_var_conf' => [
            'label' => $ll .'pages.tx_t3kitextensiontools_fixed_post_var_conf',
            'displayCond' => 'FIELD:tx_realurl_exclude:!=:1',
            'exclude' => 1,
            'config' => [
                'type' => 'select',
                'max' => 1,
                'renderType' => 'selectSingle',
                'items' => [
                    [$ll . 'none', '0'],
                    [$ll . 'new_conf', '--div--']
                ],
                'itemsProcFunc' => \T3kit\T3kitExtensionTools\Utility\HelperUtility::class . '->getTcaFixedPostVarItems'
            ]
        ]
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns);

    $GLOBALS['TCA']['pages']['palettes']['tx_realurl']['showitem'] .= ',--linebreak--,tx_t3kitextensiontools_fixed_post_var_conf';
};

$init();
unset($init);