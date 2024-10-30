<?php

class Kawudas_Tracking {


	public function __construct() {

		if(!isset($_SESSION)){session_start();}
		$this->admin_hooks();
		$this->public_hooks();
		add_action( 'init', [ $this, 'init' ] );

	}

	public function init() {
		register_setting( 'kawuda-settings', 'kawuda_no_of_rows' );
		register_setting( 'kawuda-settings', 'kawuda_no_of_days' );
		$kawuda_no_of_days = time()+36000000;
		if ( esc_attr( get_option( 'kawuda_no_of_days' ) ) > 0 ) {

			$kawuda_no_of_days = intval(esc_attr( get_option( 'kawuda_no_of_days' ) ) ) * 24*60*60;

		} else {
			$kawuda_no_of_days = time()+36000000;
		}
		
		$visit_time = time().rand();
		if(is_null(@$_COOKIE['kawuda_cookie'])) { 		 
			setcookie('kawuda_cookie', $visit_time, time() + (int) $kawuda_no_of_days, "/" , $_SERVER['HTTP_HOST']);		 
		}
	}

	public function admin_hooks() {


		add_action( 'admin_menu', [ $this, 'menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'kawuda_ajax_enqueuer' ] );

	}

	public function public_hooks() {

		add_action( 'rest_api_init', [ $this, 'kawuda_tracking_a_tag_v1' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'kawuda_ajax_enqueuer' ] );


		add_action( 'wp_ajax_view_new_stat', array( $this, 'view_new_stat' ) );
		add_action( 'wp_ajax_nopriv_view_new_stat', array( $this, 'view_new_stat' ) );

		add_action( 'wp_ajax_view_new_user_stat', array( $this, 'view_new_user_stat' ) );
		add_action( 'wp_ajax_nopriv_view_new_user_stat', array( $this, 'view_new_user_stat' ) );

		add_action( 'wp_ajax_view_new_user_link_stat', array( $this, 'view_new_user_link_stat' ) );
		add_action( 'wp_ajax_nopriv_view_new_user_link_stat', array( $this, 'view_new_user_link_stat' ) );


	}

	public function view_new_stat() {
		$id        = sanitize_text_field( ( isset( $_GET['id'] ) ) ? ( $_GET['id'] ) : ( 0 ) );
		$_kawuda   = new Kawuda_Tracking();
		$pageCount = 5;
		if ( esc_attr( get_option( 'kawuda_no_of_rows' ) ) > 0 ) {

			$pageCount = esc_attr( get_option( 'kawuda_no_of_rows' ) );

		} else {
			$pageCount = 5;
		}
		$filters['rend'] = $pageCount;
		$filters['id']   = $id;
		$kawudastats     = $_kawuda->find_new_stat( $filters );
	}

	public function view_new_user_stat() {
		$kawuda_id = sanitize_text_field( ( isset( $_POST['kawuda_id'] ) ) ? ( $_POST['kawuda_id'] ) : ( 0 ) );
		$_kawuda   = new Kawuda_Tracking();
		$pageCount = 5;
		if ( esc_attr( get_option( 'kawuda_no_of_rows' ) ) > 0 ) {

			$pageCount = esc_attr( get_option( 'kawuda_no_of_rows' ) );

		} else {
			$pageCount = 5;
		}
		$filters['rend']      = $pageCount;
		$filters['kawuda_id'] = $kawuda_id;

		$kawudastats = $_kawuda->find_new_user_stat( $filters );
	}

	public function view_new_user_link_stat() {
		 
		$_kawuda   = new Kawuda_Tracking();
		$pageCount = 5;
		if ( esc_attr( get_option( 'kawuda_no_of_rows' ) ) > 0 ) {

			$pageCount = esc_attr( get_option( 'kawuda_no_of_rows' ) );

		} else {
			$pageCount = 5;
		}
		$filters['rend']      = $pageCount;
		 

		$kawudastats = $_kawuda->find_new_user_link_stat( $filters );
	}

	public function kawuda_tracking_a_tag_v1() {
		register_rest_route( 'kawuda/v1', '/hit/(?P<cachebreak>\d+)', array(
			'methods'             => [ 'POST' ],
			'permission_callback' => '__return_true',
			'callback'            => [ $this, 'kawuda_track_v1' ]
		) );
	}

	public function kawuda_track_v1( $data ) {
		$_kawuda   = new Kawuda_Tracking();
		$user_data = $_kawuda->is_existing_user( $data );
		/*echo "<pre>";
		print_r($user_data);
		echo "</pre>";
		die();*/
		if ( @$user_data->id != "" ) {
			$kawuda_id = $user_data->id;
			$_kawuda->kawuda_track_a_tag( $data, $kawuda_id );
		} else {
			$lastid = $_kawuda->kawuda_register_user( $data );
			$_kawuda->kawuda_track_a_tag( $data, $lastid );
		}

		return "kawuda goo";


	}

	public function kawuda_ajax_enqueuer() {
		wp_register_style( 'jquery-ui', KAWUDA_URL_PATH . 'assets/css/jquery-ui.min.css' );
		wp_enqueue_style( 'jquery-ui' );

		wp_register_style( 'kawuda-css', KAWUDA_URL_PATH . 'assets/css/style.css?v=1' );
		wp_enqueue_style( 'kawuda-css' );

		wp_register_script(
			'kawuda-common',
			KAWUDA_URL_PATH . 'assets/js/common.js',
			false,
			'0.1.0',
			false
		);

		wp_register_script(
			'kawuda-loader',
			KAWUDA_URL_PATH . 'assets/js/loader.js',
			false,
			'0.1.0',
			false
		);

		wp_register_script(
			'kawuda-chart',
			KAWUDA_URL_PATH . 'assets/js/chart.js',
			false,
			'0.1.0',
			false
		);
		$page           = sanitize_text_field( ( isset( $_GET['page'] ) ) ? ( $_GET['page'] ) : ( 0 ) );
		$kawuda_view = "not_kawuda";
		if($page == "kawuda_options"){
			$kawuda_view = "kawuda";
		}

		$utm_source   = sanitize_text_field( ( isset( $_GET['utm_source'] ) ) ? ( $_GET['utm_source'] ) : ( '' ) );
		$id           = sanitize_text_field( ( isset( $_GET['id'] ) ) ? ( $_GET['id'] ) : ( 0 ) );
		$utm_medium   = sanitize_text_field( ( isset( $_GET['utm_medium'] ) ) ? ( $_GET['utm_medium'] ) : ( '' ) );
		$utm_campaign = sanitize_text_field( ( isset( $_GET['utm_campaign'] ) ) ? ( $_GET['utm_campaign'] ) : ( '' ) );
		$utm_term     = sanitize_text_field( ( isset( $_GET['utm_term'] ) ) ? ( $_GET['utm_term'] ) : ( '' ) );
		$utm_content  = sanitize_text_field( ( isset( $_GET['utm_content'] ) ) ? ( $_GET['utm_content'] ) : ( '' ) );
		$google_id    = sanitize_text_field( ( isset( $_GET['gclid'] ) ) ? ( $_GET['gclid'] ) : ( '' ) );
		$fb_id        = sanitize_text_field( ( isset( $_GET['fbclid'] ) ) ? ( $_GET['fbclid'] ) : ( '' ) );

		$user_platform = @$_SERVER['HTTP_SEC_CH_UA_PLATFORM'];
		$user_mobile   = @$_SERVER['HTTP_SEC_CH_UA_MOBILE'];
		$from_site     = @$_SERVER['HTTP_REFERER'];
		$user_browser  = @$_SERVER['HTTP_SEC_CH_UA'];
		$user_ip       = @$_SERVER['SERVER_ADDR'];


		wp_localize_script( 'kawuda-common', 'kawuda_js_vars',
			array(
				//To use this variable in javascript use "youruniquejs_vars.ajaxurl"
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				//To use this variable in javascript use "youruniquejs_vars.the_issue_key"

				'current_user_id' => get_current_user_id(),
				'post_id'         => ( is_singular() ? get_the_ID() : 0 ),
				'home_url'        => get_home_url(),


				'utm_source'    => $utm_source,
				'utm_medium'    => $utm_medium,
				'utm_campaign'  => $utm_campaign,
				'utm_term'      => $utm_term,
				'utm_content'   => $utm_content,
				'google_id'     => $google_id,
				'fb_id'         => $fb_id,
				'user_platform' => $user_platform,
				'user_mobile'   => $user_mobile,
				'user_browser'  => $user_browser,
				'from_site'     => $from_site,
				'id'            => $id,
				'user_ip'       => $user_ip,
				'view'       => $kawuda_view,

				'nonceVal' => wp_create_nonce( 'ajax-nonce' )

			)
		);


		wp_enqueue_script( "kawuda-common" );

		if($page == "kawuda_options"){

		$_chart = new Kawuda_Chart();
		$action = sanitize_text_field( ( isset( $_GET['action'] ) ) ? ( $_GET['action'] ) : ( 'list' ) );
		

		if($action == "search"){

			$from_date = sanitize_text_field( $_GET['fromDate'] );
			$to_date   = sanitize_text_field( $_GET['toDate'] );
			if($from_date == ""){
				$from_date  = date('Y-m-d', strtotime("-7 day"));		 
			}
			if($to_date == ""){				 
				$to_date = date('Y-m-d', strtotime("+1 day"));
			}
			 
			$filters['from_date'] = $from_date ;
			$filters['to_date'] = $to_date;
		}else{
			$filters['from_date'] = date('Y-m-d', strtotime("-7 day"));	
			$filters['to_date'] = date('Y-m-d', strtotime("+1 day"));
		}
		
		/*echo "<pre>";
		print_r($filters);
		echo "</pre>";
		die();*/
		$filters['utm_key'] = 'utm_source';
		$date_range = $filters['utm_key']." FROM: ".$filters['from_date']." TO: ".$filters['to_date'];

		 
		$date_range_x = "Utm";
		$date_range_y = "UtmCount";		 
		$utm_output_arr = $_chart->get_utm_source_pie_chart($filters);		 
		foreach($utm_output_arr as $key=>$val) {
		    $utm_output[] = [$key, $val];
		}
		$utm_output_row  = 0;
		if(!empty($utm_output)){
			$utm_output_row = json_encode($utm_output);
		}

		$filters['utm_key'] = 'utm_medium';		
		$date_range_campain = $filters['utm_key']." FROM: ".$filters['from_date']." TO: ".$filters['to_date'];
		$date_range_x = "Utm";
		$date_range_y = "UtmCount";		 
		$utm_output_medium_arr = $_chart->get_utm_source_pie_chart($filters);		 
		foreach($utm_output_medium_arr as $key=>$val) {
		    $utm_output_row_medium[] = [$key, $val];
		}
		$utm_output_row_me  = 0;
		if(!empty($utm_output_row_medium)){
			$utm_output_row_me= json_encode($utm_output_row_medium);
		}

		$filters['utm_key'] = 'utm_campaign';		
		$date_range_medium = $filters['utm_key']." FROM: ".$filters['from_date']." TO: ".$filters['to_date'];
		$date_range_x = "Utm";
		$date_range_y = "UtmCount";		 
		$utm_output_campain_arr = $_chart->get_utm_source_pie_chart($filters);		 
		foreach($utm_output_campain_arr as $key=>$val) {
		    $utm_output_row_campain[] = [$key, $val];
		}
		$utm_output_row_ca  = 0;
		if(!empty($utm_output_row_campain)){
			$utm_output_row_ca= json_encode($utm_output_row_campain);
		}
		
		/*echo "<pre>";
		print_r($utm_output_row_me);
		echo "</pre>";
		die(); */
	 

		wp_localize_script( 'kawuda-chart', 'kawuda_chart_vars',
			array(
				//To use this variable in javascript use "youruniquejs_vars.ajaxurl"
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				//To use this variable in javascript use "youruniquejs_vars.the_issue_key"
				'id'      => intval( $id ),
				'current_user_id' => get_current_user_id(),
				'view'       => $kawuda_view,
				'date_range'      => ( string )( $date_range ),
				'date_range_medium'      => ( string )( $date_range_medium ),	
				'date_range_campain'      => ( string )( $date_range_campain ),	
				'date_range_x'      => ( string )( $date_range_x ),
				'date_range_y'      => ( string )( $date_range_y ),
				'utm_output'      =>  ( $utm_output_row ),
				'utm_output_row_me'      =>  ( $utm_output_row_me ),
				'utm_output_row_ca'      =>  ( $utm_output_row_ca ),
				'nonceVal' => wp_create_nonce('ajax-nonce')

			)
		);

		wp_enqueue_script( "kawuda-chart" );
		wp_enqueue_script( "kawuda-loader" );

		wp_enqueue_script( 'jquery-ui-datepicker' );
	}

	}

	
	// main menu
	public function menu() {
		$kawuda_caps = current_user_can( 'manage_options' ) ? 'manage_options' : 'manage_options';
		add_menu_page( 'KAWUDA', 'Kawuda', $kawuda_caps, "kawuda_options", [
			$this,
			'dashboard'
		], KAWUDA_URL_PATH . 'assets/icon.png' );

		add_submenu_page( 'kawuda_options', 'Dashboard', 'Dashboard', $kawuda_caps, "kawuda_options", [
			$this,
			'dashboard'
		] );

		add_submenu_page( 'kawuda_options', 'Setting', 'Setting', $kawuda_caps, "kawuda_setting", [
			$this,
			'setting'
		] );

	}

	public function setting() {
		include_once KAWUDA_PATH . "/views/setting.php";
	}

	public function dashboard() {
		$_kawuda = new Kawuda_Tracking();

		$action = sanitize_text_field( ( isset( $_GET['action'] ) ) ? ( $_GET['action'] ) : ( 'list' ) );


		switch ( $action ) {
			case 'list':
			default:

				$pagei     = sanitize_text_field( ( isset( $_GET['pagei'] ) ) ? ( $_GET['pagei'] ) : ( 0 ) );
				$pageCount = 5;
				if ( esc_attr( get_option( 'kawuda_no_of_rows' ) ) > 0 ) {

					$pageCount = esc_attr( get_option( 'kawuda_no_of_rows' ) );

				} else {
					$pageCount = 5;
				}
				if ( $pagei == "" || $pagei == 0 ) {
					$P          = 1;
					$offSetPage = 0;
				} else {
					$P          = $pagei;
					$offSetPage = $pagei - 1;
				}
				$rstart = $offSetPage * $pageCount;
				$rend   = $pageCount;

				$dir = sanitize_text_field( ( isset( $_GET['dir'] ) ) ? ( $_GET['dir'] ) : ( 'DESC' ) );
				if ( $dir != 'ASC' and $dir != 'DESC' ) {
					$dir = 'ASC';
				}
				$odir  = ( $dir == 'ASC' ) ? 'DESC' : 'ASC';
				$ob    = "id";
				$obGet = sanitize_text_field( ( isset( $_GET['ob'] ) ) ? ( $_GET['ob'] ) : ( $ob ) );
				if ( ! empty( $obGet ) ) {

					if ( $obGet == "id" ) {
						$ob = "id";
					}
					if ( $obGet == "sku" ) {
						$ob = "sku";
					}
					if ( $obGet == "description" ) {
						$ob = "description";
					} else {
						$ob = "id";
					}


					$orderby = "ORDER BY " . sanitize_text_field( $ob ) . ' ' . $dir;
				}


				$filters     = array(
					"rstart"    => $rstart,
					"rend"      => $rend,
					"ob"        => $ob,
					"dir"       => $dir,
					"getOb"     => $obGet,
					"searchKey" => "",
					"nonce"     => "",
					"action"    => "list"
				);
				$kawudastats = $_kawuda->find( $filters );
				$totalCount  = $_kawuda->getTotalCount( $filters );

				include_once KAWUDA_PATH . "/views/dashboard-view-admin.php";

				break;

			case 'search':
				if ( isset( $_GET['nonce'] ) && wp_verify_nonce( $_GET['nonce'], 'kawuda_stat_search' ) ) {
					$fromDate = sanitize_text_field( $_GET['fromDate'] );
					$toDate   = sanitize_text_field( $_GET['toDate'] );

					$searchKey = sanitize_text_field( ( isset( $_GET['searchKey'] ) ) ? ( $_GET['searchKey'] ) : ( "" ) );

					$pagei     = sanitize_text_field( ( isset( $_GET['pagei'] ) ) ? ( $_GET['pagei'] ) : ( 0 ) );
					$pageCount = 5;
					if ( esc_attr( get_option( 'kawuda_no_of_rows' ) ) > 0 ) {

						$pageCount = esc_attr( get_option( 'kawuda_no_of_rows' ) );

					} else {
						$pageCount = 5;
					}
					if ( $pagei == "" || $pagei == 0 ) {
						$P          = 1;
						$offSetPage = 0;
					} else {
						$P          = $pagei;
						$offSetPage = $pagei - 1;
					}
					$rstart = $offSetPage * $pageCount;
					$rend   = $pageCount;
					$dir    = sanitize_text_field( ( isset( $_GET['dir'] ) ) ? ( $_GET['dir'] ) : ( 'DESC' ) );
					if ( $dir != 'ASC' and $dir != 'DESC' ) {
						$dir = 'ASC';
					}
					$odir  = ( $dir == 'ASC' ) ? 'DESC' : 'ASC';
					$ob    = "id";
					$obGet = sanitize_text_field( ( isset( $_GET['ob'] ) ) ? ( $_GET['ob'] ) : ( $ob ) );
					if ( ! empty( $obGet ) ) {

						if ( $obGet == "id" ) {
							$ob = "id";
						}
						if ( $obGet == "sku" ) {
							$ob = "sku";
						}
						if ( $obGet == "description" ) {
							$ob = "description";
						} else {
							$ob = "id";
						}


						$orderby = "ORDER BY " . sanitize_text_field( $ob ) . ' ' . $dir;
					}


					$filters     = array(
						"rstart"    => $rstart,
						"rend"      => $rend,
						"ob"        => $ob,
						"dir"       => $dir,
						"getOb"     => $obGet,
						"searchKey" => $searchKey,
						"nonce"     => $_GET['nonce'],
						"fromDate"  => $fromDate,
						"toDate"    => $toDate,
						"action"    => $action
					);
					$kawudastats = $_kawuda->find( $filters );
					$totalCount  = $_kawuda->getTotalCount( $filters );

					include_once KAWUDA_PATH . "/views/dashboard-view-admin.php";
				}

				break;
			case 'view':
				$kawuda_id = intval( $_GET['id'] );
				$pagei     = sanitize_text_field( ( isset( $_GET['pagei'] ) ) ? ( $_GET['pagei'] ) : ( 0 ) );
				$pageCount = 5;
				if ( esc_attr( get_option( 'kawuda_no_of_rows' ) ) > 0 ) {

					$pageCount = esc_attr( get_option( 'kawuda_no_of_rows' ) );

				} else {
					$pageCount = 5;
				}
				if ( $pagei == "" || $pagei == 0 ) {
					$P          = 1;
					$offSetPage = 0;
				} else {
					$P          = $pagei;
					$offSetPage = $pagei - 1;
				}
				$rstart = $offSetPage * $pageCount;
				$rend   = $pageCount;

				$dir = sanitize_text_field( ( isset( $_GET['dir'] ) ) ? ( $_GET['dir'] ) : ( 'DESC' ) );
				if ( $dir != 'ASC' and $dir != 'DESC' ) {
					$dir = 'ASC';
				}
				$odir  = ( $dir == 'ASC' ) ? 'DESC' : 'ASC';
				$ob    = "id";
				$obGet = sanitize_text_field( ( isset( $_GET['ob'] ) ) ? ( $_GET['ob'] ) : ( $ob ) );
				if ( ! empty( $obGet ) ) {

					if ( $obGet == "id" ) {
						$ob = "id";
					}
					if ( $obGet == "sku" ) {
						$ob = "sku";
					}
					if ( $obGet == "description" ) {
						$ob = "description";
					} else {
						$ob = "id";
					}


					$orderby = "ORDER BY " . sanitize_text_field( $ob ) . ' ' . $dir;
				}


				$filters     = array(
					"rstart"    => $rstart,
					"rend"      => $rend,
					"ob"        => $ob,
					"dir"       => $dir,
					"getOb"     => $obGet,
					"searchKey" => "",
					"nonce"     => "",
					"action"    => "view",
					"kawuda_id" => $kawuda_id
				);
				$kawudastats = $_kawuda->findByid( $filters );
				$totalCount  = $_kawuda->getTotalCountById( $filters );

				include_once KAWUDA_PATH . "/views/dashboard-view-user.php";
				break;

		}


	}
}
