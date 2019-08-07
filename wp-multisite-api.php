<?php

/*
Plugin Name: Rest Multisite api
Plugin URI: http://github.com/zuomy/rest-multisite-api
Description: Create multisite api
Version: 1.0.0
Author: Fabian Delgado
Author URI: http://github.com/fabdelgado
License: GPLv2 or later
*/

require_once(__DIR__ . '/class-endpoint.php');
include_once(__DIR__ . '/class-data.php');
include_once(__DIR__ . '/class-validations.php');

use Data_for_API\Information;
use Multisite_JSON_API\Endpoint;

/*
 * Route for list all objects multisite
 * @route
 */
add_action( 'rest_api_init', function(){
    register_rest_route(
        'wp-multisites/v1',
        'list',
        array(
            'methods'  => 'POST',
            'callback' => array(new Information(), 'wp_get_all_sites'),
            'permission_callback' => array(new Endpoint(), 'authenticate'),
        )
    );
} );


/*
 * Route for create multisite
 * @route
 */
add_action( 'rest_api_init', function(){
    register_rest_route(
        'wp-multisites/v1',
        'create',
        array(
            'methods'  => 'POST',
            'callback' => array(new Information(), 'wp_create_multisite'),
            'permission_callback' => array(new Endpoint(), 'authenticate'),
        )
    );
} );




/*
 * Route for create multisite
 * @route
 */
add_action( 'rest_api_init', function(){
    register_rest_route(
        'wp-multisites/v1',
        'delete',
        array(
            'methods'  => 'POST',
            'callback' => array(new Information(), 'wp_delete_multisite'),
            'permission_callback' => array(new Endpoint(), 'authenticate'),
        )
    );
} );









