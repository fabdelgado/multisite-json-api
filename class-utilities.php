<?php

namespace Multisite_JSON_API;
include_once(ABSPATH . './wp-load.php');
require_once(ABSPATH . 'wp-includes/class-wp-error.php');

class Utilities
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

    /**
     * Creates a new user if one doesn't already exist.
     * If it does exist, just returns the existing user's id.
     * Sanitizes email address automatically.
     * @param dirty_email string An unsanitized email
     * @param username string The username
     * @return user WP_User The Wordpress user object
     */
    public function is_valid_email_or_create_email($emailUser, $siteName)
    {
        $email = is_email($emailUser, $deprecated = false);
        if ($email) {
            $user = get_user_by('email', $email);
            if ($user){
                return $user->ID;
            }else {
                //Create a new user with a random password
                $password = wp_generate_password(12, true);
                $user_id = wpmu_create_user($email, $password, $email);
                //wp_new_user_notification($user_id, $password);
                if ($user_id){
                    return $user_id;
                }else{
                    return false;
                }
            }
        } else {
            return false;
        }
    }


}