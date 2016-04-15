<?php
/**
 * @author by wesleyhann
 * @date 2014/01/17
 * @time 11:53 AM
 */


abstract class ReportsHelper
{

	static public function dateCompare( $date , $date_comparison , $return_type )
	{

		( $date == 'now' ) ? $date = new DateTime( 'now' ) : $date = new DateTime( $date );;

		$date_comparison = new DateTime( $date_comparison );
		$interval = $date->diff( $date_comparison );

		switch ( $return_type ) {
			case 'h' :
				$hours         = $interval->h;
				$days          = $interval->days * 24;
				$format_return = ( $hours + $days );
				break;

		}

		return $format_return;

	}

}