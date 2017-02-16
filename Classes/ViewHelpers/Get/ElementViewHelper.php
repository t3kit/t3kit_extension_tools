<?php

namespace T3kit\T3kitExtensionTools\ViewHelpers\Get;

//use TYPO3\CMS\Core\Utility\GeneralUtility;
//use TYPO3\CMS\Backend\Utility\BackendUtility;
/* * *************************************************************
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
 * ************************************************************* */
class ElementViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * return array element $data[$key]
     *
     * @param array   $data     array
     * @param string   $key     key
     *
     * @return array                   Array of tt_content records
     */
    public function render($data, $key) {
	if (is_array($data) && key_exists($key, $data)) {
	    return $data[$key];
	} else {
	    return NULL;
	}
    }
}

?>
