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
 * Class BackendFaq 
 *
 * @copyright  Martin Kozianka 2014
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    faq_be
 */
class BackendFaq extends \BackendModule {
	protected $strTemplate = 'be_faq_be';
	private $arrModules    = null;
	

	public function __construct() {
		parent::__construct();
	}

	protected function compile() {

		$cat    = \Input::get('category');
		$objCat = \FaqCategoryModel::findById($cat);

		$this->Template->href   = $this->getReferer(true);
		$this->Template->title  = specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']);
		$this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];

		if ($objCat === null) {
			return;
		}

		$arrQuestions  = array();
		$collectionFaq = \FaqModel::findByPid($objCat->id, array('order' => 'sorting'));
		if ($collectionFaq !== null) {
			foreach($collectionFaq as $faq) {
				if ($faq->published === '1') {
					$arrQuestions[] = $this->addQuestion($faq);
				}			
			}
		}

		$this->Template->cat_title    = $objCat->title;
		$this->Template->cat_headline = $objCat->headline;
		$this->Template->questions    = $arrQuestions;
				
	}

	private function addQuestion($objFaq) {
		$objTemplate           = new \FrontendTemplate('be_faq_question');				
		$objTemplate->cssID    = ' id="faq_'.$objFaq->pid.'_'.$objFaq->alias.'"';

		$objTemplate->id       = $objFaq->id;
		$objTemplate->tstamp   = $objFaq->tstamp;
		$objTemplate->alias    = $objFaq->alias;
		$objTemplate->author   = $objFaq->author;

		$objTemplate->question = $objFaq->question;
		$objFaq->answer        = \StringUtil::toHtml5($objFaq->answer);
		$objTemplate->answer   = \StringUtil::encodeEmail($objFaq->answer);
		
		$intImgMaxWidth        = 700;
		// Add image
		if ($objFaq->addImage && $objFaq->singleSRC != '') {
			$objModel = \FilesModel::findByUuid($objFaq->singleSRC);

			if ($objModel !== null && is_file(TL_ROOT . '/' . $objModel->path)) {
				$arrFaq = $objFaq->row();
				$arrFaq['singleSRC'] = $objModel->path;
				$this->addImageToTemplate($objTemplate, $arrFaq, $intImgMaxWidth);
			}
		}
		$objTemplate->enclosure = array();
		if ($objFaq->addEnclosure) {
			$this->addEnclosuresToTemplate($objTemplate, $objFaq->row());
		}

		return $objTemplate->parse(); 		
	}

	public function addBackendFaq($arrModules, $blnShowAll) {
		$this->arrModules = $arrModules;

		if ($this->addFaqCategories() === false) {
			unset($this->arrModules['faq_be']);
		}

		return $this->arrModules;
	}

	private function addFaqCategories() {

		if (!$this->Database->fieldExists('backendCategory', 'tl_faq_category')) {
			return false;
		}

		$result = $this->Database->prepare("SELECT * FROM tl_faq_category WHERE backendCategory = ? ORDER BY title")
			->execute('1');

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
