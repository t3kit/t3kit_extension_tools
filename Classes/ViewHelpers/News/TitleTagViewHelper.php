<?php
namespace T3kit\T3kitExtensionTools\ViewHelpers\News;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Andriy Oprysko <andriy@pixelant.se>, Pixelant AB
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
class TitleTagViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * initializeArguments
     *
     * @return void
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments() {
        parent::initializeArguments();
        $this->registerArgument('appendTitle', 'boolean', 'Specifies if currecnt page title need to be appended', FALSE, FALSE);
    }

    /**
     * @param string $titleTag
     * @return void
     */
    public function render($titleTag = '') {
        if(empty($titleTag)) {
            $titleTag = trim($this->renderChildren());
        }

        if($this->arguments['appendTitle']) {
            $titleTag = $this->getTSFE()->page['title'] . ' ' . $titleTag;
        }

        /** @var \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->setTitle($titleTag);
    }

    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getTSFE() {
        return $GLOBALS['TSFE'];
    }
}