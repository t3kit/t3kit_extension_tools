<?php

namespace T3kit\T3kitExtensionTools\Hooks;

use TYPO3\CMS\Core\Page\PageRenderer;

/**
 * Class PageRenderPreProcess
 *
 * @package T3kit\T3kitExtensionTools\Hooks
 */
class PageRenderPreProcess
{

    /**
     * Remove standard TYPO3 DragDrop to make Gridelements work
     *
     * @param array $params
     * @return void
     */
    public function preRender(array &$params)
    {
        if (array_key_exists('RequireJS-Module-TYPO3/CMS/Backend/LayoutModule/DragDrop', $params['jsInline'])) {
            // remove standart typo3 drag and drop
            unset($params['jsInline']['RequireJS-Module-TYPO3/CMS/Backend/LayoutModule/DragDrop']);
        }
        if (array_key_exists('RequireJS-Module-TYPO3/CMS/Backend/LayoutModule/Paste', $params['jsInline'])
            && version_compare(TYPO3_version, '8.7', '>=')
        ) {
            // remove standart typo3 drag and drop
            $params['jsInline']['RequireJS-Module-TYPO3/CMS/Backend/LayoutModule/Paste']['code'] = str_replace(
                'TYPO3/CMS/Backend/LayoutModule/Paste',
                'TYPO3/CMS/T3kitExtensionTools/Paste',
                $params['jsInline']['RequireJS-Module-TYPO3/CMS/Backend/LayoutModule/Paste']['code']
            );
        }
    }
}
