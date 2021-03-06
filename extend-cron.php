<?php
/*
Plugin Name: Docs to WordPress extender - Run on Cron (every minute)
Author: William P. Davis, Bangor Daily News
Author URI: http://wpdavis.com/
Version: 1.1
*/

//First, allow the cron to run every minute
add_filter( 'cron_schedules', 'dtwp_more_reccurences' );
function dtwp_more_reccurences( $schedules ) {
        $schedules[ 'min' ] = array( 'interval' => 60, 'display' => 'Every Minute' );
        return $schedules;
}

//On activation, add the recurring cron job
register_activation_hook( __FILE__, 'dtwp_schedule_event' );
function dtwp_schedule_event() {
        wp_schedule_event( time(), 'min', 'dtwp_cronjob' );
}

//Run the cron job
add_action( 'dtwp_cronjob', 'dtwp_check_gdocs' );
function dtwp_check_gdocs( ) {

	//Init the Docs to WP
	$docs_to_wp = new Docs_To_WP();
	
	//We're just going to call one function:
	$result = $docs_to_wp->startTransfer();
	do_action( 'docs_to_wp_post_cron_run', $result );

}


//Deactivation
register_deactivation_hook(__FILE__, 'dtwp_deactivate_cron');
function dtwp_deactivate_cron() {
        wp_clear_scheduled_hook( 'dtwp_cronjob' );
}
