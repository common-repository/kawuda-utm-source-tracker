<?php

class Kawuda_Chart {

	public function get_utm_source_pie_chart($filters){
		global $wpdb;
		$fromDate               = @$filters['from_date'];
		$toDate                 = @$filters['to_date'];
		$utm_key                 = @$filters['utm_key'];
		$where_sql = "";
		if ( $fromDate != "" && $toDate != "" ) {
				$where_sql .= " Where K.visit_datetime between '" . $fromDate . "' AND '" . $toDate . "'";
			}
		$kawuda_user_table_name = $wpdb->prefix . 'kawuda_user';
		$tablename_users        = $wpdb->prefix . "users";

		$sql  = "SELECT K.".$utm_key." as utm_source, count(*) as utm_count FROM " . $kawuda_user_table_name . " as K " . $where_sql . " GROUP BY ".$utm_key;

		$kawudastats = $wpdb->get_results( ( $sql ) );
		//$utm = json_decode(json_encode($kawudastats), true);

		 
		$utm = array();
		//$utm = array("Mushrooms"=>3,"Onions"=>1,"ddd"=>1);
		foreach ( $kawudastats as $kawudastat ) :
			
			$utm[$kawudastat->utm_source] = (int) $kawudastat->utm_count;
			 
 
		endforeach;
			/*echo "<pre>";
			print_r($utm);
			echo "</pre>";*/
		 
		//echo $wpdb->last_query;
		//die();
		//
		return $utm;
	}
	 

}