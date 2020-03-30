<?php

UKMnettside::addViewData(
    [
        'UKM_HOSTNAME' => UKM_HOSTNAME,
        'image' => get_option('UKM_banner_image'),
	    'image_position_y' => get_option('UKM_banner_image_position_y') == false ? 
            'top' : 
            get_option('UKM_banner_image_position_y'),
        'image_id' => null
    ]
);