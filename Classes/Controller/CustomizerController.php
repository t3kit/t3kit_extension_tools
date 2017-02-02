<?php

namespace T3kit\T3kitExtensionTools\Controller;

use  \KayStrobach\Themes\Controller\EditorController;

/**
 * Class CustomizerController
 *
 * @package T3kit\T3kitExtensionTools\Controller
 */
class CustomizerController extends EditorController {

	/**
	 * save changed constants
	 *
	 * @param array $data
	 * @param array $check
	 * @param integer $pid
	 *
	 * @return void
	 */
	public function saveCustomizedThemeAction(array $data, array $check, $pid) {
		$this->tsParser->applyToPid($pid, $data, $check);
		$this->redirect('customizeTheme');

		$this->themeRepository->findByUid(array())->getAllPreviewImages();
	}

	/**
	 * Initialize form
	 */
	public function customizeThemeAction() {
		parent::indexAction();
	}

}
