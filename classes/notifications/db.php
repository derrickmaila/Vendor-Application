<?php
/**
 * Created by PhpStorm.
 * User: wesleyhann
 * Date: 2014/02/25
 * Time: 12:22 PM
 */

class NotificationDB extends mysqli {

	public function __construct(){

		$connection = parent::connect('m2dev-db-ws-001.c9dvmnpdpl1l.us-east-1.rds.amazonaws.com' , 'iliadportal', 'iLI@d9otR@l' , 'ws_iliadportal' );

		if( mysqli_connect_errno() ) :

			echo mysqli_connect_error();

		endif;

		return $connection;

	}

	public function query( $query ){

		if( !$this->real_query( $query ) ) {

			throw new exception( $this->error , $this->errno );

		}

		$result = new mysqli_result( $this );

		return $result;

	}

	public function fetchObject( $result ) {

		return mysqli_fetch_object( $result );

	}

	public function fetchObjects( $results ) {

		// init vars
		$return_array = array();

		while($row = mysqli_fetch_object( $results ) ) :

			$return_array[] = $row;

		endwhile;


		return $return_array;

	}

	public function update( $column , $where_column, $where_value ) {

		$query = 'Update ' . $column .' SET notificationsent = 1 WHERE ' . $where_column . ' = ' . $where_value;

		$this->real_query( $query );

		$affected_rows = $this->affected_rows;

		if( $affected_rows >= 1 ) :

			return true;

		else :

			return false;

		endif;

	}

}