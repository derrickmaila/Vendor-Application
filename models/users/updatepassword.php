<?php
/**
 * @author by wesleyhann
 * @date 2014/01/14
 * @time 11:44 AM
 */

class Updatepassword_Model extends AModel
{

	private $password;

	public function updateUserPass()
	{
		// get query
		$query = $this->urlQueryString();

		// init vars
		$user = $query['user'];
		$ref = $query['ref'];

		if ( $this->checkRef( $user , $ref ) ) {
			if ( $this->setPassword( $_POST['password'] ) ) {
				$update = $this->updatePassword( $user );
			} else {
				return false;
			}
		}

		if ( $update ) {
			return true;
		}

	}

	public function updatePassword( $userControl )
	{
		if ( $this->update( 'users' , array( 'password' => md5( $this->password ) ) , 'control' , $userControl ) ) {

			Return true;

		}
	}

	public function urlQueryString()
	{
		$baseQuery = explode( '&' , $_SERVER['QUERY_STRING'] );
		$queryArray = array();

		foreach ( $baseQuery as $i ) {
			$array = explode( '=' , $i );
			$queryArray[$array[0]] = $array[1];
		}

		return $queryArray;

	}

	private function setPassword( $password )
	{
		if ( !empty( $password ) ) {
			$this->password = $password;
			return true;

		} else {
			return false;
		}
	}

	public function checkRef( $control , $ref )
	{
		$query = 'SELECT control, password FROM users WHERE control = ' . $control . ' AND password = "' . $ref . '" ';

		$statement = $this->prepare( $query );
		$statement->execute();

		$result = $this->fetchObject( $statement );

		if ( is_object( $result ) ) {
			return true;

		} else {

			die( 'Access Denied: Token expired' );

		}

	}

}