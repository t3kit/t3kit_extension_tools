<?php


namespace T3kit\T3kitExtensionTools\Hooks;


use TYPO3\CMS\Core\Page\PageRenderer;

/**
 * Class PageRenderPreProcess
 *
 * @package T3kit\T3kitExtensionTools\Hooks
 */
class PageRenderPreProcess {

    /**
     * Remove standard TYPO3 DragDrop to make Gridelements work
     *
     * @param array $params
     * @param PageRenderer $pObj
     * @return void
     */
    public function preRender(array &$params, PageRenderer $pObj) {
        $drapAndDropRequireModule = 'RequireJS-Module-TYPO3/CMS/Backend/LayoutModule/DragDrop';
        if(array_key_exists($drapAndDropRequireModule, $params['jsInline'])) {
            // remove standart typo3 drag and drop
            unset($params['jsInline'][$drapAndDropRequireModule]);
        }
    }
}