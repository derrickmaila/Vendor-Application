<?php

/**
 * @author by wesleyhann
 * @date 2014/01/28
 * @time 10:31 AM
 */
class MailBody
{

	private $body;
	public $header_style = 'style="font-family: Arial, sans-serif; color: rgb(6, 63, 124); font-weight: normal; "';
	public $paragraph_style = 'style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff;"';
	public $list_style = 'style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff; margin-bottom: 5px;"';
	public $link_style = 'style=" text-decoration: none; display: block; background-color: rgb(6, 63, 124); padding: 15px 25px; color:  #fff; font-weight: 800; width:  125px; text-align: center; text-shadow: 0 -1px 0 rgba(0,0,0,0.3);"';
	public $inline_link = 'style="color: rgb(6, 63, 124); font-weight: normal; "';

	public function __construct( $stage, $data = null )
	{

		switch ( $stage ) {

			case 'details-change' :

				$template = $this->bankDetailsChange( $data );

				break;

			case 'application-complete' :

				$template = $this->bankingDetailsToHarry();

				break;

			case 'internal-rejection' :

				$template = $this->internalRejection( $data );

			break;

			case 1 :

				$template = $this->newApplicant();

				break;

			case 2 :

				$template = $this->genericTemplate( $data );

				break;

			case 7 :

				$template = $this->applicationComplete( $data );

				break;

			case 8 :

				$template = $this->applicationRejected( $data );

				break;

			case 5 :

				$template = $this->supplierAgreement( $data );

				break;

			case 9 :

				$template = $this->principleApproved( $data );

				break;

			case 10 :

				$template = $this->validateDocuments( $data );

				break;

			case 16 :

				$template = $this->catalogueRejected( $data );

				break;

			default :

				$template = $this->genericTemplate( $data );

				break;

		}

		$this->setBody( $template );

	}
	public function getName($email) {
		$name = substr($email, 0,strpos($email, "@"));
		$aName = explode(".", $name);
		return ucwords($aName[0])." ".ucwords($aName[1]);
	}

