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
<div class="fsms_div">
	<div class="wrap">


		<form action="options.php" method="post">


			<?php

			settings_fields( 'kawuda-settings' );

			do_settings_sections( 'kawuda-settings' );

			?>

			<table>
				<caption>
					<div>Settings</div>
				</caption>


				<tr>
					<td>Number of rows</td>
					<td><input style="width:50px;" type="text" placeholder="25" name="kawuda_no_of_rows"  value="<?php echo esc_attr( get_option( 'kawuda_no_of_rows' ) ); ?>"/></td>

				</tr>

				<tr>
					<td>User tracking save for </td>
					<td><input style="width:150px;" type="text" placeholder="25" name="kawuda_no_of_days"  value="<?php echo esc_attr( get_option( 'kawuda_no_of_days' ) ); ?>"/> days</td>

				</tr>


				<tr>
					<td></td>
					<td><?php submit_button(); ?></td>
				</tr>
			</table>
		</form>
	</div>
</div>
