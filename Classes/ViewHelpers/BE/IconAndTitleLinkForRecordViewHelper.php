<?php
namespace T3kit\T3kitExtensionTools\ViewHelpers\Be;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;

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
class IconAndTitleLinkForRecordViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
		/**
	 * @var IconFactory
	 */
	protected $iconFactory;

	/**
	 * Construct to initialize class variables.
	 */
	public function __construct() {
		$this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
	}

	/**
	 * Iterates through elements of $each and renders child nodes
	 *
	 * @param array 	$record 		The tt_content record
	 * @param boolean 	$oncludeTitle 	If title should be included in output,
	 * @return string
	 */
	public function render($record) {
		$shortcutContent = '';
		$tableName = 'tt_content';
		if (is_array($record)) {
			$icon = $this->iconFactory->getIconForRecord($tableName, $record, Icon::SIZE_SMALL)->render();
			if ($this->getBackendUser()->recordEditAccessInternals($tableName, $record)) {
				$icon = $this->getPageLayoutController()->getModuleTemplate()->wrapClickMenuOnIcon($icon, $tableName, $record['uid'], 1, '', '+copy,info,edit,view');
			}
			$link = $this->linkEditContent(htmlspecialchars(BackendUtility::getRecordTitle($tableName, $record)), $record);
			$shortcutContent = $icon . $link;
		}
		return $shortcutContent;
	}

	/**
	 * Will create a link on the input string and possibly a big button after the string which links to editing in the RTE.
	 * Used for content element content displayed so the user can click the content / "Edit in Rich Text Editor" button
	 *
	 * @param string $str String to link. Must be prepared for HTML output.
	 * @param array $row The row.
	 * @return string If the whole thing was editable ($this->doEdit) $str is return with link around. Otherwise just $str.
	 * @see getTable_tt_content()
	 */
	public function linkEditContent($str, $row) {
		$addButton = '';
		$onClick = '';
		if ($this->getBackendUser()->recordEditAccessInternals('tt_content', $row)) {
			// Setting onclick action for content link:
			$onClick = BackendUtility::editOnClick('&edit[tt_content][' . $row['uid'] . ']=edit');
		}
		// Return link
		return $onClick ? '<a href="#" onclick="' . htmlspecialchars($onClick)
			. '" title="' . $this->getLanguageService()->getLL('edit', TRUE) . '">' . $str . '</a>' . $addButton : $str;
	}

	/**
	 * @return PageLayoutController
	 */
	protected function getPageLayoutController() {
		return $GLOBALS['SOBE'];
	}

	/**
	 * @return BackendUserAuthentication
	 */
	protected function getBackendUser() {
		return $GLOBALS['BE_USER'];
	}

	/**
	 * @return \TYPO3\CMS\Lang\LanguageService
	 */
	protected function getLanguageService() {
		return $GLOBALS['LANG'];
	}
}
?>
