<?php

namespace Multisite_JSON_API;
include_once(ABSPATH . 'wp-includes/pluggable.php');
//include_once(ABSPATH . 'wp-includes/rest-api/class-wp-rest-response.php');

//use WP_REST_Response;

class Endpoint
{
    function __construct()
    {
        $this->request_method = $_SERVER['REQUEST_METHOD'];
        $this->user = $_SERVER['PHP_AUTH_USER'];
        $this->password = $_SERVER['PHP_AUTH_PW'];
    }

    /*
     * Authenticate user using the HTTP Headers
     */
    public function authenticate()
    {
        if ($this->check_data()) {
            $user = wp_authenticate($this->user, $this->password);
            if (is_wp_error($user)) {
                return false;
            } else {
                wp_set_current_user($user->ID, '');
                if (current_user_can('manage_sites')) {
                    return $user;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }


    /*
    * Check if user and password from parameters exist
    */
    public function check_data()
    {
        if ($this->user && $this->password) {
            return true;
        } else {
            return false;
        }
    }


}