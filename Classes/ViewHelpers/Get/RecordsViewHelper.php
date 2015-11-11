<?php
namespace T3kit\T3kitExtensionTools\ViewHelpers\Get;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
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
class RecordsViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
     * Initialize ViewHelper arguments
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('flexform2array', '', 'Do convert flexform to array', false);
    }

	/**
	 * Fetches tt_content records and returns them in an array
	 *
	 * @param string   $recordList     List of uids to fetch
	 * @return array                   Array of tt_content records
	 */
	public function render($recordList) {
        $records = array();
		$recordList = GeneralUtility::TrimExplode(',', $recordList);
        if (is_array($recordList) && count($recordList) > 0) {
            foreach ($recordList as $recordIdentifier) {
                $split = BackendUtility::splitTable_Uid($recordIdentifier);
                $tableName = empty($split[0]) ? 'tt_content' : $split[0];
                $shortcutRecord = BackendUtility::getRecord($tableName, $split[1]);
                if (is_array($shortcutRecord)) {
					if ($this->arguments['flexform2array']) {
						// parse flexform
						$flexformService = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Service\\FlexFormService');
						$shortcutRecord['pi_flexform'] = $flexformService->convertFlexFormContentToArray($shortcutRecord['pi_flexform']);
					}
                    $records[] = $shortcutRecord;
                }
            }
        }
        return $records;
	}
}
?>
