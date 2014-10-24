<?php

$GLOBALS['TL_HOOKS']['getUserNavigation'][]   = array('ContaoBackendFaq\BackendFaqCallback', 'addBackendFaq');

array_insert($GLOBALS['BE_MOD'], 0, array('faq_be' => array(
        'backend_faq'   => array (
        'callback'      => 'ContaoBackendFaq\BackendFaq',
        'stylesheet'    => 'system/modules/faq_be/assets/be_style.css',
        'icon'          => 'system/modules/faq_be/assets/sticky-notes-text.png'
    )
)));
