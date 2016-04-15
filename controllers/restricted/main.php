<?php
/**
 * @author by wesleyhann
 * @date 2014/01/22
 * @time 4:56 PM
 */

class Controller extends AController {

	public function view() {

		echo $this->loadView( "restricted/view" , "" );

	}

}