<?php

$init = function () {
    if (!is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3kit_extension_tools'])) {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3kit_extension_tools'] = [];
    }

    $configuration = &$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3kit_extension_tools'];

    // predefine configuration for news item single view
    $configuration['fixedPostVars']['news_single_view'] = [
        'title' => 'News single view',
        'configuration' => [
            [
                'GETvar' => 'tx_news_pi1[action]',
                'noMatch' => 'bypass'
            ],
            [
                'GETvar' => 'tx_news_pi1[controller]',
                'noMatch' => 'bypass'
            ],
            [
                'GETvar' => 'tx_news_pi1[news]',
                'lookUpTable' => [
                    'table' => 'tx_news_domain_model_news',
                    'id_field' => 'uid',
                    'alias_field' => 'IF(path_segment!="",path_segment,title)',
                    'addWhereClause' => ' AND NOT deleted',
                    'useUniqueCache' => 1,
                    'useUniqueCache_conf' => [
                        'strtolower' => 1,
                        'spaceCharacter' => '-'
                    ],
                    'languageGetVar' => 'L',
                    'languageExceptionUids' => '',
                    'languageField' => 'sys_language_uid',
                    'transOrigPointerField' => 'l10n_parent',
                    'autoUpdate' => 1,
                    'expireDays' => 180
                ]
            ]
        ]
    ];

    // predefine configuration for news category menu
    $configuration['fixedPostVars']['categories_item'] = [
        'title' => 'Article category',
        'configuration' => [
            [
                'GETvar' => 'tx_news_pi1[overwriteDemand][categories]',
                'lookUpTable' => [
                    'table' => 'sys_category',
                    'id_field' => 'uid',
                    'alias_field' => 'title',
                    'addWhereClause' => ' AND NOT deleted',
                    'useUniqueCache' => 1,
                    'useUniqueCache_conf' => [
                        'strtolower' => 1,
                        'spaceCharacter' => '-'
                    ]
                ]
            ]
        ]
    ];

    // predefine configuration for news tags menu
    $configuration['fixedPostVars']['tags_item'] = [
        'title' => 'Article tag',
        'configuration' => [
            [
                'GETvar' => 'tx_news_pi1[overwriteDemand][tags]',
                'lookUpTable' => [
                    'table' => 'tx_news_domain_model_tag',
                    'id_field' => 'uid',
                    'alias_field' => 'title',
                    'addWhereClause' => ' AND NOT deleted',
                    'useUniqueCache' => 1,
                    'useUniqueCache_conf' => [
                        'strtolower' => 1,
                        'spaceCharacter' => '-'
                    ]
                ]
            ]
        ]
    ];

    // predefine configuration for news date menu
    $configuration['fixedPostVars']['date_item'] = [
        'title' => 'Article date',
        'configuration' => [
            [
                'GETvar' => 'tx_news_pi1[controller]',
                'noMatch' => 'bypass',
            ],
            [
                'GETvar' => 'tx_news_pi1[overwriteDemand][year]'
            ],
            [
                'GETvar' => 'tx_news_pi1[overwriteDemand][month]',
            ],
            [
                'GETvar' => 'tx_news_pi1[overwriteDemand][day]',
                'noMatch' => 'bypass',
            ]
        ]
    ];
};

$init();
unset($init);