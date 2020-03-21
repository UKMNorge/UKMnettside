<?php

use UKMNorge\Wordpress\Blog;

$infoside = Blog::hentSideByPath(get_current_blog_id(), 'info');

if( $infoside->ID == $_GET['deleteInfoSide'] ) {
    try {
        Blog::fjernSide(get_current_blog_id(), 'info');
        UKMnettside::getFlash()->success('Informasjonsteksten er nå fjernet.');
    } catch( Exception $e ) {
        UKMnettside::getFlash()->error('En feil oppsto, og vi kunne ikke slette informasjonsteksten');
    }
} else {
    UKMnettside::getFlash()->error('Kunne ikke slette informasjonsteksten');
}