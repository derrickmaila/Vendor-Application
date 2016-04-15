<?php
class User_Model extends AModel
{

	public function login( $email = '' , $password = '' )
	{

		$email = $this->sanitizeline( $email );

		$password = md5( $this->sanitizeline( $password ) );

		$sql = "SELECT *,
		            (SELECT (SELECT VENDNAME FROM vendormaster WHERE VENDORID = vendormapping.gpvendor) FROM vendormapping WHERE portalusercontrol = users.control) as gpvendor,
		            (SELECT gpbuyer FROM buyermapping WHERE portalusercontrol = users.control) as gpbuyer
		        FROM users WHERE users.username like '%{$email}%' and users.password = '{$password}'";
//        $sql = "SELECT *
//                FROM users
//                    LEFT JOIN vendormapping as  vm
//                        ON vm.portalusercontrol=users.control
//                WHERE users.username like '%{$email}%'
//                AND users.password = '{$password}'";


		$result = $this->query( $sql );

		$row = $this->fetchObject( $result );

        if(empty($row->gpbuyer)){
            $row->gpbuyer = '<a style="color: #00778f !important;" href="/?control=buyermapping/main/index">Please add Mapping</a>';
        };

		return $row;

	}

	public function addUser( $data )
	{

		if ( $data['password'] == "" ) {

			unset( $data['password'] );

		} else {

			$data['password'] = md5( $data['password'] );

		}
		$data['username'] = strtolower($data['username']);

		if ( $data['usertypecontrol'] == "" ) {
			$data['usertypecontrol'] = 2;
		} //default to normal user if not set

		return $this->insert( "user" , $data );
	}

	public function updateUser( $control , $data )
	{

		if ( $data['password'] == "" ) {

			unset( $data['password'] );

		} else {

			$data['password'] = md5( $data['password'] );

		}
		$data['username'] = strtolower($data['username']);

		return $this->update( "user" , $data , "control" , $control );
	}

	public function getUsers()
	{

		$sql = "SELECT a.* , b.control as typecontrol, b.description as typedescription FROM  users as a LEFT JOIN usertypes as b ON (a.usertypecontrol = b.control)";

		$result = $this->query( $sql );

		while ( $row = $this->fetchObject( $result ) ) $rows[] = $row;

		return $rows;

	}
	public function getUsername($control = 0) {
		$sql = "SELECT * FROM users WHERE control = ?";
		$statement = $this->prepare($sql);
		$statement->execute(array($control));
		return $this->fetchObject($statement);
	}

	public function getUser( $id = 0 )
	{

		$id = (int)$id;

		$sql = "SELECT a.* , b.control as typecontrol, b.description as typedescription FROM  users as a LEFT JOIN usertypes as b ON (a.usertypecontrol = b.control) WHERE a.control = '{$id}'";

		$statement = $this->prepare( $sql );
		$statement->execute();

		$result = $this->fetchObject( $statement );

		return $result;

	}

	public function getUserByEmail( $email = '' )
	{

		$email = $this->sanitizeline( $email );

		$sql = "SELECT * FROM users WHERE email = '{$email}'";

		$result = $this->query( $sql );

		return $this->fetchObject( $result );

	}

	public function checkIfUserExists( $username )
	{
		$sql = "SELECT count(email) as cnt FROM users WHERE email = '{$username}'";

		$result = $this->query( $sql );
		$row = $this->fetchObject( $result );


		if ( $row->cnt == 0 ) return false;
		else return true;
	}

	public function removeUser( $control )
	{
		$sql = 'DELETE FROM users WHERE control = ' . $control;
		$stmnt = $this->prepare( $sql );
		return $stmnt->execute();
	}

	public function doesUserExist( $email = '' )
	{

		$sql = "SELECT * FROM users WHERE username = ?";

		$data = array( $email );

		$statement = $this->prepare( $sql );

		$statement->execute( $data );

		if ( $statement->rowCount() > 0 ) :

			return true;

		else :

			return false;

		endif;

	}

	public function getUserTypes()
	{
		$sql = "SELECT * FROM usertypes ORDER BY description ASC";
		$statement = $this->prepare( $sql );
		$statement->execute();
		return $this->fetchObjects( $statement );
	}

	public function searchByName()
	{
		// init vars
		$keyword = $_POST['keyword'];
		$query = 'SELECT control, username FROM users WHERE username LIKE "%' . $keyword . '%"';
		$statement = $this->prepare( $query );

		$statement->execute();
		$return = $this->fetchObjects( $statement );

		echo json_encode( $return );
	}
}

?>