	public function genericTemplate( $data )
	{

		//echo '<pre>' . print_r( $data , true ) .'</pre>';

		
		$data->pm_name = $this->getName($data->pm_email);


		$body = '<html><body style="background: #fafafa;">';
		
		$body .= '<p ' . $this->paragraph_style . '>The following Vendor&lsquo;s application has progressed to the next stage. Please review the application and action accordingly.</p>';
		$body .= '<ul style="list-style: none; padding:0;" >';
		$body .= '<li ' . $this->list_style . '>Application Ref: #00' .  $data->application->control . '</li>';
		$body .= '<li ' . $this->list_style . '>Trade name: ' . $data->application->suppliername . '</li>';
		$body .= '<li ' . $this->list_style . '>Date Application Actioned: ' . date( 'jS M Y - H:i' ) . '</li>';
		$body .= '<li ' . $this->list_style . '>Stage: ' . $data->stage_no->stageno . '</li>';
		$body .= '<li ' . $this->list_style . '>SLA Response time: ' . $data->stage_time . ' hours</li>';
		$body .= '<li ' . $this->list_style . '>Status: ' . $data->stage_description . '</li>';
		$body .= '</ul>';
		$body .= '<h4>The Procurement Manager Assigned to this supplier:</h4>';
		$body .= '<p ' . $this->paragraph_style . '>Procurement manager name: ' . $data->pm_name . '</p>';
		$body .= '<p ' . $this->paragraph_style . '>Email Address: <a ' . $this->inline_link . ' href="mailto:' . $data->pm_email . '">' . $data->pm_email . '</a></p>';
		$body .= '<a ' . $this->inline_link . ' target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a>';
		$body .= '<p ' . $this->paragraph_style . '>Best Regards, <br />';
		$body .= '<p ' . $this->paragraph_style . '>Vendor Portal Admin <br />';
		$body .= 'Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		return $body;


	}
	public function productUpdate( $data )
	{

		//echo '<pre>' . print_r( $data , true ) .'</pre>';

		
		$data->pm_name = $this->getName($data->pm_email);


		$body = '<html><body style="background: #fafafa;">';
		
		$body .= '<p ' . $this->paragraph_style . '>The following Vendor has updated their <b>product listing</b>. Please review and action accordingly.</p>';
		$body .= '<ul style="list-style: none; padding:0;" >';
		$body .= '<li ' . $this->list_style . '>Application Ref: #00' .  $data->application->control . '</li>';
		$body .= '<li ' . $this->list_style . '>Trade name: ' . $data->application->suppliername . '</li>';
		$body .= '<li ' . $this->list_style . '>Date Application Actioned: ' . date( 'jS M Y - H:i' ) . '</li>';

		$body .= '</ul>';
		$body .= '<h4>The Procurement Manager Assigned to this supplier:</h4>';
		$body .= '<p ' . $this->paragraph_style . '>Procurement manager name: ' . $data->pm_name . '</p>';
		$body .= '<p ' . $this->paragraph_style . '>Email Address: <a ' . $this->inline_link . ' href="mailto:' . $data->pm_email . '">' . $data->pm_email . '</a></p>';
		$body .= '<a ' . $this->inline_link . ' target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a>';
		$body .= '<p ' . $this->paragraph_style . '>Best Regards, <br />';
		$body .= '<p ' . $this->paragraph_style . '>Vendor Portal Admin <br />';
		$body .= 'Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		return $body;


	}
	public function bankingDetailsUpdate( $data )
	{

		//echo '<pre>' . print_r( $data , true ) .'</pre>';

		
		$data->pm_name = $this->getName($data->pm_email);


		$body = '<html><body style="background: #fafafa;">';
		
		$body .= '<p ' . $this->paragraph_style . '>The following Vendor has updated their <b>banking details</b>. Please review the application and action accordingly.</p>';
		$body.="<h1>Details changed:</h1>";
		$details = $data->bankdetails;
		//print_r($details);

		$body .= '<p ' . $this->paragraph_style . '>Bank Name: <b>'.$details[bankname].'</b></p>';
		$body .= '<p ' . $this->paragraph_style . '>Acc Number: <b>'.$details[bankaccnumber].'</b></p>';
		$body .= '<p ' . $this->paragraph_style . '>Branch Name: <b>'.$details[bankbranchname].'</b></p>';
		$body .= '<p ' . $this->paragraph_style . '>Branch Code: <b>'.$details[bankbranchcode].'</b></p>';
		$body .= '<p ' . $this->paragraph_style . '>Acc Type: <b>'.$details[bankacctype].'</b></p>';
		$body .= '<p ' . $this->paragraph_style . '>Acc Holder: <b>'.$details[bankaccholdername].'</b></p>';
		$body .= '<p ' . $this->paragraph_style . '>Swift: <b>'.$details[swift].'</b></p>';

		$body .= '<ul style="list-style: none; padding:0;" >';
		$body .= '<li ' . $this->list_style . '>Application Ref: #00' .  $data->application->control . '</li>';
		$body .= '<li ' . $this->list_style . '>Trade name: ' . $data->application->suppliername . '</li>';
		$body .= '<li ' . $this->list_style . '>Date Application Actioned: ' . date( 'jS M Y - H:i' ) . '</li>';
	
		$body .= '</ul>';
		$body .= '<h4>The Procurement Manager Assigned to this supplier:</h4>';
		$body .= '<p ' . $this->paragraph_style . '>Procurement manager name: ' . $data->pm_name . '</p>';
		$body .= '<p ' . $this->paragraph_style . '>Email Address: <a ' . $this->inline_link . ' href="mailto:' . $data->pm_email . '">' . $data->pm_email . '</a></p>';
		$body .= '<a ' . $this->inline_link . ' target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a>';
		$body .= '<p ' . $this->paragraph_style . '>Best Regards, <br />';
		$body .= '<p ' . $this->paragraph_style . '>Vendor Portal Admin <br />';
		$body .= 'Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		return $body;


	}
	public function applicationUpdate( $data )
	{

		//echo '<pre>' . print_r( $data , true ) .'</pre>';

		
		$data->pm_name = $this->getName($data->pm_email);


		$body = '<html><body style="background: #fafafa;">';
		
		$body .= '<p ' . $this->paragraph_style . '>The following Vendor has updated their <b>contact details</b>. Please review and action accordingly.</p>';
		$body .= '<ul style="list-style: none; padding:0;" >';
		$body .= '<li ' . $this->list_style . '>Application Ref: #00' .  $data->application->control . '</li>';
		$body .= '<li ' . $this->list_style . '>Trade name: ' . $data->application->suppliername . '</li>';
		$body .= '<li ' . $this->list_style . '>Date Application Actioned: ' . date( 'jS M Y - H:i' ) . '</li>';

		$body .= '</ul>';
		$body .= '<h4>The Procurement Manager Assigned to this supplier:</h4>';
		$body .= '<p ' . $this->paragraph_style . '>Procurement manager name: ' . $data->pm_name . '</p>';
		$body .= '<p ' . $this->paragraph_style . '>Email Address: <a ' . $this->inline_link . ' href="mailto:' . $data->pm_email . '">' . $data->pm_email . '</a></p>';
		$body .= '<a ' . $this->inline_link . ' target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a>';
		$body .= '<p ' . $this->paragraph_style . '>Best Regards, <br />';
		$body .= '<p ' . $this->paragraph_style . '>Vendor Portal Admin <br />';
		$body .= 'Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		return $body;


	}


