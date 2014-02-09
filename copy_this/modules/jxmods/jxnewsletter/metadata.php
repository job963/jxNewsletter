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
                        'de'=>'Anzeige und Export von Newletter Abonnenten.',
                        'en'=>'Display and Export of Newsletter Subscriptions.'
                        ),
    'thumbnail'    => 'jxnewsletter.png',
    'version'      => '0.2',
    'author'       => 'Joachim Barthel',
    'url'          => 'https://github.com/job963/jxNewsletter',
    'email'        => 'jobarthel@gmail.com',
    'extend'       => array(
                        ),
    'files'        => array(
        'jxnewsletter_list'      => 'jxmods/jxnewsletter/application/controllers/admin/jxnewsletter_list.php',
                        ),
    'templates'    => array(
        'jxnewsletter_list.tpl'    => 'jxmods/jxnewsletter/views/admin/tpl/jxnewsletter_list.tpl',
                        ),
    'events'       => array(
                        ),
    'settings'     => array(
                        )
    );

?>