<?php


namespace T3kit\T3kitExtensionTools\Utility;

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
     * @return array|mixed
     */
    public static function getExtConf()
    {
        if (self::$extConf === null) {
            self::$extConf = @unserialize((string)$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['t3kit_extension_tools']);
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
            foreach ($configurations as $key => $configuration) {
                $availableConfigurations[] = [$configuration['title'], $key];
            }
        }

        return $availableConfigurations;
    }
}