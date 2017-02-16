<?php


namespace T3kit\T3kitExtensionTools\Hook;

use T3kit\T3kitExtensionTools\Utility\FixedPostVarsConfigurationUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Class DataHandlerHook
 * @package T3kit\T3kitExtensionTools\Hook
 */
class DataHandlerHook
{
    /**
     * If page was created/edited and fixedPostVarConf changed we need to update configuration file
     *
     * @param string $status
     * @param string $table
     * @param int $id
     * @param array $fieldArray
     * @param DataHandler $pObj
     * @return void
     */
    public function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, DataHandler $pObj)
    {
        // if field tx_t3kitextensiontools_fixed_post_var_conf was modify we need to update configuration
        if ($table === 'pages' && isset($fieldArray['tx_t3kitextensiontools_fixed_post_var_conf'])) {
            /** @var FixedPostVarsConfigurationUtility $fixedPostVarsConfigurationUtility */
            $fixedPostVarsConfigurationUtility = GeneralUtility::makeInstance(FixedPostVarsConfigurationUtility::class);
            $fixedPostVarsConfigurationUtility->updateConfiguration();
        }
    }

    /**
     * Update fixed post vars conf if page was removed
     *
     * @param string $table
     * @param string|int $id
     * @param array $recordToDelete
     * @param boolean &$recordWasDeleted
     * @param DataHandler $pObj
     * @return void
     */
    public function processCmdmap_deleteAction($table, $id, $recordToDelete, &$recordWasDeleted, DataHandler $pObj) {
        if ($table === 'pages' && $recordToDelete['tx_t3kitextensiontools_fixed_post_var_conf']) {
            $pObj->deleteEl($table, $id);
            $recordWasDeleted = true;

            /** @var FixedPostVarsConfigurationUtility $fixedPostVarsConfigurationUtility */
            $fixedPostVarsConfigurationUtility = GeneralUtility::makeInstance(FixedPostVarsConfigurationUtility::class);
            $fixedPostVarsConfigurationUtility->updateConfiguration();
        }
    }
}