<?php
namespace T3kit\T3kitExtensionTools\ViewHelpers\Be;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\Utility\IconUtility;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Mats Svensson <mats.svensson@pixelant.se>, Pixelant AB
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class IconAndTitleLinkForRecordViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * Iterates through elements of $each and renders child nodes
     *
     * @param array     $record         The tt_content record
     * @param boolean   $oncludeTitle   If title should be included in output,
     * @return string
     */
    public function render($record)
    {
        $pageLayoutView = GeneralUtility::makeInstance(\TYPO3\CMS\Backend\View\PageLayoutView::class);
        $shortcutContent = '';
        $tableName = 'tt_content';
        if (is_array($record)) {
            $altText = BackendUtility::getRecordIconAltText($record, $tableName);
            $iconImg = IconUtility::getSpriteIconForRecord($tableName, $record, array('title' => $altText));
            if ($this->getBackendUser()->recordEditAccessInternals($tableName, $record)) {
                $iconImg = BackendUtility::wrapClickMenuOnIcon($iconImg, $tableName, $record['uid'], 1, '', '+copy,info,edit,view');
            }
            $link = $pageLayoutView->linkEditContent(htmlspecialchars(BackendUtility::getRecordTitle($tableName, $record)), $record);
            $shortcutContent = $iconImg . ' ' . $link;
        }
        return $shortcutContent;
    }

    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }
}
