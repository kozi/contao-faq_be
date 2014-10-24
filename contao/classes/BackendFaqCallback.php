<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2014 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    faq_be 
 * @license    LGPL 
 * @filesource
 */
namespace ContaoBackendFaq;

/**
 * Class BackendFaqCallback
 *
 * @copyright  Martin Kozianka 2014
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    faq_be
 */
class BackendFaqCallback extends \System {
	private $arrModules    = null;

    public function addBackendFaq($arrModules, $blnShowAll) {
		$this->arrModules = $arrModules;

		if ($this->addFaqCategories() === false) {
			unset($this->arrModules['faq_be']);
		}

		return $this->arrModules;
	}

	private function addFaqCategories() {
        $db = \Database::getInstance();

		if (!$db->fieldExists('backendCategory', 'tl_faq_category')) {
			return false;
		}

		$result = $db->prepare("SELECT * FROM tl_faq_category WHERE backendCategory = ? ORDER BY title")->execute('1');

		if ($result->numRows === 0) {
			return false;
		}

		unset($this->arrModules['faq_be']['modules']['backend_faq']);

		while($result->next()) {			
			$this->arrModules['faq_be']['modules'][] = array(
					'title' => specialchars($result->title),
					'label' => $result->headline,
					'icon'  => sprintf(' style="background-image:url(\'%s%s\')"', TL_ASSETS_URL, 'system/modules/faq_be/assets/sticky-notes-text.png'),
					'class' => 'navigation ',
					'href'  => \Environment::get('script') . '?do=backend_faq&category='.$result->id,
			);
		}
		return true;
	}
	
}

