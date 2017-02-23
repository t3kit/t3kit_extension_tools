<?php


namespace T3kit\T3kitExtensionTools\Utility;

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;

/**
 * Class MainUtility
 * @package T3kit\T3kitExtensionTools\Utility
 */
class HelperUtility
{
    /**
     * Keep configuration of extension
     *
     * @var array
     */
    protected static $extConf;

    /**
     * Get extension configuration
     *
     * @return array
     */
    public static function getExtConf()
    {
        if (self::$extConf === null) {
            if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['t3kit_extension_tools'])) {
                self::$extConf = unserialize((string)$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['t3kit_extension_tools']);
            } else {
                self::$extConf = [];
            }
        }

        return self::$extConf;
    }

    /**
     * Get items for select field
     *
     * @param array &$params
     * @param object $pObj
     */
    public function getTcaFixedPostVarItems(&$params, $pObj)
    {
        $items = array_merge($params['items'], $this->getAvailableFixedPostVarConfigurations());

        $params['items'] = $items;
    }

    /**
     * Get predefined configurations
     *
     * @return array
     */
    protected function getAvailableFixedPostVarConfigurations()
    {
        $configurations = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3kit_extension_tools']['fixedPostVars'];
        $availableConfigurations = [];

        if (is_array($configurations)) {
            foreach ($configurations as $configuration) {
                $key = (string)$configuration['key'];

                if (empty($key)) {
                    $this->addErrorMessage('realurl_conf.error.empty_key');
                } elseif ($key !== '--div--' && array_key_exists($key, $availableConfigurations)) {
                    $this->addErrorMessage('realurl_conf.error.double_key', [$key]);
                } else {
                    $availableConfigurations[] = [$configuration['title'], $key];
                }
            }
        }

        return $availableConfigurations;
    }

    protected function addErrorMessage($error, $arguments = [])
    {
        /** @var LanguageService $lang */
        $languageService = $GLOBALS['LANG'];

        $ll = 'LLL:EXT:t3kit_extension_tools/Resources/Private/Language/locallang_db.xlf:';

        $message = $languageService->sL($ll . $error);
        if (!empty($arguments)) {
            $message = sprintf($message, ...$arguments);
        }

        /** @var FlashMessage $flashMessage */
        $flashMessage = GeneralUtility::makeInstance(
            FlashMessage::class,
            $message,
            $languageService->sL($ll . 'realurl_conf.error.title'),
            FlashMessage::ERROR,
            true
        );

        /** @var FlashMessageQueue $flashMessageQueue */
        $flashMessageQueue = GeneralUtility::makeInstance(
            FlashMessageQueue::class,
            'core.template.flashMessages'
        );

        $flashMessageQueue->addMessage($flashMessage);
    }
}
