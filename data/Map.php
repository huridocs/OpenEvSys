<?php

/**
 * Class for geocoding and map.
 *
 * This file is part of OpenEvsys.
 *
 * Copyright (C) 2009  Human Rights Information and Documentation Systems,
 *                     HURIDOCnameS), http://www.huridocs.org/, info@huridocs.org
 *
 * OpenEvsys is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OpenEvsys is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author	Hmayak Tigranyan <hmayak.tigranyan@huridocs.org>
 * 
 * @package	OpenEvsys
 * @subpackage	DataModel
 *
 */
include_once(APPROOT . '3rd/php-google-map-api/GoogleMap.php');
include_once(APPROOT . '3rd/php-google-map-api/JSMin.php');

class Map {

    public function __construct() {
        
    }

    public static function geocode($address = NULL) {
        if ($address) {
            $coordinates = self::checkAndGetCoordinates($address);
            if ($coordinates) {
                $geocodes = array(
                    'country' => '',
                    'location_name' => '',
                    'latitude' => $coordinates['lat'],
                    'longitude' => $coordinates['lng']
                );

                return $geocodes;
            }
            $map_object = new GoogleMapAPI();
            $map_object->setLookupService("GOOGLE");
            $geocodes_full = $map_object->geoGetCoordsFull($address);

            // Verify that the request succeeded
            if (!isset($geocodes_full->status))
                return FALSE;
            if ($geocodes_full->status != 'OK')
                return FALSE;

            // Convert the Geocoder's results to an array
            $all_components = json_decode(json_encode($geocodes_full->results), TRUE);
            $location = $all_components[0]['geometry']['location'];

            // Find the country
            $address_components = $all_components[0]['address_components'];
            $country_name = NULL;
            foreach ($address_components as $component) {
                if (in_array('country', $component['types'])) {
                    $country_name = $component['long_name'];
                    break;
                }
            }

            // If no country has been found, use the formatted address
            if (empty($country_name)) {
                $country_name = $all_components[0]['formatted_address'];
            }

            $geocodes = array(
                'country' => $country_name,
                'location_name' => $all_components[0]['formatted_address'],
                'latitude' => $location['lat'],
                'longitude' => $location['lng']
            );

            return $geocodes;
        } else {
            return FALSE;
        }
    }

    function checkAndGetCoordinates($address) {

        preg_match_all("/-?\d+[\.]\d+/", $address, $coordinates, PREG_SET_ORDER);

        if (isset($coordinates[0][0]) && isset($coordinates[1][0])) {
            return array('lat' => $coordinates[0][0], 'lng' => $coordinates[1][0]);
        }
        return false;
    }

}
