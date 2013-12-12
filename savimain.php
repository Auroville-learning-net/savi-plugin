<?php
/*
Plugin Name: SAVI-Connect
Plugin URI: http://codex.wordpress.org/Adding_Administration_Menus
Description: SAVI-Connect extends wordpress to create links between AV Units, their projects and the opportunities within each Unit / Project
Author: Syllogic
Author URI: http://wordpress.syllogic.in
*/
if (!class_exists("Listing")) 
    include_once("class_listing.php");

if (!class_exists("AV_units"))
    include_once("Auroville-Units.php");
    
if (!class_exists("opportunity"))
    include_once("Opportunities.php");
    
if (!class_exists("Guest_house"))
    include_once("Guest-House.php");

if (!class_exists("Program"))
    include_once("Programs.php");
    
if (!class_exists("AV_projects"))
    include_once("AVProjects.php");
    
class SaviConnect extends Listing {
    public function __construct() {
        $av_unit_Obj = new AV_units();
        $opportunity_obj = new Opportunity();
        $guesthouse_obj = new Guest_house();
        $guesthouse_obj = new Program(); 
        $AVProjects_obj = new AV_projects();
       
    }

}

$savi_obj = new SaviConnect();

?>
