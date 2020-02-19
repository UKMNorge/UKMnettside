<?php

use UKMNorge\Arrangement\Arrangement;
use UKMNorge\DesignWordpress\Environment\Front\Front;

Front::init();

$festival_settings = [
    'festival_leave_plakat',
    'festival_leave_stemning'
];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['slogan'])) {
        Front::setBannerSlagord($_POST['slogan']);
    }

    if (isset($_POST['menu_color'])) {
        Front::setBannerMenyFarge($_POST['menu_color']);
    }

    if (isset($_POST['menu'])) {
        Front::setMeny(intval($_POST['menu']));
    }


    // FESTIVAL-info
    foreach( $festival_settings as $setting ) {
        if( isset( $_POST[$setting] ) ) {
            update_option($setting, $_POST[$setting]);
        }
    }
}

foreach( $festival_settings as $setting ) {
    UKMnettside::addViewData($setting, get_option($setting));
}

UKMnettside::addViewData(
    [
        'forside' => new Front(),
        'menyer' => wp_get_nav_menus()
    ]
);

if( get_option('pl_id') ) {
    $arrangement = new Arrangement( intval(get_option('pl_id') ));
    UKMnettside::addViewData('arrangement', $arrangement);
}