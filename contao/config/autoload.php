<?php

ClassLoader::addClasses(array(
    'ContaoBackendFaq\BackendFaqCallback'  => 'system/modules/faq_be/classes/BackendFaqCallback.php',
	'ContaoBackendFaq\BackendFaq'          => 'system/modules/faq_be/classes/BackendFaq.php'
));

TemplateLoader::addFiles(array(
	'be_faq_be'            => 'system/modules/faq_be/templates',
	'be_faq_question'      => 'system/modules/faq_be/templates'
));



