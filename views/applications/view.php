<?php require_once( dirname( __FILE__ ) . '/helpers/applications.php' ); ?>

<table class="display-table">
<!--<h2>View Application</h2>-->
<!--<br />-->
<tbody>
<?php if($application->accountcode) { ?>
<tr>
	<td class="label">Account code</td>
	<td><?= $application->accountcode ?></td>
</tr>
<?php } ?>
<tr>
	<td class="label">Supplier name</td>
	<td><?= $application->suppliername ?></td>
</tr>
<tr>
	<td class="label">Supplier address</td>
	<td><?= $application->supplieraddress ?></td>
</tr>

<tr>
	<td class="label">Telephone number</td>
	<td><?= $application->telephonenumber ?></td>
</tr>
<tr>
	<td class="label">Fax number</td>
	<td><?= $application->faxnumber ?></td>
</tr>
<tr>
	<td class="label">Legal entity name</td>
	<td><?= $application->legalentityname ?></td>
</tr>
<tr>
	<td class="label">Legal entity type</td>
	<td><?= $application->legalentitytype ?></td>
</tr>
<tr>
	<td class="label">Reg no</td>
	<td><?= $application->regno ?></td>
</tr>
<tr>
	<td class="label">Registration country</td>
	<td><?= $application->regcountry ?></td>
</tr>
<tr>
	<td class="label">Email address</td>
	<td><?= $application->emailaddress ?></td>
</tr>

<tr>
	<td class="label">Cell number</td>
	<td><?= $application->cellnumber ?></td>
</tr>

<tr>
	<td class="label">Post address</td>
	<td><?= $application->postaddress ?></td>
</tr>

<tr>
	<td class="label">Annual turnover</td>
	<td><?= $application->annualturnover ?></td>
</tr>
<tr>
	<td class="label">Vat number</td>
	<td><?= $application->vatnumber ?></td>
</tr>

<tr>
	<td colspan="2">
		<h1>Sales Representative Contact Details</h1>
	</td>
</tr>
<tr>
	<td class="label">Sales title</td>
	<td><?= $application->salestitle ?></td>
</tr>
<tr>
	<td class="label">Sales communication method</td>
	<td><?= $application->salescommethod ?></td>
</tr>
<tr>
	<td class="label">Sales name</td>
	<td><?= $application->salesfname ?></td>
</tr>
<tr>
	<td class="label">Sales surname</td>
	<td><?= $application->salessname ?></td>
</tr>
<tr>
	<td class="label">Sales cell number</td>
	<td><?= $application->salescellnumber ?></td>
</tr>
<tr>
	<td class="label">Sales telephone number</td>
	<td><?= $application->salestelnumber ?></td>
</tr>
<tr>
	<td class="label">Sales email</td>
	<td><?= $application->salesemail ?></td>
</tr>

	<td colspan="2">
		<h1>Finance/Credit Contact Details</h1>
	</td>
</tr>
<tr>
	<td class="label">Finance title</td>
	<td><?= $application->financetitle ?></td>
</tr>
<tr>
	<td class="label">Finance communication method</td>
	<td><?= $application->financecommethod ?></td>
</tr>
<tr>
	<td class="label">Finance name</td>
	<td><?= $application->financefname ?></td>
</tr>
<tr>
	<td class="label">Finance surname</td>
	<td><?= $application->financesname ?></td>
</tr>
<tr>
	<td class="label">Finance cell number</td>
	<td><?= $application->financecellnumber ?></td>
</tr>
<tr>
	<td class="label">Finance telephone number</td>
	<td><?= $application->financetelnumber ?></td>
</tr>
<tr>
	<td class="label">Finance email</td>
	<td><?= $application->financeemail ?></td>
</tr>
<tr>
	<td colspan="2">
		<h1>Support Contact Details</h1>
	</td>
</tr>
<tr>
	<td class="label">Support title</td>
	<td><?= $application->supporttitle ?></td>
</tr>
<tr>
	<td class="label">Support communication method</td>
	<td><?= $application->supportcommethod ?></td>
</tr>
<tr>
	<td class="label">Support name</td>
	<td><?= $application->supportfname ?></td>
</tr>
<tr>
	<td class="label">Support surname</td>
	<td><?= $application->supportsname ?></td>
</tr>
<tr>
	<td class="label">Support cell number</td>
	<td><?= $application->supportcellnumber ?></td>
</tr>
<tr>
	<td class="label">Support telephone number</td>
	<td><?= $application->supporttelnumber ?></td>
</tr>
<tr>
	<td class="label">Support email</td>
	<td><?= $application->supportemail ?></td>
</tr>

<tr>
	<td colspan="2">
		<h1>Banking Details</h1>
	</td>
</tr>
<tr>
	<td class="label">Bank name</td>
	<td><?= $application->bankname ?></td>
</tr>
<tr>
	<td class="label">Bank account number</td>
	<td><?= $application->bankaccnumber ?></td>
</tr>
<tr>
	<td class="label">Bank branch name</td>
	<td><?= $application->bankbranchname ?></td>
</tr>
<tr>
	<td class="label">Bank branch code</td>
	<td><?= $application->bankbranchcode ?></td>
</tr>
<tr>
	<td class="label">Bank account type</td>
	<td><?= $application->bankacctype ?></td>
</tr>
<tr>
	<td class="label">Bank account holder name</td>
	<td><?= $application->bankaccholdername ?></td>
</tr>

<tr>
	<td colspan="2">
		<h1>Business Categories</h1>
	</td>
</tr>
<tr>
	<td class="label">Business description</td>
	<td><?= $application->businessdscr ?></td>
</tr>
<tr>
	<td class="label">Business industry</td>
	<td><?= $application->businessindustry ?></td>
</tr>
<tr>
	<td class="label">Business market segment</td>
	<td><?= $application->businessmarketsegment ?></td>
</tr>


<tr>
	<td colspan="2">
		<h1>BEE Details</h1>
	</td>
</tr>
<tr>
	<td class="label">Bee scorecard availibility</td>
	<td><?= $application->beescorecardavail ?></td>
</tr>
<tr>
	<td class="label">Bee score card rating</td>
	<td><?= $application->beesscorecardrating ?></td>
</tr>
<tr>
	<td class="label">Bee expiry date</td>
	<td><?= $application->beeexpirydate ?></td>
</tr>

<tr>
	<td colspan="2"><h1>Supporting Documentation</h1></td>
</tr>

<tr>
	<td colspan="2">
		<table class="display-table">
			<tr>
				<th>Filename</th>
				<th>Type</th>
				
				<th>Supporting File For</th>
				<th>Download</th>
			</tr>

			<?php
			$uploads = json_decode( $application->uploads );

			if ( $uploads ) :

				foreach ( $uploads as $upload ) :
					$filename = substr($upload->uniqfile, 0,strpos($upload->uniqfile, "."));
					?>

					<tr>
						<td><?= $upload->name ?></td>
						<td><?= $upload->type ?></td>
						
						<td><?php echo ApplicationsHelper::forSwitch( $upload->thetype ); ?></td>
						<td>
						<?php
							$ext = substr($upload->name, strpos($upload->name, ".")+1);

						?>
							<a href="/assets/uploads/<?= $filename ?>.<?=$ext?>" target="_blank">Download</a>
						</td>
					</tr>

				<?php endforeach;

			else : ?>

				<tr>

					<td colspan="5">No files found</td>

				</tr>;

			<?php endif; ?>

		</table>
	</td>

</tr>

</tbody>
</table>
