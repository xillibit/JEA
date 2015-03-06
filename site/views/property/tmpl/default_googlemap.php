<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: default_googlemap.php 440 2013-08-29 08:36:23Z ilhooq $
 * @package     Joomla.Site
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


JHtml::_('behavior.framework');

$langs  = explode('-', $this->document->getLanguage());

$lang   = $langs[0];
$region = $langs[1];

$latitude  = floatval($this->row->latitude);
$longitude = floatval($this->row->longitude);
$address   = str_replace(array("\n", "\r\n"), ' ', addslashes($this->row->address));
$town      = str_replace(array("\n", "\r\n"), ' ', addslashes($this->row->town));

if(!empty($address) && !empty($town)){
    $address .= ', ' . $town . ', '. $lang;
} elseif (!empty($address)) {
    $address .= ', '. $lang;
} elseif (!empty($town)) {
    $address = $town . ', '. $lang;
} elseif (!empty($this->row->department)) {
    $address = addslashes($this->row->department) . ', '. $lang;
} else {
    $address = $lang;
}

/*$script = <<<EOD
var map = null;

function initMap(mapOptions, MarkerLatlng) {
    map = new google.maps.Map(document.id('jea_property_map'), mapOptions);
    var marker = new google.maps.Marker({
        position: MarkerLatlng, 
        map: map, 
        title: '{$this->row->ref}'
    });
}

window.addEvent("domready", function(){
    var longitude  = {$longitude};
    var latitude   = {$latitude};

    if (longitude && latitude) {
        var myLatlng = new google.maps.LatLng(latitude, longitude);
        var options = {
          zoom : 15,
          center : myLatlng,
          mapTypeId : google.maps.MapTypeId.ROADMAP
        };
        initMap(options, myLatlng);

    } else {
        var geocoder = new google.maps.Geocoder();
        var opts = {'address':'$address', 'language':'$lang', 'region':'$region'};
        geocoder.geocode(opts, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var myLatlng = results[0].geometry.location;
                var options = {
                  center : myLatlng,
                  mapTypeId : google.maps.MapTypeId.ROADMAP
                };
                initMap(options, myLatlng);
                map.fitBounds(results[0].geometry.viewport);
             }
        });
    }
});

EOD;
$this->document->addScriptDeclaration($script); */
?>
<input type="hidden" id="com-jea-map-ref" value="<?php echo $this->row->ref ?>" />
<input type="hidden" id="com-jea-map-latitude" value="<?php echo $latitude ?>" />
<input type="hidden" id="com-jea-map-longitude" value="<?php echo $longitude ?>" />

<div id="jea_property_map"></div>