	public function newApplicant()
	{

		$body = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' . $this->header_style . '>Valued Supplier</h2>';
		$body .= '<p ' . $this->paragraph_style . '>This email serves to inform you that the application was received by Premier Foods Trading (PTY) Limited. The relevant procurement manager will review the application and contact you accordingly.</p>';
		$body .= '<p ' . $this->paragraph_style . '>We thank you for your co-operation and patience</p>';
		$body .= '<p ' . $this->paragraph_style . '>Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		$body .= '<p ' . $this->paragraph_style . '><a style="color: rgb(6, 63, 124); font-weight: 400;" target="_blank" href="http://www.premier.co.za/terms/">Read our terms and Conditions</a></p>';
		$body .= '<html><body>';
		return $body;

	}

	public function newApplicantProcurement()
	{

		$body = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' . $this->header_style . '>Valued Supplier</h2>';
		$body .= '<p ' . $this->paragraph_style . '>This is an Email to inform you that the Application was received by Premier Foods. The Relevant Procurement manager will review the application and contact you accordingly.</p>';
		$body .= '<p ' . $this->paragraph_style . '>We thank you for your co-operation and patience</p>';
		$body .= '<p ' . $this->paragraph_style . '>Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		$body .= '<p ' . $this->paragraph_style . '><a style="color: rgb(6, 63, 124); font-weight: 400;" target="_blank" href="http://www.premier.co.za/terms/">Read our terms and Conditions</a></p>';
		$body .= '<html><body>';
		return $body;

	}

	public function principleApproved( $data )
	{

		$data->pm_name = $this->getName($data->pm_email);

		$body = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' . $this->header_style . '>' . $data->application->suppliername . ',</h2>';
		$body .= '<p ' . $this->paragraph_style . '>We are pleased to inform you that your new application request to be listed as a supplier to Premier Foods Trading has been approved in PRINCIPLE, subject to meeting our requirements, terms & conditions and the completion and acceptance of additional information as per the below request.</p>';
		$body .= '<p ' . $this->paragraph_style . '>We would like to request that you log back into the <a style="color: rgb(6, 63, 124); font-weight: 400;" target="_blank" href="http://vendorhub.premier.co.za/">Vendor Portal</a> and continue with the application process by furnishing Premierwith the requested additional documentation.Please download the relevant documents from the Vendor portal, sign and scan back via the Vendor Portal.</p>';
		$body .= '<h4 ' . $this->paragraph_style . '>The Procurement Manager Assigned to your Application:</h4>';
		$body .= '<p ' . $this->paragraph_style . '>Procurement manager name: ' . $data->pm_name . '</p>';
		$body .= '<p ' . $this->paragraph_style . '>Email Address: <a ' . $this->inline_link . ' href="mailto:' . $data->pm_email . '">' . $data->pm_email . '</a></p>';
		$body .= '<p ' . $this->paragraph_style . '>Tel: 011 847 7300</p>';
		$body .= '<p ' . $this->paragraph_style . '>&nbsp;</p>';
		$body .= '<p ' . $this->paragraph_style . '>Best Regards, </p>';
		$body .= '<p ' . $this->paragraph_style . '>Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		$body .= '<p ' . $this->paragraph_style . '><a style="color: rgb(6, 63, 124); font-weight: 400;" target="_blank" href="http://www.premier.co.za/terms/">Read our terms and Conditions</a></p>';
		$body .= '<a ' . $this->inline_link . '  target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a>';
		$body .= '<html><body>';

		return $body;


	}

