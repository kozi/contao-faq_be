<?php

$GLOBALS['TL_DCA']['tl_faq_category']['palettes']['default'] = str_replace(
	'jumpTo;',
	'jumpTo,backendCategory;',
	$GLOBALS['TL_DCA']['tl_faq_category']['palettes']['default']
);

$GLOBALS['TL_DCA']['tl_faq_category']['fields']['backendCategory'] = array(
		'label'                   => &$GLOBALS['TL_LANG']['tl_faq_category']['backendCategory'],
		'exclude'                 => true,
		'filter'                  => true,
		'inputType'               => 'checkbox',		
		'sql'                     => "char(1) NOT NULL default ''"
);