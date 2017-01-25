<?php


namespace T3kit\T3kitExtensionTools\Utility;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class FixedPostVarsConfigurationUtility
 * @package T3kit\T3kitExtensionTools\Utility
 */
class FixedPostVarsConfigurationUtility
{

    /**
     * Update configuration file of fixed post vars
     *
     * @return void
     */
    public function updateConfiguration()
    {
        $filePath = $this->getSaveFilePath();

        if (!$this->canWriteConfiguration($filePath)) {
            throw new \RuntimeException(
                $filePath . ' is not writable.', 1485349703
            );
        }

        $configurations = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3kit_extension_tools']['fixedPostVars'];
        $varNameToPageFixedUids = [];

        $pages = $this->getFixedPagesUids();

        $content = '<?php' . LF;
        $content .= '$init = function() {' . LF;

        // generate fixed post var config vars
        foreach ($pages as $page) {
            $key = $page['tx_t3kitextensiontools_fixed_post_var_conf'];

            if (array_key_exists($key, $configurations)) {
                if (!array_key_exists($key, $varNameToPageFixedUids)) {
                    $varName = '$' . GeneralUtility::underscoredToLowerCamelCase($key);
                    $content .= LF . $varName . ' = ' . ArrayUtility::arrayExport($configurations[$key]['configuration']) . ';' . LF;

                    // save var name
                    $varNameToPageFixedUids[$key] = [
                        'varName' => $varName,
                        'fixedUids' => [$page['uid']]
                    ];
                } else {
                    $varNameToPageFixedUids[$key]['fixedUids'][] = $page['uid'];
                }
            }
        }

        foreach ($varNameToPageFixedUids as $key => $varNameToPageFixedUid) {
            foreach ($varNameToPageFixedUid['fixedUids'] as $fixedUid) {
                $content .= '$GLOBALS[\'TYPO3_CONF_VARS\'][\'EXTCONF\'][\'realurl\'][\'_DEFAULT\'][\'fixedPostVars\'][\'' . $fixedUid . '\'] = ' . $varNameToPageFixedUid['varName'] . ';' . LF;
            }
        }

        $content .= LF . '};' . LF;
        $content .= '$init();' . LF;
        $content .= 'unset($init);' . LF;

        GeneralUtility::writeFile($filePath, $content, true);
    }

    /**
     * Get list of pages with fixed post var configuration
     *
     * @return array
     */
    protected function getFixedPagesUids()
    {
        if (version_compare(TYPO3_version, '8.0', '<')) {
            /** @var DatabaseConnection $dbConnection */
            $dbConnection = $GLOBALS['TYPO3_DB'];

            $pages = $dbConnection->exec_SELECTgetRows(
                'uid, tx_t3kitextensiontools_fixed_post_var_conf',
                'pages',
                'tx_t3kitextensiontools_fixed_post_var_conf != \'0\' AND tx_t3kitextensiontools_fixed_post_var_conf != \'\''
                . BackendUtility::deleteClause('pages')
            );
        } else {
            $pages = [];
        }

        return $pages;
    }

    /**
     * Checks if the configuration can be written.
     *
     * @param string $fileLocation
     * @return bool
     */
    protected function canWriteConfiguration($fileLocation)
    {
        $typo3confFolder = PATH_typo3conf;
        return @is_writable($typo3confFolder) && (!file_exists($fileLocation) || @is_writable($fileLocation));
    }

    /**
     * Get path where to save configuration
     *
     * @return string
     */
    protected function getSaveFilePath()
    {
        $extConf = \T3kit\T3kitExtensionTools\Utility\HelperUtility::getExtConf();

        if (is_array($extConf) && $extConf['fixedPostVarsSaveFilePath']) {
            $filePath = PATH_site . trim($extConf['fixedPostVarsSaveFilePath']);
        } else {
            $filePath = PATH_site . 'typo3conf/realurl_fixedPostVars_conf.php';
        }

        return $filePath;
    }
}