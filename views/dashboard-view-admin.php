<?php
/**
 * Exit if accessed directly!
 *
 * @package           Kawuda
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<?php

$nonce = wp_create_nonce( 'kawuda_stat_search' );

?>

<div class="kawuda_div">
	<!--Div that will hold the pie chart-->
	<table>
		<caption>Last 7 days look like</caption>
		<tr>
			<td style="background-color: #ccc;"><div id="utm_source_chart_div"></div></td>
			<td style="background-color: #ccc;"><div id="utm_medium_chart_div"></div></td>
			<td style="background-color: #ccc;"><div id="utm_campain_chart_div"></div></td>
		</tr>
	</table>
    
    
    
	<table>
		<caption>
			<div>Kawuda User Link Click - Real Time Tracking</div>
		</caption>
		<thead>
		<tr>
			<th>Id</th>
			<th>Visit time</th>
			<th>From</th>
			<th>To</th>
			<th>Type</th>
			<th>Item</th>


		</tr>
		</thead>
		<tbody id="new_stat_user_link_block">
		</tbody>
	</table>

	<table>
		<caption>
			<div>Kawuda User Real Time Tracking</div>
		</caption>
		<thead>
		<tr>
			<th>Id</th>
			<th>Name</th>
			<th>IP</th>
			<th>Visit time</th>
			<th>From</th>
			<th>UTM source</th>
			<th>UTM medium</th>
			<th>UTM campaign</th>
			<th>UTM term</th>
			<th>UTM content</th>
			<th>Google id</th>
			<th>Facebook id</th>


		</tr>
		</thead>
		<tbody id="new_stat_block">
		</tbody>
	</table>


	<form method="GET" action="">
		<table>
			<caption>Search
			</caption>
			<tr>
				<td>Month from</td>
				<td colspan=""><input type="text" autocomplete="off" id="fromDate" name="fromDate"
				                      value="<?php echo esc_attr( @$fromDate ); ?>"></td>
			</tr>
			<tr>
				<td>Month to</td>
				<td colspan=""><input type="text" autocomplete="off" id="toDate" name="toDate"
				                      value="<?php echo esc_attr( @$toDate ); ?>">
				</td>
			</tr>
			<tr>
				<td>Search</td>
				<td>

					<input type="hidden" name="page" value="kawuda_options">
					<input type="hidden" name="action" value="search">
					<input type="hidden" name="nonce" value="<?php echo esc_attr( $nonce ); ?>">
					<input id="searchKey" type="text" name="searchKey" value="<?php echo esc_attr( @$filters['searchKey'] ); ?>">


				</td>
			</tr>
			<tr>
				<td>

				</td>
				<td>
					<input type="submit" name="search" value="Search">
					<a href="admin.php?page=kawuda_options"><input type="Button" name="search" value="Reset"></a>
				</td>
			</tr>
		</table>
	</form>

	<table>
		<caption>
			<div>Kawuda utm stat system</div>
		</caption>
		<thead>
		<tr>
			<th>Id</th>
			<th>Name</th>
			<th>IP</th>
			<th>Visit time</th>
			<th>From</th>
			<th>UTM source</th>
			<th>UTM medium</th>
			<th>UTM campaign</th>
			<th>UTM term</th>
			<th>UTM content</th>
			<th>Google id</th>
			<th>Facebook id</th>


		</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $kawudastats as $kawudastat ) :
			?>
			<tr>
				<td>
					<a href="admin.php?page=kawuda_options&action=view&id=<?php echo esc_attr( $kawudastat->id ); ?>"><?php echo esc_attr( $kawudastat->id ); ?></a>
				</td>
				<td><?php echo esc_attr( $kawudastat->user_nicename ); ?></td>
				<td><?php echo esc_attr( $kawudastat->user_ip ); ?></td>
				<td><?php echo esc_attr( $kawudastat->visit_datetime ); ?></td>
				<td><?php echo esc_attr( $kawudastat->from_site ); ?>  </td>
				<td><?php echo esc_attr( $kawudastat->utm_source ); ?> </td>
				<td><?php echo esc_attr( $kawudastat->utm_medium ); ?> </td>
				<td><?php echo esc_attr( $kawudastat->utm_campaign ); ?> </td>
				<td><?php echo esc_attr( $kawudastat->utm_term ); ?> </td>
				<td><?php echo esc_attr( $kawudastat->utm_content ); ?> </td>
				<td><?php echo esc_attr( $kawudastat->google_id ); ?> </td>
				<td><?php echo esc_attr( $kawudastat->fb_id ); ?> </td>

			</tr>
		<?php endforeach; ?>
		<?php

		$lead_pagi = '';
		$pageText  = 'Page:';
		if ( $totalCount > $pageCount ) {
			$pageText = 'Page:';
		}
		$lead_pagi .= '<tr>';
		$lead_pagi .= '<td colspan="12" class="rgt">&nbsp;' . $pageText;

		//echo $acPageCount;
		$acPageCount    = ceil( ( $totalCount / $pageCount ) );
		$totalPageCount = $acPageCount;
		$page           = (int) ( $P );
		$urlPagi        = "";

		if ( ( $page - 9 ) > 0 ) {
			$startpage = $page - 9;
		} else {
			$startpage = 1;
		}


		if ( ( $page + 9 ) < $totalPageCount ) {
			$endpage = $page + 9;
		} else {
			$endpage = $totalPageCount;
		}


		if ( $startpage > 1 ) {
			if ( $page == 1 ) {
				$lead_pagi .= "<span class='cPointer'><a href='admin.php?page=kawuda_options&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "'>1</a></span>";
			} else {
				$lead_pagi .= "<span class='cPointer'><a href='admin.php?page=kawuda_options&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>1</a></span>";
			}


			$lead_pagi .= "...&nbsp;";
		}

		for ( $i = $startpage; $i <= $endpage; $i ++ ) {
			if ( $page == $i ) {
				$lead_pagi .= "<span class='cPointer' style='font-weight: bold;' ><a href='admin.php?page=kawuda_options&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>" . $i . "</a></span>";
			} else {
				$lead_pagi .= "<span class='cPointer' ><a href='admin.php?page=kawuda_options&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>" . $i . "</a></span>";
			}


		}
		if ( $endpage < $totalPageCount ) {
			$lead_pagi .= "...&nbsp;";
			if ( $page == $totalPageCount ) {
				$lead_pagi .= "&nbsp;" . $totalPageCount . "</span>";
			} else {
				$lead_pagi .= "&nbsp;<span class='cPointer'><a href='admin.php?page=kawuda_options&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>" . $totalPageCount . "</a></span>&nbsp;";
			}

		}

		$lead_pagi .= "</td>";
		$lead_pagi .= "</tr>";
		echo wp_kses_post( $lead_pagi );
		?>
		</tbody>
	</table>
</div>
 