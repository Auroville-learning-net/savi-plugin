<?php
/*
Plugin Name: SAVI-Connect
Plugin URI: http://wordpress.syllogic.in
Description: SAVI-Connect extends wordpress to create links between AV Units, their projects and the opportunities within each Unit / Project
Author: Syllogic Consultants Pvt Ltd
Author URI: http://syllogic.in
*/
if (!class_exists("Listing")) 
    include_once("class_listing.php");
if (!class_exists("Default_Profile")) 
    include_once("Base_Views-Default_Profile.php");
    
if (!class_exists("AV_units"))
    include_once("Auroville-Units.php");
    
if (!class_exists("opportunity"))
    include_once("Opportunities.php");
    
if (!class_exists("Guest_house"))
    include_once("Guest-House.php");

//if (!class_exists("Program"))
//    include_once("Programs.php");
    
if (!class_exists("AV_projects"))
    include_once("AVProjects.php");

if (!class_exists("Enquiry_Review"))
   include_once("View_0-Enquiry_Review.php");
   
if (!class_exists("Approve_Profile"))
   include_once("View_1-Approve_Profile.php");

if (!class_exists("Volunteer_Opportunity_Review"))
   include_once("View_2-Volunteer_Opportunity_Review.php");  

if (!class_exists("Confirm_Opportunity"))
   include_once("View_3-Confirm_Opportunity.php");
  
if (!class_exists("Dormant_Volunteers"))
   include_once("View_4-Dormant_Volunteers.php");

if (!class_exists("Visa_Documentation"))
   include_once("View_5-Visa_Documentation.php");  

if (!class_exists("Confirm_Visa_Status"))
   include_once("View_6-Confirm_Visa_Status.php");    

if (!class_exists("Ready_to_Board"))
   include_once("View_7-Ready_to_Board.php");    
   
if (!class_exists("Research"))
   include_once("Research.php"); 

if (!class_exists("FRS_Custom_Bulk_Action"))
   include_once("custom-bulk-action.php");      


if (!class_exists("Custom_Views_Column"))
   include_once("Custom_Views_Column.php");      

   
class SaviConnect extends Listing {
    public function __construct() {
        $av_unit_Obj = new AV_units();
        $opportunity_obj = new Opportunity();
        $research_obj= new Research(); 
        $guesthouse_obj = new Guest_house();
        //$program_obj = new Program(); 
        $AVProjects_obj = new AV_projects();
        $enquiryreview_obj = new Enquiry_Review();
        $approveprofile_obj = new Approve_Profile();
        $volunteer_obj = new Volunteer_Opportunity_Review();
        $confirm_Opportunity_obj = new Confirm_Opportunity();
        $dormant_volunteers_obj = new Dormant_Volunteers();
        $Visa_Documentation_obj = new Visa_Documentation();
        $Confirm_Visa_Status_obj = new Confirm_Visa_Status();
        $Ready_to_Board_obj = new Ready_to_Board();
        $custom_bluk_action_obj= new FRS_Custom_Bulk_Action();
        $Custom_Views_Column_obj= new Custom_Views_Column(); 
        
        add_action('admin_print_styles', array($this,'my_scripts'));
    }
	function my_scripts() {
		wp_register_style( 'prefix-style', plugins_url('css/mystyle.css', __FILE__) );
		wp_enqueue_style( 'prefix-style' );
	}
}
$savi_obj = new SaviConnect();
?>