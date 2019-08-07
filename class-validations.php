<?php

namespace Validations_for_API;

class Validations
{

    /*
    * Checks whether sitename is a valid domain name or site name
    * Works on both domain and subdirectory
    */
    public function is_valid_sitename($request)
    {
        //Check if subdomain functionality is activate
        if (is_subdomain_install()) {
            //compare if name is valid and not is reserved name
            if (preg_match('/^[a-zA-Z0-9][a-zA-Z0-9-]+$/', $request) && !in_array($request, get_subdirectory_reserved_names()))
                return true;
            else
                return false;
        } else {
            return false;
        }
    }

    /*
     * Validates emails via the wordpress functions.
     * @param email_address
     */
    public function is_valid_email($request)
    {
        $email = sanitize_email($request);
        if (!empty($email) && is_email($email))
            return true;
        else
            return false;
    }

    /*
    * Validates that the site title is at least 2 alphanumerics and doesn't start with a space
    */
    public function is_valid_site_title($request)
    {
        // Make sure site title is not empty
        if (preg_match('/^[a-zA-Z0-9-_][a-zA-Z0-9-_ ]+/', $request))
            return true;
        else
            return false;
    }

}