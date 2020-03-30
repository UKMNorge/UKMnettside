<?php

use UKMNorge\Arrangement\Arrangement;
use UKMNorge\DesignWordpress\Environment\Front\Front;
use UKMNorge\DesignWordpress\Environment\Front\OmradeFront;
use UKMNorge\Nettverk\Omrade;

Front::init();

$festival_settings = [
    'festival_leave_plakat',
    'festival_leave_stemning'
];

////////////////////////////////////////////
// LAGRING
////////////////////////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // FORSIDE-BILDE
    if ( isset( $_POST['banner_image'] ) ) {
        UKMnettside::require('save/forsidebilde.save.php');
    }
    
    // FORSIDE-BANNER
    if (isset($_POST['slogan'])) {
        Front::setBannerSlagord($_POST['slogan']);
    }

    // FARGE PÅ MENYKNAPP
    if (isset($_POST['menu_color'])) {
        Front::setBannerMenyFarge($_POST['menu_color']);
    }

    // EKSTRA-MENY
    if (isset($_POST['menu'])) {
        Front::setMeny(intval($_POST['menu']));
    }

    // INFOSIDE (SAVE)
    if (isset($_POST['informasjonstekst'])) {
        UKMnettside::require('save/infoside.save.php');
    }

    // FESTIVAL-INFO
    foreach ($festival_settings as $setting) {
        if (isset($_POST[$setting])) {
            update_option($setting, $_POST[$setting]);
        }
    }
} else {
    // INFOSIDE (DELETE)
    if (isset($_GET['deleteInfoSide'])) {
        UKMnettside::require('delete/infoside.delete.php');
    }
}

////////////////////////////////////////////
// HENTING OG KLARGJØRING AV INFO
////////////////////////////////////////////
foreach ($festival_settings as $setting) {
    UKMnettside::addViewData($setting, get_option($setting));
}

switch (get_option('site_type')) {
    case 'fylke':
        $omrade = Omrade::getByFylke(get_option('fylke'));
        OmradeFront::setOmrade($omrade);
        $front = new OmradeFront();
        break;
    case 'kommune':
        $omrade = Omrade::getByKommune(get_option('kommune'));
        OmradeFront::setOmrade($omrade);
        $front = new OmradeFront();
        break;
    default:
        $front = new Front();
        break;
}

UKMnettside::addViewData(
    [
        'forside' => $front,
        'menyer' => wp_get_nav_menus(),
    ]
);

if (get_option('pl_id')) {
    $arrangement = new Arrangement(intval(get_option('pl_id')));
    UKMnettside::addViewData('arrangement', $arrangement);
}
