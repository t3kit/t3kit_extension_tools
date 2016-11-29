<?php


namespace T3kit\T3kitExtensionTools\Xclass\Frontend\DataProcessing;


/**
 * Class GalleryProcessor
 * Extend class to add new image positions
 *
 * @package T3kit\T3kitExtensionTools\Xclass\FrontendDataProcessing
 */
class GalleryProcessor extends \TYPO3\CMS\Frontend\DataProcessing\GalleryProcessor
{
    /**
     * Matching the tt_content field towards the imageOrient option
     *
     * @var array
     */
    protected $availableGalleryPositions = [
        'horizontal' => [
            'center' => [0, 8],
            'right' => [1, 9, 17, 25],
            'left' => [2, 10, 18, 26],
            'vertically-aligned' => [101, 102]
        ],
        'vertical' => [
            'above' => [0, 1, 2],
            'intext' => [17, 18, 25, 26, 101, 102],
            'below' => [8, 9, 10]
        ],
        'verticallyAligned' => [
            'left' => [102],
            'right' => [101]
        ]
    ];

    /**
     * Define the gallery position
     *
     * Gallery has a horizontal and a vertical position towards the text
     * and a possible wrapping of the text around the gallery.
     *
     * @return void
     */
    protected function determineGalleryPosition()
    {
        parent::determineGalleryPosition();

        if ($this->mediaOrientation === 101 || $this->mediaOrientation === 102) {
            $this->galleryData['position']['noWrap'] = true;
        }
    }
}