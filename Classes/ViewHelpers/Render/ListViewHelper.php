<?php
namespace T3kit\T3kitExtensionTools\ViewHelpers\Render;
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
class ListViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
	
	/**
	 * Generate a list from the content
	 *
	 * @param string $content
	 * @param string $tagClass (Optional) The class for the list tag
	 * @param string $itemClass (Optional) The class for the li
	 * @param string $tag (Optional) The tag to render (ol,li)
	 * @return string
	 */
	public function render($content = NULL, $tagClass = NULL, $itemClass = NULL, $tag = NULL) {
		if (NULL === $content) {
			$content = $this->renderChildren();
		}
		$tag = in_array($tag, array('ul', 'ol')) ? $tag : 'ul';
		$tagClass = $tagClass === NULL ? '' : ' class="' . $tagClass . '"';
		$itemClass = $itemClass === NULL ? '' : ' class="' . $itemClass . '"';
		$listItems = explode(PHP_EOL, $content);
		$html = '';
		if (is_array($listItems)) {
			$html .= '<' . $tag . $tagClass . '>';
			foreach ($listItems as $listItem) {
				$html .= '<li' . $itemClass . '>' . $listItem . '</li>';
			}
			$html .= '</' . $tag . '>';
		}
		return $html;
	}
}
?>