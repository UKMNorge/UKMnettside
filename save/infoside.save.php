<?php

use UKMNorge\DesignWordpress\Environment\Front\OmradeFront;
use UKMNorge\Wordpress\Blog;

$forside = OmradeFront::getInfoside();

// Hvis brukeren har trykt lagre og vi har data.
if( !empty($_POST['informasjonstekst']) ) {
    $side = false;
    try {
        $side = Blog::hentSideByPath(get_current_blog_id(), 'info');
    } catch( Exception $e ) {
        if( $e->getCode() == 172011 ) {
            $side = Blog::opprettSide(get_current_blog_id(), 'info', 'Informasjonstekst');
        } else {
            UKMnettside::getFlash()->error('Kunne ikke lagre informasjonstekst!. Systemet sa: '. $e->getMessage());
        }
    }

    if( $side ) {
        Blog::oppdaterSideInnhold(get_current_blog_id(), 'info', $_POST['informasjonstekst']);
        Blog::setOption(get_current_blog_id(), 'UKMnettside_info_last_updated', time());
        UKMnettside::getFlash()->success('Informasjonstekst lagret!');
    } else {
        UKMnettside::getFlash()->error('Klarte ikke å lagre infotekst');
        UKMnettside::addViewData('backupPageContent', $_POST['informasjonstekst']);
    }
} else {
    Blog::fjernSide(get_current_blog_id(), 'info');
}