	public function supplierAgreement( $data )
	{

		$data->pm_name = $this->getName($data->pm_email);

		$body = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' . $this->header_style . '>' . $data->application->suppliername  . '</h2>';
		$body .= '<p ' . $this->paragraph_style . '>We are pleased to inform you that your application request to be listed as a supplier to Premier Foods Trading (PTY) Limited is in the final stage. Subject to meeting our requirements, terms & conditions and the completion and acceptance of additional information as per below request the application will continue to the final stages.</p>';
		$body .= '<p ' . $this->paragraph_style . '>We would like to request that you log back into the <a ' . $this->inline_link . '  target="_blank" href="http://vendorhub.premier.co.za/">Vendor Portal</a> and download the relevant documents to be signed off and scanned back via the Vendor Portal . All Documents can be found under the Confirm button in your Application Tab.</p>';
		$body .= '<p ' . $this->paragraph_style . '>Supplier agreement and/or Credit terms.</p>';
		$body .= '<h4 ' . $this->paragraph_style . '>The Procurement Manager Assigned to your Application:</h4>';
		$body .= '<p ' . $this->paragraph_style . '>Procurement manager name: ' . $data->pm_name . '</p>';
		$body .= '<p ' . $this->paragraph_style . '>Email Address: <a ' . $this->inline_link . ' href="mailto:' . $data->pm_email . '">' . $data->pm_email . '</a></p>';
		$body .= '<p ' . $this->paragraph_style . '>Tel: 011 847 7300</p>';
		$body .= '<p ' . $this->paragraph_style . '>&nbsp;</p>';
		$body .= '<p ' . $this->paragraph_style . '>Best Regards, </p>';
		$body .= '<p ' . $this->paragraph_style . '>Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		$body .= '<p ' . $this->paragraph_style . '><a style="color: rgb(6, 63, 124); font-weight: 400;" target="_blank" href="http://www.premier.co.za/terms/">Read our terms and Conditions</a></p>';
		$body .= '<a ' . $this->inline_link . '  target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a>';
		$body .= '<html><body>';

		return $body;


	}

	public function applicationComplete( $data )
	{
		$data->pm_name = $this->getName($data->pm_email);

		$body = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' . $this->header_style . '>' . $data->application->suppliername . ',</h2>';
		//$body .= '<p ' . $this->paragraph_style . '>We are pleased to inform you that your new application request to be listed as a supplier to Premier Foods Trading has been created successfully.</p>';
		$body .= '<p ' . $this->paragraph_style . '>Your data was successfully updated on the vendor portal hub.</p>';
		//$body .= '<p ' . $this->paragraph_style . '><strong>Your Account number: ' . $data->application->accountcode . '</strong></p>';
		//$body .= '<p ' . $this->paragraph_style . '>We would like to request that you log back into the <a style="color: rgb(6, 63, 124); font-weight: 400;" target="_blank" href="http://vendorhub.premier.co.za/">Vendor Portal</a> and continue with the application process by furnishing Premierwith the requested additional documentation.<br />Please download the relevant documents from the Vendor portal, sign and scan back via the Vendor Portal.</p>';
		$body .= '<h4 ' . $this->paragraph_style . '>The Procurement Manager Assigned to your Application:</h4>';
		$body .= '<p ' . $this->paragraph_style . '>Procurement manager name: ' . $data->pm_name . '</p>';
		$body .= '<p ' . $this->paragraph_style . '>Email Address: <a ' . $this->inline_link . ' href="mailto:' . $data->pm_email . '">' . $data->pm_email . '</a></p>';
		$body .= '<p ' . $this->paragraph_style . '>Tel: 011 847 7300</p>';
		$body .= '<p ' . $this->paragraph_style . '>&nbsp;</p>';
		$body .= '<p ' . $this->paragraph_style . '>Best Regards, </p>';
		$body .= '<p ' . $this->paragraph_style . '>Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		$body .= '<p ' . $this->paragraph_style . '><a style="color: rgb(6, 63, 124); font-weight: 400;" target="_blank" href="http://www.premier.co.za/terms/">Read our terms and Conditions</a></p>';
		$body .= '<a ' . $this->inline_link . '  target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a>';

		return $body;

	}

