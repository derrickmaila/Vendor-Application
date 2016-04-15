<?php

/**
 * @author Wesley Hann
 * @date 2014/02/24
 * @time 3:53 PM
 */

abstract class ApplicationsHelper {

	static public function forSwitch( $the_type ) {

		switch ( $the_type ) {

			case 'beescorecard' :

				$actual_name = "BBEEE Scorecard";

			break;

			case 'regdoc' :

				$actual_name = "Registration Document";

			break;

			case 'Company letterhead' :

				$actual_name = "Company Letterhead";

			break;

			case 'vatcert' :

				$actual_name = "Tax Clearance Certificate";

			break;

			case 'beecert' :

				$actual_name = "BEE Ownership Certificate";

			break;

			case 'shareholdercert' :

				$actual_name = "Shareholders Certificate";

			break;

			case 'ISO 9001' :

				$actual_name = "ISO 9001";

			break;

			case 'ISO 14001' :

			$actual_name = "ISO 14001";

			break;

			case 'OSHAS 18001' :

				$actual_name = "OSHAS 18001";

			break;

			case 'Company logo' :

				$actual_name = "Company Logo";

			break;

			case 'bank-details-doc' :

				$actual_name = "Bank Certification of Change to Banking Details";

			break;

		}

		return $actual_name;

	}

	static public function stageSwitch( $stage ) {

		switch ( $stage ) {

			case '1'  : $stage_description = "To be assessed by Procurement Manager"; break;
			case '2'  : $stage_description = "To be approved by Legal"; break;
			case '3'  : $stage_description = "To be accepted by Procurement Executive"; break;
			case '4'  : $stage_description = "To receive final approval from Procurement Manager"; break;
			case '5'  : $stage_description = "Supplier to accept or reject approved application"; break;
			case '6'  : $stage_description = "Procurement Manager to inform CMF"; break;
			case '7'  : $stage_description = "Application Complete"; break;
			case '8'  : $stage_description = "Application Rejected"; break;
			case '9'  : $stage_description = "Principle Approved awaiting further docs from supplier"; break;
			case '10' : $stage_description = "Procurement manager to validate docs"; break;
			case '11' : $stage_description = "CMF Team to list the supplier"; break;
			case '12' : $stage_description = "CMA to notify branches"; break;
			case '13' : $stage_description = "To be asessed by Procurement Manager"; break;
			case '14' : $stage_description = "Procurement Manager to inform CMF"; break;
			case '15' : $stage_description = "CMF Team to list the supplier"; break;

		}

		return $stage_description;

	}

} 
?>