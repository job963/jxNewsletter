<?php

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';
 
/**
 * Module information
 */
$aModule = array(
    'id'           => 'jxnewsletter',
    'title'        => 'jxNewsletter - Display and Export of Newsletter Subscriptions',
    'description'  => array(
                        'de' => 'Anzeige und Export von Newsletter Abonnenten.',
                        'en' => 'Display and Export of Newsletter Subscriptions.'
                        ),
    'thumbnail'    => 'jxnewsletter.png',
    'version'      => '0.3.1',
    'author'       => 'Joachim Barthel',
    'url'          => 'https://github.com/job963/jxNewsletter',
    'email'        => 'jobarthel@gmail.com',
    'extend'       => array(
                        ),
    'files'        => array(
        'jxnewsletter_list'      => 'jxmods/jxnewsletter/application/controllers/admin/jxnewsletter_list.php',
                        ),
    'templates'    => array(
        'jxnewsletter_list.tpl'    => 'jxmods/jxnewsletter/application/views/admin/tpl/jxnewsletter_list.tpl',
                        ),
    'events'       => array(
                        ),
    'settings'     => array(
                            array(
                                    'group' => 'JXNEWSLETTER_RETRIEVE', 
                                    'name'  => 'bJxNewsletterCustNo', 
                                    'type'  => 'bool', 
                                    'value' => 'true'
                                    ),
                            array(
                                    'group' => 'JXNEWSLETTER_RETRIEVE', 
                                    'name'  => 'bJxNewsletterCompany', 
                                    'type'  => 'bool', 
                                    'value' => 'true'
                                    ),
                            array(
                                    'group' => 'JXNEWSLETTER_RETRIEVE', 
                                    'name'  => 'bJxNewsletterAddress', 
                                    'type'  => 'bool', 
                                    'value' => 'true'
                                    ),
                            array(
                                    'group' => 'JXNEWSLETTER_RETRIEVE', 
                                    'name'  => 'bJxNewsletterPhone', 
                                    'type'  => 'bool', 
                                    'value' => 'true'
                                    ),
                            array(
                                    'group' => 'JXNEWSLETTER_RETRIEVE', 
                                    'name'  => 'bJxNewsletterCountry', 
                                    'type'  => 'bool', 
                                    'value' => 'true'
                                    ),
                            array(
                                    'group' => 'JXNEWSLETTER_RETRIEVE', 
                                    'name'  => 'bJxNewsletterSubscribed', 
                                    'type'  => 'bool', 
                                    'value' => 'true'
                                    ),
                            array(
                                    'group' => 'JXNEWSLETTER_RETRIEVE', 
                                    'name'  => 'bJxNewsletterLanguage', 
                                    'type'  => 'bool', 
                                    'value' => 'true'
                                    ),
                            array(
                                    'group' => 'JXNEWSLETTER_RETRIEVE', 
                                    'name'  => 'bJxNewsletterRevenue', 
                                    'type'  => 'bool', 
                                    'value' => 'true'
                                    ),
                            array(
                                    'group' => 'JXNEWSLETTER_RETRIEVE', 
                                    'name'  => 'bJxNewsletterOrderCount', 
                                    'type'  => 'bool', 
                                    'value' => 'true'
                                    ),
                            array(
                                    'group' => 'JXNEWSLETTER_RETRIEVE', 
                                    'name'  => 'bJxNewsletterReturnCount', 
                                    'type'  => 'bool', 
                                    'value' => 'true'
                                    ),
                            array(
                                    'group' => 'JXNEWSLETTER_RETRIEVE', 
                                    'name'  => 'bJxNewsletterReturnSum', 
                                    'type'  => 'bool', 
                                    'value' => 'true'
                                    ),
                            array(
                                    'group' => 'JXNEWSLETTER_DOWNLOAD', 
                                    'name'  => 'bJxNewsletterHeader', 
                                    'type'  => 'bool', 
                                    'value' => 'true'
                                    ),
                            array(
                                    'group' => 'JXNEWSLETTER_DOWNLOAD', 
                                    'name'  => 'sJxNewsletterSeparator', 
                                    'type'  => 'select', 
                                    'value' => 'comma',
                                    'constrains' => 'comma|semicolon|tab|pipe|tilde', 
                                    'position' => 0 
                                    ),
                            array(
                                    'group' => 'JXNEWSLETTER_DOWNLOAD', 
                                    'name'  => 'bJxNewsletterQuote', 
                                    'type'  => 'bool', 
                                    'value' => 'true'
                                    ),
                            array(
                                    'group' => 'JXNEWSLETTER_INCLUDESETTINGS', 
                                    'name'  => 'aJxNewsletterIncludeFiles', 
                                    'type'  => 'arr', 
                                    'value' => array(), 
                                    'position' => 1
                                    ),
                        )
    );

?>
