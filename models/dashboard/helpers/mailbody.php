<?php
/**
 * @author by wesleyhann
 * @date 2014/01/28
 * @time 10:31 AM
 */


class MailBody {

	public $header_style = 'style="font-family: Arial, sans-serif; color: rgb(6, 63, 124); font-weight: 400; "';
	public $paragraph_style = 'style="font-family: Arial, sans-serif; color: #444; text-shadow: 0 1px 0 #fff;"';
	public $link_style = 'style="display: block; background-color: rgb(6, 63, 124); padding: 15px 25px; color:  #fff; font-weight: 800; width:  125px; text-align: center; text-shadow: 0 -1px 0 rgba(0,0,0,0.3);"';

	public function __construct( $stage ) {

		switch ( $stage ) {

			case 6 :

			break;

			case 9 :

				$template = $this->supplierUploadDocs();

			break;

			default :

				$template = $this->genericTemplate();

			break;

		}

		$this->output( $template );

	}

	public function genericTemplate () {

		$body  = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' .$this->header_style .'>An Application requires your attention.</h2>';
		$body .= '<p' . $this->paragraph_style . '>An Application has now enetered a stage that requires your attention.</p>';
		$body .= '<p ' . $this->paragraph_style . '>Please login to Approve/Decline</p>';
		$body .= '<a ' . $this->link_style . '  target="_blank" href="http://webstore.iliad.local/">Click here to login</a>';
		$body .= '<html><body>';
		return $body;


	}

	public function userToDoMail() {



	}

	public function supplierUploadDocs() {

		$body  = '<html><body style="background: #fafafa;">';
		$body .= '<h2 ' .$this->header_style .'>Hi there.</h2>';
		$body .= '<p ' .$this->paragraph_style .'>There are a few additional documents that we require from you to complete your application. They are as follows:</p>';
		$body .= '<ul>';
		$body .= '<li>Product Template</li>';
		$body .= '<li>List of Legal Entities</li>';
		$body .= '<li>Supplier Agreement</li>';
		$body .= '<li>Returns Policy</li>';
		$body .= '<li>Resolution Letter</li>';
		$body .= '<li>Direct IDs</li>';
		$body .= '<li>Credit Application Form</li>';
		$body .= '</ul>';
		$body .= '<p>Please login to our site and upload your documents to proceed</p>';
		$body .= '<a ' . $this->link_style . ' target="_blank" href="http://webstore.iliad.local/">Click here to login</a>';
		$body .= '<html><body>';

		return $body;

	}

	public function output( $template ){

		echo $template;

	}

}