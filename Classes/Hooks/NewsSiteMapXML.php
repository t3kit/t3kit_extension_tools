<?php

namespace T3kit\T3kitExtensionTools\Hooks;

/**
 * (c) 2017 Web Essentials <production@web-essentials.asia>
 *
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

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook to add news items to the sitemap created by SEO Basics
 */
class NewsSiteMapXML {

	/**
	 * @var integer
	 */
	public $detailPid;
	/**
	 * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
	 */
	public $cObj;

	/**
	 * @param $params
	 * @param $object
	 */
	public function main(&$params, &$object) {
		$this->cObj = $object->cObj;
		$this->detailPid = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_news.']['settings.']['defaultDetailPid'];
		$params['content'] .= $this->generateSitemap( $this->getNewsItems() );
	}


	public function getNewsItems() {
		return $this->getDatabaseConnection()->exec_SELECTgetRows(
			'*',
			'tx_news_domain_model_news',
			'sys_language_uid IN(' . (int)GeneralUtility::_GP('L') . ',-1)' .
			' AND type=0',
			'',
			'tstamp DESC'
		);
	}

	/**
	 * @param $newsItems
	 *
	 * @return string
	 */
	public function generateSitemap($newsItems) {
		$content = '';
		foreach ($newsItems as $item) {
			$lastmod = $item['tstamp'];
			$lastmod = date('c', $lastmod);
			$content .= '
	<url>
		<loc>' . htmlspecialchars($this->getNewsItemUrl($item)) . '</loc>
		<lastmod>' . htmlspecialchars($lastmod) . '</lastmod>
	</url>';
		}
		return $content;
	}

	protected function getNewsItemUrl($newsRow) {
		$conf = array(
			'additionalParams' => '&tx_news_pi1[controller]=News&tx_news_pi1[action]=detail&tx_news_pi1[news]=' . $newsRow['uid'],
			'forceAbsoluteUrl' => 1,
			'parameter' => $this->detailPid,
			'returnLast' => 'url',
			'useCacheHash' => true,
		);
		return htmlspecialchars($this->cObj->typoLink('', $conf));
	}


	/**
	 * @return \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}
}
