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

	<table>
		<caption>
			<div>Kawuda User Real Time Tacking</div>
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
		<tbody id="new_stat_user_block">
		</tbody>
	</table>
	<table>
		<caption>
			<div>Kawuda user utm stat system</div>
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
		<tbody>
		<?php
		foreach ( $kawudastats as $kawudastat ):
			?>
			<tr>
				<td><?php echo esc_attr( $kawudastat->id ); ?></td>
				<td><?php echo esc_attr( $kawudastat->action_datetime ); ?></td>
				<td><?php echo esc_attr( $kawudastat->from_url ); ?>  </td>
				<td><?php echo esc_attr( $kawudastat->to_url ); ?> </td>
				<td><?php echo esc_attr( $kawudastat->tracking_item_type ); ?> </td>
				<td><?php echo esc_attr( $kawudastat->tracking_item ); ?> </td>


			</tr>
		<?php endforeach; ?>
		<?php

		$lead_pagi = "";
		$pageText  = 'Page:';
		if ( $totalCount > $pageCount ) {
			$pageText = 'Page:';
		}
		$lead_pagi .= "<tr>";
		$lead_pagi .= "<td colspan='12' class='rgt'>&nbsp;" . $pageText; 
		
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
				$lead_pagi .= "<span class='cPointer'><a href='admin.php?page=kawuda_options&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&id=" . $filters['kawuda_id'] . "'>1</a></span>";
			} else {
				$lead_pagi .= "<span class='cPointer'><a href='admin.php?page=kawuda_options&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "&id=" . $filters['kawuda_id'] . "'>1</a></span>";
			}


			$lead_pagi .= "...&nbsp;";
		}

		for ( $i = $startpage; $i <= $endpage; $i ++ ) {
			if ( $page == $i ) {
				$lead_pagi .= "<span class='cPointer' style='font-weight: bold;' ><a href='admin.php?page=kawuda_options&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "&id=" . $filters['kawuda_id'] . "'>" . $i . "</a></span>";
			} else {
				$lead_pagi .= "<span class='cPointer' ><a href='admin.php?page=kawuda_options&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "&id=" . $filters['kawuda_id'] . "'>" . $i . "</a></span>";
			}


		}
		if ( $endpage < $totalPageCount ) {
			$lead_pagi .= "...&nbsp;";
			if ( $page == $totalPageCount ) {
				$lead_pagi .= "&nbsp;" . $totalPageCount . "</span>";
			} else {
				$lead_pagi .= "&nbsp;<span class='cPointer'><a href='admin.php?page=kawuda_options&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "&id=" . $filters['kawuda_id'] . "'>" . $totalPageCount . "</a></span>&nbsp;";
			}

		}

		$lead_pagi .= "</td>";
		$lead_pagi .= "</tr>";
		echo wp_kses_post( $lead_pagi );
		?>
		</tbody>
	</table>
</div>
