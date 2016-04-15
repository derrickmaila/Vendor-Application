<?php
/**
 * @author by wesleyhann
 * @date 2014/02/03
 * @time 1:59 PM
 */

Define( 'DS' , DIRECTORY_SEPARATOR );

class Download_Model extends AModel {

	public function generateFile( $params ) {

		$query_string = $this->processQueryString( $params );

		$this->fileCheck( $query_string,$_GET['confirmation'] );

	}

	public function fileCheck( $query_string ,$confirmation = 0) {
		if($confirmation == 0) {
			$file_path = $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/vendor_files/' . $query_string[ 'controlsource' ] . DS . $query_string['file'];

		} else {
			$file_path = $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/application_confirmation/' . $query_string[ 'controlsource' ] . DS . $query_string['file'];
		}

		// We'll be outputting as the file type
		/*
		print_r($query_string);
		echo $file_path;
		
		ob_clean();
		
		readfile( $file_path );
		exit;*/
		
		
		ob_clean();
		header('Content-type: ' . $query_string[ 'filetype' ]);
		//header("Content-Transfer-Encoding: binary");

		// It will be the file name on the download
		header('Content-Disposition: attachment; filename="' . str_replace("%20", " ", $query_string[ 'filename' ]) . '"');
		header('Content-Length: ' . filesize($file_path));
		// The original file source
		readfile( $file_path );

		exit;

	}


	public function processQueryString( $params ) {

		$explode_query_string = explode( '&' , $params);

		$return_array = array();

		foreach($explode_query_string as $query) {

			$new_array = explode( '=' , $query );

			$return_array[ $new_array[0] ] = $new_array[1] ;

		}

		unset($return_array['ajax']);

		return $return_array;

	}


}
?>