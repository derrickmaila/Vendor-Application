<h1>Authorizations</h1>
<table class="display-table">
		<tr>
			<th>Username</th>
			<th>Date and Time</th>
			<th>Stage</th>
		</tr>
		<?php
		if ( $audits ) {

			foreach ( $audits as $audit ) {
				?>

				<tr class="application" data-name="<?php echo strtolower( $audit->vendorname ); ?>">
					
					<td><?= $audit->username ?></td>
					<td><?= $audit->datelogged ?></td>
					<td><?= $audit->description ?></td>

				</tr>
			<?php
			}
		} else {
			?>
			<tr>
				<td colspan="6">
					No results found
				</td>
			</tr>
		<?php } ?>
	</table>
	<h1>Mails</h1>
<table class="display-table">
		<tr>
			<th>Username that initiated action</th>
			<th>Date and Time</th>
			<th>Mail body</th>
			<th>Sent to</th>
		</tr>
		<?php
		if ( $mails) {

			foreach ( $mails as $mail ) {
				?>

				<tr class="application">
					
					<td><?= $mail->username ?></td>
					<td><?= $mail->date ?></td>
					<td><?= $mail->body ?></td>
					<td><?= $mail->sentto ?></td>

				</tr>
			<?php
			}
		} else {
			?>
			<tr>
				<td colspan="6">
					No results found
				</td>
			</tr>
		<?php } ?>
	</table>

	<h1>Reassignment Log</h1>
<table class="display-table">
		<tr>
			<th>Assigned from</th>
			<th>Assigned to</th>
			<th>Time</th>
			
		</tr>
		<?php
		if ( $logs) {

			foreach ( $logs as $log ) {
				?>

				<tr class="application">
					
					<td><?= $log->usernamefrom ?></td>
					<td><?= $log->usernameto ?></td>
					<td><?= $log->datelogged ?></td>
					

				</tr>
			<?php
			}
		} else {
			?>
			<tr>
				<td colspan="6">
					No results found
				</td>
			</tr>
		<?php } ?>
	</table>