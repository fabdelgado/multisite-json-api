<?php

namespace Data_for_API;

use Validations_for_API\Validations;
use Multisite_JSON_API\Utilities;

include_once(__DIR__ . '/class-validations.php');
include_once(__DIR__ . '/class-utilities.php');
require_once(ABSPATH . 'wp-admin/includes/admin.php');
require_once(ABSPATH . 'wp-includes/class-wp-error.php');


class Information
{

    protected $validation;
    protected $endpoint;

    public function __construct()
    {
        $this->validation = new Validations();
        $this->endpoint = new Utilities();
    }

    public function wp_get_all_sites()
    {
        $public = null;
        $spam = null;
        $archived = null;
        $deleted = null;

        if (isset($_GET['public']))
            $public = $_GET['public'];
        if (isset($_GET['spam']))
            $spam = $_GET['spam'];
        if (isset($_GET['archived']))
            $archived = $_GET['archived'];
        if (isset($_GET['deleted']))
            $deleted = $_GET['deleted'];

        $sites = get_sites(array(
            "public" => $public,
            "spam" => $spam,
            "archived" => $archived,
            "deleted" => $deleted
        ));
        return $sites;
    }


    /*
     * Creates a new site.
     * @param title string The title of the site
     * @param site_name string The sitename used for the site, will become the path or the subdomain
     * @param user_id The ID of the admin user for this site
     */
    public function wp_create_multisite($request)
    {
        $json = $request->get_json_params();
        $title = $json['title'];
        $siteName = $json['site'];
        $user = $json['userId'];
        $domain  = $siteName . $_SERVER['REQUEST_URI'];
        $path = '/';
        $email = $json['email'];


        var_dump($title);
        //Validate name site
        if ($this->validation->is_valid_sitename($siteName)) {
            //Validate site title
            if ($this->validation->is_valid_site_title($title)) {
                //Check if email is valid or instead create the user
                $userID = $this->endpoint->is_valid_email_or_create_email($email,$siteName);
                if($userID){
                    //Create a site
                    $site_id = wpmu_create_blog($domain, $path, $title, $user, array('public' => true));
                    if(!$site_id){
                        //Assign user to site after create
                       user_to_blog($site_id, $userID, 'administrator');
                       return $site_id;
                    }
                }else{
                    return new \WP_REST_Response(array('error' => 'The email is not valid'), 400);
                }
            } else {
                return new \WP_REST_Response(array('error' => 'The site title is not valid.'), 400);
            }
        } else {
            return new \WP_REST_Response(array('error' => 'The site name is not valid.'), 400);
        }
    }

    /*
    * Soft delete multisite.
    * @param siteId Identifier for multisite
    */
    public function wp_delete_multisite($request)
    {
        $json = $request->get_json_params();
        $siteId = $json['siteId'];

        $deleteme = get_blog_details($siteId);

        if ($deleteme != false && $deleteme->blog_id == $siteId) {
            wpmu_delete_blog($siteId, $drop = false);
            $deleteme->deleted = true;
            return $deleteme;
        } else {
            return new \WP_REST_Response(array('error' => 'The site you are trying to delete does not exist.'), 400);
        }
    }
}