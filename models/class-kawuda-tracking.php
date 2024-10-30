<?php

class Kawuda_Tracking {

	public function is_existing_user( $data ) {
		global $wpdb;
		$kawuda_user_table_name = $wpdb->prefix . 'kawuda_user';
		$rowcount               = 0;

		// Check if cookie is already set
		$kawuda_id_sess_id = session_id();
		if(!empty(@$_COOKIE['kawuda_cookie'])) {
			$kawuda_id_sess_id = $_COOKIE['kawuda_cookie'];
		}
		
		$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $kawuda_user_table_name . " WHERE session_id=%s", wp_unslash( $kawuda_id_sess_id ) ));

		$rowcount = $wpdb->num_rows;
		if ( $rowcount > 0 ) {
			return $user;
		}

		return 0;
	}

	function find_new_stat( $filters ) {
		global $wpdb;
		$kawuda_user_table_name = $wpdb->prefix . 'kawuda_user';
		$tablename_users        = $wpdb->prefix . "users";

		$rend      = $filters['rend'];
		$kawuda_id = intval( $filters['kawuda_id'] );
		$where_sql = "";
		if ( $id > 0 ) {
			$where_sql = " WHERE K.id=" . $kawuda_id;
		}
		$rend = 5;
		$sql  = "SELECT K.*, U.user_nicename FROM " . $kawuda_user_table_name . " as K LEFT JOIN " . $tablename_users . " as U ON U.ID=K.wp_user_id " . $where_sql . " ORDER BY ID DESC LIMIT 0, $rend ";

		$kawudastats = $wpdb->get_results( ( $sql ) );
		//echo $wpdb->last_query;
		foreach ( $kawudastats as $kawudastat ):
			$newStatTbl .= '
					<tr>
			        <td><a href="admin.php?page=kawuda_options&action=view&id=' . esc_attr( $kawudastat->id ) . '">' . esc_attr( $kawudastat->id ) . '</a></td>
			        <td>' . esc_attr( $kawudastat->user_nicename ) . '</td>
			        <td>' . esc_attr( $kawudastat->user_ip ) . '</td>
			        <td>' . esc_attr( $kawudastat->visit_datetime ) . '</td>
			        <td>' . esc_attr( $kawudastat->from_site ) . '  </td>
			        <td>' . esc_attr( $kawudastat->utm_source ) . ' </td>
			        <td>' . esc_attr( $kawudastat->utm_medium ) . ' </td>
			        <td>' . esc_attr( $kawudastat->utm_campaign ) . ' </td>
			        <td>' . esc_attr( $kawudastat->utm_term ) . ' </td>
			        <td>' . esc_attr( $kawudastat->utm_content ) . ' </td>
			        <td>' . esc_attr( $kawudastat->google_id ) . ' </td>
			        <td>' . esc_attr( $kawudastat->fb_id ) . ' </td>
			         
			    </tr>
						';
		endforeach;
		$newStatTblArr = array( "newStatTbl" => $newStatTbl );
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $newStatTblArr );
		die();

	}

	function find_new_user_stat( $filters ) {
		global $wpdb;
		$kawuda_user_table_name = $wpdb->prefix . 'kawuda_user';
		$tablename_users        = $wpdb->prefix . "users";
		$tablename_link         = $wpdb->prefix . "kawuda_link";

		$rend      = $filters['rend'];
		$kawuda_id = intval( $filters['kawuda_id'] );
		$where_sql = "";
		if ( $id > 0 ) {
			$where_sql = " WHERE L.kawuda_id=" . $kawuda_id;
		}

		$kawudastats = $wpdb->get_results( $wpdb->prepare( "SELECT L.*, U.user_nicename FROM " . $kawuda_user_table_name . " as K LEFT JOIN " . $tablename_users . " as U ON U.ID=K.wp_user_id  LEFT JOIN " . $tablename_link . " as L ON K.ID=L.kawuda_id WHERE L.kawuda_id=%d  ORDER BY L.id desc LIMIT %d, %d", $kawuda_id, 0, $rend ) );


		//echo $wpdb->last_query;
		foreach ( $kawudastats as $kawudastat ):
			$newStatTbl .= '
					<tr>

			        <td>' . esc_attr( $kawudastat->id ) . '</td>
			       
			        <td>' . esc_attr( $kawudastat->action_datetime ) . '</td>
			        <td>' . esc_attr( $kawudastat->from_url ) . '  </td>
			        <td>' . esc_attr( $kawudastat->to_url ) . ' </td>
			         
			        <td>' . esc_attr( $kawudastat->tracking_item_type ) . ' </td>
			        <td>' . esc_attr( $kawudastat->tracking_item ) . ' </td>
			        
			         
			    </tr>
						';
		endforeach;
		$newStatTblArr = array( "newStatTbl" => $wpdb->last_query, "newStatTbl" => $newStatTbl );
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $newStatTblArr );
		die();

	}

	function find_new_user_link_stat( $filters ) {
		global $wpdb;
		$kawuda_user_table_name = $wpdb->prefix . 'kawuda_user';
		$tablename_users        = $wpdb->prefix . "users";
		$tablename_link         = $wpdb->prefix . "kawuda_link";

		$rend      = $filters['rend'];
		 

		$kawudastats = $wpdb->get_results( $wpdb->prepare( "SELECT L.*, U.user_nicename FROM " . $kawuda_user_table_name . " as K LEFT JOIN " . $tablename_users . " as U ON U.ID=K.wp_user_id  LEFT JOIN " . $tablename_link . " as L ON K.ID=L.kawuda_id ORDER BY L.id desc LIMIT %d, %d", 0, 5 ) );


		//echo $wpdb->last_query;
		foreach ( $kawudastats as $kawudastat ):
			$newStatTbl .= '
					<tr>

			        <td><a href="admin.php?page=kawuda_options&action=view&id=' . esc_attr( $kawudastat->kawuda_id ) . '">' . esc_attr( $kawudastat->kawuda_id ) . '</a></td>
			       
			        <td>' . esc_attr( $kawudastat->action_datetime ) . '</td>
			        <td>' . esc_attr( $kawudastat->from_url ) . '  </td>
			        <td>' . esc_attr( $kawudastat->to_url ) . ' </td>
			         
			        <td>' . esc_attr( $kawudastat->tracking_item_type ) . ' </td>
			        <td>' . esc_attr( $kawudastat->tracking_item ) . ' </td>
			        
			         
			    </tr>
						';
		endforeach;
		$newStatTblArr = array( "newStatTbl" => $wpdb->last_query, "newStatTbl" => $newStatTbl );
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $newStatTblArr );
		die();

	}

	// list all sku, paginated. 
	// allow filters
	function find( $filters = null ) {
		global $wpdb;
		$searchParam = "";
		$searchvar   = "";


		$kawuda_user_table_name = $wpdb->prefix . 'kawuda_user';
		$tablename_users        = $wpdb->prefix . "users";
		$rstart                 = @$filters['rstart'];
		$rend                   = @$filters['rend'];
		$dir                    = @$filters['dir'];
		$ob                     = @$filters['ob'];
		$fromDate               = @$filters['fromDate'];
		$toDate                 = @$filters['toDate'];
		$searchParam            = " WHERE 1=1";
		$searchvar              = ",";
		if ( ! empty( $filters ) && ( isset( $filters['searchKey'] ) && $filters['searchKey'] != "" ) || ( ( isset( $filters['fromDate'] ) && $filters['fromDate'] != "" ) && ( isset( $filters['toDate'] ) && $filters['toDate'] != "" ) ) ) {

			if ( isset( $filters['searchKey'] ) && $filters['searchKey'] != "" ) {
				$searchParam .= " AND (K.utm_source LIKE  '%" . $filters['searchKey'] . "%' OR K.utm_campaign LIKE  '%" . $filters['searchKey'] . "%' )";

			}

			if ( $fromDate != "" && $toDate != "" ) {
				$searchParam .= " AND K.visit_datetime between '" . $fromDate . "' AND '" . $toDate . "'";
			}
			$sql = "SELECT K.*, U.user_nicename FROM " . $kawuda_user_table_name . " as K LEFT JOIN " . $tablename_users . " as U ON U.ID=K.wp_user_id " . $searchParam . " ORDER BY $ob $dir LIMIT $rstart, $rend ";

			$kawudastats = $wpdb->get_results( ( $sql ) );
		} else {
			$kawudastats = $wpdb->get_results( $wpdb->prepare( "SELECT K.*,U.user_nicename FROM " . $kawuda_user_table_name . " as K LEFT JOIN " . $tablename_users . " as U ON U.ID=K.wp_user_id ORDER BY $ob $dir LIMIT %d, %d", $rstart, $rend ) );
		}

		//echo $wpdb->last_query;
		return $kawudastats;

	}

	function getTotalCount( $filters = null ) {
		global $wpdb;
		$param       = "";
		$searchVar   = "";
		$searchParam = "";
		$fromDate    = @$filters['fromDate'];
		$toDate      = @$filters['toDate'];
		$searchParam = " WHERE 1=1";
		if ( ! empty( $filters ) && $filters['searchKey'] != "" ) {
			$searchParam .= " AND (K.utm_source LIKE  '%" . $filters['searchKey'] . "%' OR K.utm_campaign LIKE  '%" . $filters['searchKey'] . "%' )";
		}
		if ( $fromDate != "" && $toDate != "" ) {
			$searchParam .= " AND K.visit_datetime between '" . $fromDate . "' AND '" . $toDate . "'";
		}
		$kawuda_user_table_name = $wpdb->prefix . 'kawuda_user';
		if ( ! empty( $filters ) && isset( $filters['searchKey'] ) ) {
			$totalCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $kawuda_user_table_name . " as K" . $searchParam ) );
		} else {
			$totalCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $kawuda_user_table_name . " as k" ) );
		}
		//echo "<br>";
		//echo $wpdb->last_query;
		return $totalCount;

	}

	// list all user, paginated. 
	// allow filters
	function findByid( $filters = null ) {
		global $wpdb;
		$searchParam = "";
		$searchvar   = "";


		$kawuda_user_table_name = $wpdb->prefix . 'kawuda_user';
		$tablename_users        = $wpdb->prefix . "users";
		$tablename_link         = $wpdb->prefix . "kawuda_link";
		$rstart                 = $filters['rstart'];
		$rend                   = $filters['rend'];
		$dir                    = $filters['dir'];
		$ob                     = $filters['ob'];
		$kawuda_id              = $filters['kawuda_id'];

		if ( ! empty( $filters ) && isset( $filters['searchKey'] ) && $filters['searchKey'] != "" ) {
			$searchParam = " WHERE (L.tracking_item LIKE %s OR L.tracking_item_type LIKE %s ) AND L.kawuda_id=%d";
			$searchvar   = $filters['searchKey'];
			$kawudastats = $wpdb->get_results( $wpdb->prepare( "SELECT L.*, U.user_nicename FROM " . $kawuda_user_table_name . " as K LEFT JOIN " . $tablename_users . " as U ON U.ID=K.wp_user_id  LEFT JOIN " . $tablename_link . " as L ON K.ID=L.kawuda_id " . $searchParam . " ORDER BY $ob $dir LIMIT %d, %d", "%" . $searchvar . "%", "%" . $searchvar . "%", $kawuda_id, $rstart, $rend ) );
		} else {
			$kawudastats = $wpdb->get_results( $wpdb->prepare( "SELECT L.*,U.user_nicename FROM " . $kawuda_user_table_name . " as K LEFT JOIN " . $tablename_users . " as U ON U.ID=K.wp_user_id LEFT JOIN " . $tablename_link . " as L ON K.ID=L.kawuda_id WHERE L.kawuda_id=%d ORDER BY $ob $dir LIMIT %d, %d", $kawuda_id, $rstart, $rend ) );
		}

		//echo $wpdb->last_query;
		return $kawudastats;

	}

	function getTotalCountById( $filters = null ) {
		global $wpdb;
		$param          = "";
		$searchVar      = "";
		$kawuda_id      = $filters['kawuda_id'];
		$tablename_link = $wpdb->prefix . "kawuda_link";
		if ( ! empty( $filters ) && $filters['searchKey'] != "" ) {
			$searchParam = " WHERE (tracking_item LIKE '%" . $filters['searchKey'] . "' OR tracking_item_type LIKE '%" . $filters['searchKey'] . "') AND L.kawuda_id='" . $kawuda_id . "'";
		}


		if ( ! empty( $filters ) && isset( $filters['searchKey'] ) && $filters['searchKey'] != "" ) {
			$totalCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $tablename_link . "" . $searchParam ) );
		} else {
			$totalCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $tablename_link . " WHERE kawuda_id='" . $kawuda_id . "'" ) );
		}
		//echo "<br>";
		//echo $wpdb->last_query;
		return $totalCount;

	}

	public function kawuda_track_a_tag( $data, $lastid ) {
		global $wpdb;
		$kawuda_link_table_name = $wpdb->prefix . 'kawuda_link';
		$kawuda_id_sess_id = session_id();
		if(!empty(@$_COOKIE['kawuda_cookie'])) {
			$kawuda_id_sess_id = $_COOKIE['kawuda_cookie'];
		}
		Kawuda_Tracking::prepare_vars( $data );
		$wpdb->insert(
			$kawuda_link_table_name,
			array(
				'kawuda_id'          => $lastid,
				'session_id'         => wp_unslash($kawuda_id_sess_id),
				'from_url'           => $data['from_url'],
				'to_url'             => $data['to_url'],
				'post_id'            => $data['post_id'],
				'tracking_item_type' => $data['tracking_item_type'],
				'tracking_item'      => $data['tracking_item']
			),
			array( '%d', '%s', '%s', '%s', '%d', '%s', '%s' )
		);


		return "kawuda paw";
	}

	// prepare and sanitize vars
	public static function prepare_vars( &$data ) {
		$data['from_url']           = sanitize_text_field( $data['from_url'] );
		$data['to_url']             = sanitize_text_field( $data['to_url'] );
		$data['post_id']            = sanitize_text_field( $data['post_id'] );
		$data['tracking_item_type'] = sanitize_text_field( $data['tracking_item_type'] );
		$data['tracking_item']      = sanitize_text_field( $data['tracking_item'] );
		$data['user_ip']            = sanitize_text_field( $data['user_ip'] );

	}

	// prepare and sanitize vars
	public static function prepare_vars_user( &$data ) {
		$data['current_user_id'] = sanitize_text_field( $data['current_user_id'] );
		$data['utm_source']      = sanitize_text_field( $data['utm_source'] );
		$data['utm_medium']      = sanitize_text_field( $data['utm_medium'] );
		$data['utm_campaign']    = sanitize_text_field( $data['utm_campaign'] );
		$data['utm_term']        = sanitize_text_field( $data['utm_term'] );
		$data['utm_content']     = sanitize_text_field( $data['utm_content'] );
		$data['google_id']       = sanitize_text_field( $data['google_id'] );
		$data['fb_id']           = sanitize_text_field( $data['fb_id'] );
		$data['user_platform']   = sanitize_text_field( $data['user_platform'] );
		$data['user_mobile']     = sanitize_text_field( $data['user_mobile'] );
		$data['user_browser']    = sanitize_text_field( $data['user_browser'] );
		$data['from_site']       = sanitize_text_field( $data['from_site'] );
	}

	public function kawuda_register_user( $data ) {
		global $wpdb;

		$kawuda_user_table_name = $wpdb->prefix . 'kawuda_user';
		$kawuda_id_sess_id = session_id();
		if(!empty(@$_COOKIE['kawuda_cookie'])) {
			$kawuda_id_sess_id = $_COOKIE['kawuda_cookie'];
		}
		Kawuda_Tracking::prepare_vars_user( $data );
		$wpdb->insert(
			$kawuda_user_table_name,
			array(
				'wp_user_id'    => $data['current_user_id'],
				'session_id'    => $kawuda_id_sess_id,
				'utm_source'    => $data['utm_source'],
				'utm_medium'    => $data['utm_medium'],
				'utm_campaign'  => $data['utm_campaign'],
				'utm_term'      => $data['utm_term'],
				'utm_content'   => $data['utm_content'],
				'google_id'     => $data['google_id'],
				'fb_id'         => $data['fb_id'],
				'user_platform' => $data['user_platform'],
				'user_mobile'   => $data['user_mobile'],
				'user_browser'  => $data['user_browser'],
				'from_site'     => $data['from_site'],
				'user_ip'       => $data['user_ip']
			),
			array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
		);
		//echo $wpdb->last_query;
		$lastid = $wpdb->insert_id;

		return $lastid;
	}

}