<?php

/**
 * Class Menu
 */

Class Menu extends AModel
{

	public function __construct()
	{

		$permissions = $this->getPermittedItems();

		$this->accessCheck();

		$this->getItems( $permissions );

	}

	public function getPermittedItems()
	{

		$user_control = $_SESSION['userdata']->control;
		$user_query = 'SELECT usertypecontrol FROM users WHERE control = ' . $user_control;

		$user_statement = $this->prepare( $user_query );
		$user_statement->execute();

		$user = $this->fetchObject( $user_statement );

		$query = "SELECT * FROM menu WHERE state = 1";
		$statement = $this->prepare( $query );
		$statement->execute();

		$menu_items = $this->fetchObjects( $statement );
		$permitted_items = array();

		foreach ( $menu_items as $item ) :

			$set_permissions = json_decode( $item->permissions , true );

			if ( in_array( $user->usertypecontrol , $set_permissions ) ) :

				$permitted_items[] = $item->control;

			endif;

		endforeach;

		return $permitted_items;

	}

	public function accessCheck()
	{
		
		$control = $this->getControlString();

		$query = 'SELECT permissions FROM menu WHERE link = "' . $control . '"';
		$statement = $this->prepare( $query );
		$statement->execute();

		$item = $this->fetchObject( $statement );

		$allowed_user = json_decode( $item->permissions , true );

		if ( !empty( $control ) ) :

			if ( !$allowed_user == 0 ) :

				if ( !in_array( $_SESSION['userdata']->usertypecontrol , $allowed_user ) ) {

					/*header( 'Location: /error.php?status=401' );

					session_destroy();

					exit;*/

				}

			endif;

		endif;

	}

	public function getItems( $permissions )
	{

		$query = "SELECT * FROM menu WHERE state = 1 AND control IN (" . implode( ',' , $permissions ) . ")";

		$statement = $this->prepare( $query );
		$statement->execute();

		$menu_items = $this->fetchObjects( $statement );

		$this->setActive( $menu_items );

	}

	public function setActive( $menu_items )
	{

		$current_uri = $this->getControlString();
        //print $current_uri;
        if($current_uri == 'control=history/main/audit'){
            $current_uri = 'control=history/main/index';
        }
        if($current_uri == 'control=dashboard/main/responses'){
            $current_uri = 'control=dashboard/main/inbox';
        }

		foreach ( $menu_items as $key => $item ) :

			if ( $item->link == $current_uri ):

				$menu_items[$key]->active = 'active';

			endif;

		endforeach;

		$this->displayMenu( $menu_items );

	}

	public function getControlString()
	{

		// init vars
		$server = $_SERVER['QUERY_STRING'];
		$control_end_position = strpos( $server , '&' );

		if ( !empty( $control_end_position ) ) :

			$query_array = str_split( $server , $control_end_position );

			$current_uri = $query_array[0];

		else :

			$current_uri = $server;

		endif;

		return $current_uri;
	}

	public function displayMenu( $menu_items )
	{

		echo '<ul class="menu">';

		foreach ( $menu_items as $item ):
            if($item->title === 'Logout'){
                if( $_SESSION['userdata'] ) {
                    echo '  <li style="float:right">
                    <!--<div id="loggedin">
                        You are logged in as '.$_SESSION['userdata']->vend->VENDNAME.'<br/> -->
                        <a href="/?' . $item->link . '">' . $item->title . '</a>
                </li>';
                }
            }else{
                echo '<li class="' . $item->active . '">';
                echo '<a href="/?' . $item->link . '">' . $item->title . '</a>';
                echo '</li>';
            }
		endforeach;

//        if( $_SESSION['userdata'] ) {
//        echo '  <li style="float:right">
//                    <div id="loggedin">
//                        You are logged in as '.$_SESSION['userdata']->username.'
//                    </div>
//                </li>';
//        }
        echo '</ul>';

	}

}


?>