	public function applicationRejected( $data )
	{
				

		$body = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' . $this->header_style . '>' . $data->application->suppliername . ',</h2>';
		$body .= '<p ' . $this->paragraph_style . '>We regret to inform you that your application request to be listed as a supplier to Premier Foods trading was unsuccessful and has been declined.
		We will keep all relevant data on file and should circumstances change, we will review</p>';
		$body .= '<p ' . $this->paragraph_style . '>Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		$body .= '<p ' . $this->paragraph_style . '><a style="color: rgb(6, 63, 124); font-weight: 400;" target="_blank" href="http://www.premier.co.za/terms/">Read our terms and Conditions</a></p>';
		$body .= '<a ' . $this->inline_link . '  target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a>';

		return $body;

	}

	public function bankDetailsChange( $data )
	{

		$data->pm_name = $this->getName($data->pm_email);
		//echo '<pre>' . print_r( $data , true ) . '</pre>';

		$body = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' . $this->header_style . '>Harry Smit.</h2>';
		$body .= '<p ' . $this->paragraph_style . '>The following supplier has made changes to their Banking details.</p>';
		$body .= '<ul style="list-style: none; padding: 0;text-indent: 0;">';
		$body .= '<li ' . $this->list_style . '>Account number: ' . $data->application->accountcode . '</li>';
		$body .= '<li ' . $this->list_style . '>Trade name: ' . $data->application->suppliername . '</li>';
		$body .= '<li ' . $this->list_style . '>Date Application Actioned: ' . date( 'jS M Y - H:i' ) . '</li>';
		$body .= '</ul>';
		$body .= '<h3>Banking details</h3>';
		$body .= '<ul style="list-style: none; padding: 0;text-indent: 0;">';
		$body .= '<li ' . $this->list_style . '>Bank Name: ' . $data->application->bankname . '</li>';
		$body .= '<li ' . $this->list_style . '>Bank Account Number/IBAN: ' . $data->application->bankaccnumber . '</li>';
		$body .= '<li ' . $this->list_style . '>Bank Branch Name:  ' . $data->application->bankbranchname . '</li>';
		$body .= '<li ' . $this->list_style . '>Bank Branch Code: ' . $data->application->bankbranchcode . '</li>';
		$body .= '<li ' . $this->list_style . '>Bank Account Type: ' . $data->application->bankacctype . '</li>';
		$body .= '<li ' . $this->list_style . '>Bank Account Holders Name. ' . $data->application->bankaccholdername . '</li>';
		$body .= '<li ' . $this->list_style . '>Swift/BIC Code: Swift Code: '. $data->application->swift  .'</li>';
		$body .= '</ul>';
		$body .= '<h4>Signatories:</h4>';

		$body .= '<table style="width: 500px">';
		$body .= '<thead><tr>';
		$body .= '<th style="width: 33.333333%; text-align: left;">Name:</th><th style="width: 33.333333%; text-align: left;">Email</th><th style="width: 33.333333%; text-align: left;">Contact Number</th>';
		$body .= '</tr></thead><tbody>';

		foreach ( json_decode( $data->application->signatories ) as $signatory ) :

			$body .= '<tr>';

			$body .= '<td>' . $signatory[ 0 ] . '</td>';
			$body .= '<td><a ' . $this->inline_link . ' href="mailto:' . $signatory[ 1 ] . '">' . $signatory[ 1 ] . '</a></td>';
			$body .= '<td>' . $signatory[ 2 ] . '</td>';
			$body .= '</tr>';

		endforeach;

		$body .= '</tbody></table>';

		$body .= '<p>The Procurement Manager Assigned to this supplier:</p>';
		$body .= '<p>Procurement manager name: ' . $data->pm_name . '</p>';
		$body .= '<p>Email Address: <a ' . $this->inline_link . ' href="mailto:' . $data->pm_email . '">' . $data->pm_email . '</a></p>';
		$body .= '<p ' . $this->paragraph_style . '>Best Regards, <br />';
		$body .= 'Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		$body .= '<p ' . $this->paragraph_style . '><a style="color: rgb(6, 63, 124); font-weight: 400;" target="_blank" href="http://www.premier.co.za/terms/">Read our terms and Conditions</a></p>';
		$body .= '<a ' . $this->inline_link . ' target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a>';

		return $body;

	}

