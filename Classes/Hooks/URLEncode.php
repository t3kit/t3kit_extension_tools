<?php
namespace T3kit\T3kitExtensionTools\Hooks;

class URLEncode {

	/**
	 * Array of replacement characters
	 *
	 * @var array
	 */
	protected static $replacements = array(
		'ä' => 'a',
		'å' => 'a',
		'ö' => 'o',
		'Å' => 'a',
		'Ä' => 'a',
		'Ö' => 'o',
	);

	static function encodeTitle ($params, $parentObj) {
		// Replace letters in title
		$title = str_replace(array_keys(self::$replacements), self::$replacements, $params['title']);
		// If title changed
		if ($title != $params['title']) {
			// Check what class that called the function, they have different functions to encode title.
			if (get_class($params['pObj']) == 'tx_realurl') {
				// If hook is called from class tx_realurl which doesn't have encodeTitle
				// get configuration, needed as parameter lookUp_cleanAlias clean alias function in pObj
				$cfg = array('useUniqueCache_conf' => $params['encodingConfiguration']);
				$params['processedTitle'] = $params['pObj']->lookUp_cleanAlias($cfg, $title);
			} elseif (get_class($params['pObj']) == 'tx_realurl_advanced') {
				// If hook is called from class tx_realurl_advanced, well just call encodeTitle
				$params['processedTitle'] = $params['pObj']->encodeTitle($title);
			}
		}
		return $params['processedTitle'];
	}
}
?>
