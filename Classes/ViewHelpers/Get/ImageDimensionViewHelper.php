<?php
namespace T3kit\T3kitExtensionTools\ViewHelpers\Get;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Web Essentials <production@web-essentials.asia>
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

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\Exception;

/**
 * Get exact resource width or height
 */
class ImageDimensionViewHelper extends AbstractViewHelper {

	/**
	 * @param string $src
	 * @param integer $width
	 * @param integer $height
	 * @param integer $minWidth
	 * @param integer $minHeight
	 * @param integer $maxWidth
	 * @param integer $maxHeight
	 * @param boolean $treatIdAsReference
	 * @param FileInterface|AbstractFileFolder $image
	 * @param string|boolean $crop
	 * @param string $dimensionType
	 *
	 * @return string|NULL
	 * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
	 */
	public function render($src = NULL, $width = NULL, $height = NULL, $minWidth = NULL, $minHeight = NULL, $maxWidth = NULL, $maxHeight = NULL, $treatIdAsReference = FALSE, $image = NULL, $crop = NULL, $dimensionType = 'width') {
		if (is_null($src) && is_null($image) || !is_null($src) && !is_null($image)) {
			throw new Exception('You must either specify a string src or a File object.', 1485241341);
		}

		$dimension = NULL;

		try {
			$image = self::getImageService()->getImage($src, $image, $treatIdAsReference);
			if ($crop === NULL) {
				$crop = $image instanceof FileReference ? $image->getProperty('crop') : NULL;
			}
			$processingInstructions = [
				'width' => $width,
				'height' => $height,
				'minWidth' => $minWidth,
				'minHeight' => $minHeight,
				'maxWidth' => $maxWidth,
				'maxHeight' => $maxHeight,
				'crop' => $crop,
			];
			$processedImage = self::getImageService()->applyProcessingInstructions($image, $processingInstructions);

			$dimension = $processedImage->getProperty($dimensionType);

		} catch (ResourceDoesNotExistException $e) {
			// thrown if file does not exist
		} catch (\UnexpectedValueException $e) {
			// thrown if a file has been replaced with a folder
		} catch (\RuntimeException $e) {
			// RuntimeException thrown if a file is outside of a storage
		} catch (\InvalidArgumentException $e) {
			// thrown if file storage does not exist
		}

		return $dimension;
	}

	/**
	 * Return an instance of ImageService using object manager
	 *
	 * @return ImageService
	 */
	protected static function getImageService() {
		/** @var ObjectManager $objectManager */
		$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
		return $objectManager->get(ImageService::class);
	}
}