	public function bankingDetailsToHarry()
	{


		$body = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' . $this->header_style . '>Hi there Harry.</h2>';
		$body .= '<p ' . $this->paragraph_style . '>A vendor has successfully completed their application and has been added to our system. Please find the attached
		banking details.</p>';

		return $body;

	}

	public function assessedByLegal( $data )
	{

		$data->pm_name = $this->getName($data->pm_email);

		$body = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' . $this->header_style . '>Hi there,</h2>';
		$body .= '<p ' . $this->paragraph_style . '>The following Vendor has requested to be part of the Premiersupplier list. Please review the application and Action accordingly.</p>';
		$body .= '<ul style="list-style: none; padding: 0;text-indent: 0;">';
		$body .= '<li ' . $this->list_style . '><strong>Trade name:</strong> ' . $data->application->suppliername . '</li>';
		$body .= '<li ' . $this->list_style . '><strong>Date Application Actioned:</strong> ' . date( 'jS F Y - H:i' ) . '</li>';
		$body .= '<li ' . $this->list_style . '><strong>Stage:</strong> 4</li>';
		$body .= '<li ' . $this->list_style . '><strong>Status:</strong> To be approved by Legal</li>';
		$body .= '</ul>';
		$body .= '<p ' . $this->paragraph_style . '>The following Procurement manager was assigned to this Vendor.</p>';
		$body .= '<ul style="list-style: none; padding: 0;text-indent: 0;">';
		$body .= '<li ' . $this->list_style . '><strong>Procurement Manager</strong>: ' . $data->pm_name . '</li>';
		$body .= '<li ' . $this->list_style . '><strong>Email</strong>: <a ' . $this->inline_link . ' href="mailto:' . $data->pm_email . '">' . $data->pm_email . '</a></li>';
		$body .= '</ul>';
		$body .= '<hr style="margin: 25px 0;" />';
		$body .= '<p style="margin-top: 0;"><a ' . $this->inline_link . ' target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a></p>';
		$body .= '<ul style="list-style: none; padding: 0; text-indent: 0;">';
		$body .= '<li ' . $this->list_style . '>Best Regards</li>';
		$body .= '<li ' . $this->list_style . '>Vendor Portal Admin</li>';
		$body .= '<li ' . $this->list_style . '>Premier Foods.</li>';
		$body .= '</ul>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';

		return $body;

	}

	public function validateDocuments( $data )
	{

		$data->pm_name = $this->getName($data->pm_email);

		$body = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' . $this->header_style . '>Dear ' . $data->pm_name . ',</h2>';
		$body .= '<p ' . $this->paragraph_style . '>The following Vendor has uploaded and submitted their supporting documents. Please review the application, Action and contact the supplier accordingly.</p>';
		$body .= '<ul style="list-style: none; padding: 0;text-indent: 0;">';
		$body .= '<li ' . $this->list_style . '><strong>Trade name:</strong> ' . $data->application->suppliername . '</li>';
		$body .= '<li ' . $this->list_style . '><strong>Date Application Actioned:</strong> ' . date( 'jS F Y - H:i' ) . '</li>';
		$body .= '<li ' . $this->list_style . '><strong>Stage:</strong> 3</li>';
		$body .= '<li ' . $this->list_style . '><strong>Status:</strong> Procurement manager to validate docs</li>';
		$body .= '</ul>';
		$body .= '<p style="margin-top: 0;">&nbsp;</p>';
		$body .= '<ul style="list-style: none; padding: 0; text-indent: 0;">';
		$body .= '<li ' . $this->list_style . '>Best Regards</li>';
		$body .= '<li ' . $this->list_style . '>Vendor Portal Admin</li>';
		$body .= '<li ' . $this->list_style . '>Premier Foods.</li>';
		$body .= '</ul>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		$body .= '<p style="margin-top: 0;"><a ' . $this->inline_link . ' target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a></p>';

		return $body;

	}

