<?php
namespace T3kit\T3kitExtensionTools\DataProcessing;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * This data processor can be used for fetching information about selected website languages to use in f.ex a language menu
 *
 * Example TypoScript configuration:
 * 10 = Pixelant\ThemeCore\DataProcessing\FlexFormProcessor
 * 10 {
 *   as = languages
 *   availableLanguages = 2,1
 *   currentLanguageUid = 0
 *   defaultLanguageIsoCode = en
 *   defaultLanguageFlag = gb
 *   defaultLanguageLabel = English
 * }
 *
 */
class LanguagesProcessor implements DataProcessorInterface {

	/**
     * @var ContentObjectRenderer
     */
    protected $cObj;

	/**
	 * Process flexform field data to an array
	 *
	 * @param ContentObjectRenderer $cObj The data of the content element or page
	 * @param array $contentObjectConfiguration The configuration of Content Object
	 * @param array $processorConfiguration The configuration of this processor
	 * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
	 * @return array the processed data as key/value store
	 */
	public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData) {
        if (isset($processorConfiguration['if.']) && !$cObj->checkIf($processorConfiguration['if.'])) {
            return $processedData;
        }

        $this->cObj = $cObj;

		$languages = array();

        // set targetvariable, default "flexform"
        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, 'languages');

		$availableLanguages = array_unique(GeneralUtility::trimExplode(',', '0,' . $processorConfiguration['availableLanguages'], TRUE));
		$currentLanguageUid = (int) $processorConfiguration['currentLanguageUid'];
		$defaultLanguageIsoCode = $processorConfiguration['defaultLanguageIsoCode'] ? $processorConfiguration['defaultLanguageIsoCode'] : 'en';
		$defaultLanguageLabel = $processorConfiguration['defaultLanguageLabel'] ? $processorConfiguration['defaultLanguageLabel'] : 'English';
		$defaultLanguageFlag = $processorConfiguration['defaultLanguageFlag'] ? $processorConfiguration['defaultLanguageFlag'] : 'gb';

		$flagIconPath = $processorConfiguration['flagIconPath'];
		$flagIconFileExtension = $processorConfiguration['flagIconFileExtension'];

        foreach ($isoCodes as $isoCode) {
            $languages[$isoCode] = $languageService->sL('LLL:EXT:core/Resources/Private/Language/db.xlf:sys_language.language_isocode.' . $isoCode);
        }
        if (!empty($availableLanguages)) {
			foreach ($availableLanguages as $languageUid) {

				$languageIsocode = '';
				$class = '';
				$label = '';
				$flag = '';
				$hasTranslation = FALSE;

				if ((int) $languageUid === 0) {
					$languageIsocode = $defaultLanguageIsoCode;
					$flag = $defaultLanguageFlag;
					$label = $defaultLanguageLabel;
					$hasTranslation = TRUE;
				} elseif (($sysLanguage = $this->getSysLanguage($languageUid))) {
					$languageIsocode = $sysLanguage['language_isocode'];
					$flag = $sysLanguage['flag'];
					$label = $sysLanguage['title'];
					$hasTranslation = $this->hasTranslation($GLOBALS['TSFE']->id, $languageUid);
				}

				$languages[] = array(
					'L' => $languageUid,
					'languageIsocode' => $languageIsocode,
					'label' => $label,
					'flag' => $flag,
					'hasTranslation' => $hasTranslation,
					'active' => ((int) $currentLanguageUid === (int) $languageUid),
				);
			}
			$processedData[$targetVariableName] = $languages;
		}

        // if targetvariable is settings, try to merge it with contentObjectConfiguration['settings.']
        if ($targetVariableName == 'settings') {
            if (is_array($contentObjectConfiguration['settings.'])) {
                $convertedConf = GeneralUtility::removeDotsFromTS($contentObjectConfiguration['settings.']);
                foreach ($convertedConf as $key => $value) {
                    if (!isset($processedData[$targetVariableName][$key]) || $processedData[$targetVariableName][$key] == false) {
                        $processedData[$targetVariableName][$key] = $value;
                    }
                }
            }
        }
		return $processedData;
	}

	/**
	 * Get the data of a sys language
	 * @param $uid \int Language uid
	 * @return \array Language data array
	 */
	protected function getSysLanguage($uid = 0) {
		$sysLanguage = FALSE;
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery( 'uid, title, flag, language_isocode', 'sys_language', 'uid=' . ((int) $uid));
		$sysLanguage = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		$GLOBALS['TYPO3_DB']->sql_free_result($res);
		return $sysLanguage;
	}

	/**
	 * Check if a translation of a page is available
	 * @param $pid \int Page id
	 * @param $languageUid \int Language uid
	 * @return bool
	 */
	protected function hasTranslation($pid, $languageUid) {
		$enableFieldsSql  = $this->cObj->enableFields('pages_language_overlay');
		$languageSql = ' pid=' . ((int)$pid) . ' AND `sys_language_uid` =' . ((int)$languageUid) . ' ';
		$where = $languageSql . $enableFieldsSql;
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('COUNT(uid)', 'pages_language_overlay', $where);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		$GLOBALS['TYPO3_DB']->sql_free_result($res);
		return ($row[0] > 0);
	}
}