	public function catalogueRejected( $data )
	{
		$data->pm_name = $this->getName($data->pm_email);

		$body = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' . $this->header_style . '>' . $data->application->suppliername . '</h2>';
		$body .= '<p ' . $this->paragraph_style . '>The following Supplier Product pricing template was rejected with the rejection reason:</p>';
		$body .= '<p ' . $this->paragraph_style . '>'. $data->reason->description  .'</p>';
		$body .= '<p ' . $this->paragraph_style . '>Application ref: #00' .  $data->application->control . '</p>';
		$body .= '<p ' . $this->paragraph_style . '>Please log into the <a ' . $this->inline_link . ' target="_blank" href="http://vendorhub.premier.co.za/">Vendor portal</a>, download the Product pricing template.
		Review the comments in the first sheet and resubmit with the necessary amendments. Thanks for your co-operation. </p>';
		$body .= '<ul style="list-style: none; padding: 0; text-indent: 0;">';
		$body .= '<p ' . $this->paragraph_style . '>The Procurement Manager Assigned to this supplier:</p>';
		$body .= '<p ' . $this->paragraph_style . '>Procurement manager name: ' . $data->pm_name . '</p>';
		$body .= '<p ' . $this->paragraph_style . '>Email Address: <a ' . $this->inline_link . ' href="mailto:' . $data->pm_email . '">' . $data->pm_email . '</a></p>';
		$body .= '<p ' . $this->paragraph_style . '>Best Regards, <br />';
		$body .= 'Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		$body .= '<p ' . $this->paragraph_style . '><a style="color: rgb(6, 63, 124); font-weight: 400;" target="_blank" href="http://www.premier.co.za/terms/">Read our terms and Conditions</a></p>';
		$body .= '<a ' . $this->inline_link . ' target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a>';

		return $body;

	}

	public function internalRejection( $data ) {


		$data->pm_name = $this->getName($data->pm_email);

		$body = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' . $this->header_style . '>' . $data->application->suppliername . '</h2>';
		$body .= '<p ' . $this->paragraph_style . '>The following Supplier request was rejected with the following rejection reason:</p>';
		$body .= '<p ' . $this->paragraph_style . '>'. $data->reason->description  .'</p>';
		$body .= '<h4>Supplier Details</h4>';
		$body .= '<ul style="list-style: none; padding:0;" >';
		$body .= '<li ' . $this->list_style . '>Application Ref: #00' . $data->application->control . '</li>';
		$body .= '<li ' . $this->list_style . '>Trade name: ' . $data->application->suppliername . '</li>';
		$body .= '<li ' . $this->list_style . '>Date Application Actioned: ' . date( 'jS M Y - H:i' , strtotime($data->audit->previousstagedatelogged ) ) . '</li>';
		$body .= '<li ' . $this->list_style . '>Date Application Rejecting: ' . date( 'jS M Y - H:i' ) . '</li>';
		$body .= '<li ' . $this->list_style . '>Stage No: ' . $data->stage_no->stageno . '</li>';
		$body .= '<li ' . $this->list_style . '>Status: ' . $data->stage_no->description . '</li>';
		$body .= '</ul>';
		$body .= '<ul style="list-style: none; padding: 0; text-indent: 0;">';
		$body .= '<p ' . $this->paragraph_style . '>The Procurement Manager Assigned to this supplier:</p>';
		$body .= '<p ' . $this->paragraph_style . '>Procurement manager name: ' . $data->pm_name . '</p>';
		$body .= '<p ' . $this->paragraph_style . '>Email Address: <a ' . $this->inline_link . ' href="mailto:' . $data->pm_email . '">' . $data->pm_email . '</a></p>';
		$body .= '<p ' . $this->paragraph_style . '>Best Regards, <br />';
		$body .= 'Premier Foods Trading (PTY) Limited.</p>';
		$body .= '<p><small>This is an automated email Response. Please do not Reply or send any further correspondence to this email address.</small></p>';
		$body .= '<p ' . $this->paragraph_style . '><a style="color: rgb(6, 63, 124); font-weight: 400;" target="_blank" href="http://www.premier.co.za/terms/">Read our terms and Conditions</a></p>';
		$body .= '<a ' . $this->inline_link . ' target="_blank" href="http://vendorhub.premier.co.za/">Click here to login</a>';

		return $body;

	}

	public function setBody( $template )
	{

		$this->body = $template;

	}

	public function output()
	{

		return $this->body;

	}

}