<?php

class Reports_Model extends AModel
{

	private $data;

	/**
	 * @param $params The $_POST data from the reports from
	 * @param $format Either web or excel can be passed
	 * @return array Returns an array of results from the database that fall within the paramaters
	 */

	public function getReports( $params , $format )
	{
		$this->queryDatabase( $params );

		if ( $format == 'web' ) {

			$applications = array();

			foreach ( $this->data as $key => $app ) {
				$applications[] = $this->formatForWeb( $app );;
			}

			return $applications;

		} else {
			$this->formatForExcel( $params );
		}

	}

	/**
	 * @param $params The parameters that have been set for the report
	 */

	public function queryDatabase( $params )
	{

		$fromDate = new DateTime( $params['from-date'] );

		if ( !empty( $params['to-date'] ) ) {
			$toDate = new DateTime( $params['to-date'] );
		} else {
			$toDate = new DateTime( 'now' );
		}

		$sql = 'SELECT a.* , b.control as vendorcontrol, b.suppliername as vendorname, c.description as stagedescription, d.username as username, e.username as previoususername, ';
		$sql .= 'f.description as previoustage, f.timeallowedonstage as alloprevioustimeallowed ';
		$sql .= 'FROM audit AS a LEFT JOIN applications AS b ON a.usercontrol = b.usercontrol ';
		$sql .= 'LEFT JOIN applicationstages AS c ON a.applicationstagecontrol = c.control ';
		$sql .= 'LEFT JOIN users AS d ON a.usercontrol = d.control ';
		$sql .= 'LEFT JOIN users AS e ON a.previoususercontrol = e.control ';
		$sql .= 'LEFT JOIN applicationstages AS f ON a.applicationstagecontrol = f.nextstagecontrol AND a.applicationtypemarker = f.applicationtype ';
		$sql .= 'WHERE ';
		if ( !empty( $params['from-date'] ) ) :
			$sql .= 'datelogged BETWEEN "' . $fromDate->format( 'Y-m-d' ) . '" AND "' . $toDate->format( 'Y-m-d' ) . '" AND ';
		endif;
		$sql .= 'applicationstagecontrol = ' . $params['stagecontrol'];
		$sql .= ' ORDER BY ' . $params['sort-by'] . ' ' . $params['sort-direction'];

		//echo $sql;

		$statement = $this->prepare( $sql );
		$statement->execute();

		$results = $this->fetchObjects( $statement );

		$this->setData( $results );
	}

	public function setData( $data )
	{
		$this->data = $data;
	}

	/**
	 * @param mixed $application Application data used to build the class
	 * @return stdClass Returns a structured class of data for the application
	 */

	public function formatForWeb( $application )
	{
		$app = new stdClass();
		$app->current = new stdClass();
		$app->previous = new stdClass();
		$current = $app->current;
		$previous = $app->previous;
		$data = $application;

		// application details
		$app->control = $data->control;
		$app->usercontrol = $data->usercontrol;
		$app->application = $data->applicationcontrol;
		$app->vendor = $data->vendorname;
		$app->vendorcontrol = $data->vendorcontrol;
		$app->stage = $data->applicationstagecontrol;
		$app->type = $data->applicationtypemarker;
		$app->lastupdate = $data->datelogged;

		// current stage data
		$current->stage = $data->stagedescription;
		$current->user = $data->username;
		$current->date = $data->datelogged;
		$current->notification = $data->notificationsent;

		// previous stage data
		$previous->stage = $data->previoustage;
		$previous->user = $data->previoususername;
		$previous->date = $data->previousstagedatelogged;
		$previous->timeframe = $data->alloprevioustimeallowed;

		return $app;

	}

	/**
	 * @param $params Required for generating the outputted file name
	 */

	public function formatForExcel( $params )
	{

		if ( class_exists( 'PHPExcel' ) ) : ;

			$i = 2;
			$objPHPExcel = new PHPExcel();

			// Set document properties
			$objPHPExcel->getProperties()->setCreator( "Premier Portal" )
				->setLastModifiedBy( "Premier Portal" )
				->setTitle( "Office 2007 XLSX Test Document" )
				->setSubject( "Office 2007 XLSX Test Document" )
				->setDescription( "Test document for Office 2007 XLSX, generated using PHP classes." )
				->setKeywords( "office 2007 openxml php" )
				->setCategory( "Test result file" );


			// Add headers
			$objPHPExcel->setActiveSheetIndex( 0 )
				->setCellValue( 'A1' , 'Vendor' )
				->setCellValue( 'B1' , 'Current Stage' )
				->setCellValue( 'C1' , 'Person Responsible' )
				->setCellValue( 'D1' , 'Time on Stage' )
				->setCellValue( 'E1' , 'Previous Stage' )
				->setCellValue( 'F1' , 'Previous Person Responsible' )
				->setCellValue( 'G1' , 'Previous Time on Stage' )
				->setCellValue( 'H1' , 'Previously on Time' )
				->setCellValue( 'I1' , 'Last Update' )
				->setCellValue( 'J1' , 'Application Type' );


			foreach ( $this->data as $row ) :

				$currStageTimer = ReportsHelper::dateCompare( 'now' , $row->datelogged , 'h' );
				$prevStageTimer = ReportsHelper::dateCompare( $row->datelogged , $row->previousstagedatelogged , 'h' );
				( $row->alloprevioustimeallowed > $prevStageTimer ) ? $completedOnTime = 'Yes' : $completedOnTime = 'No';

				$objPHPExcel->setActiveSheetIndex( 0 )
					->setCellValue( 'A' . $i , $row->vendorname )
					->setCellValue( 'B' . $i , $row->stagedescription )
					->setCellValue( 'C' . $i , $row->username )
					->setCellValue( 'D' . $i , $currStageTimer . ' Hours' )
					->setCellValue( 'E' . $i , $row->previoustage )
					->setCellValue( 'F' . $i , $row->previoususername )
					->setCellValue( 'G' . $i , $prevStageTimer . ' Hours' )
					->setCellValue( 'H' . $i , $completedOnTime )
					->setCellValue( 'I' . $i , $row->datelogged )
					->setCellValue( 'J' . $i , $row->applicationtypemarker );

				$i++;

			endforeach;

			// Styling

			$headerStyle = new PHPExcel_Style();

			$headerStyle->applyFromArray(
				array(
					'alignment' => array(
						'wrap' => true ,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
					) ,
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID ,
						'color' => array( 'argb' => 'FF003366' )
					) ,
					'font' => array(
						'color' => array( 'argb' => 'FFFFFFFF' ) ,
						'name' => 'Arial' ,
						'size' => '9'
					) ,
					'borders' => array(
						'bottom' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN )
					)

				) );

			$rowStyle = new PHPExcel_Style();

			$rowStyle->applyFromArray(
				array(
					'alignment' => array(
						'wrap' => true ,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
					) ,
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID ,
						'color' => array( 'argb' => 'FFFAFAFA' )
					) ,
					'font' => array(
						'color' => array( 'argb' => 'FF666666' ) ,
						'name' => 'Arial' ,
						'size' => '9'
					) ,
					'borders' => array(
						'bottom' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN , 'color' => array( 'argb' => 'FFA1A1A1' ) )
					)

				) );

			$objPHPExcel->getActiveSheet()->setSharedStyle( $headerStyle , "A1:J1" );
			$objPHPExcel->getActiveSheet()->setSharedStyle( $rowStyle , "A2:J100" );

			$objPHPExcel->getActiveSheet()->getRowDimension( '1' )->setRowHeight( 20 );

			$p = 2;

			foreach ( $this->data as $row ) :

				$objPHPExcel->getActiveSheet()->getRowDimension( (string)$p )->setRowHeight( 20 );

				$p++;

			endforeach;

			$objPHPExcel->getActiveSheet()->getColumnDimension( 'A' )->setWidth( 12 );
			$objPHPExcel->getActiveSheet()->getColumnDimension( 'B' )->setWidth( 24 );
			$objPHPExcel->getActiveSheet()->getColumnDimension( 'C' )->setWidth( 28 );
			$objPHPExcel->getActiveSheet()->getColumnDimension( 'D' )->setWidth( 12 );
			$objPHPExcel->getActiveSheet()->getColumnDimension( 'E' )->setWidth( 36 );
			$objPHPExcel->getActiveSheet()->getColumnDimension( 'F' )->setWidth( 24 );
			$objPHPExcel->getActiveSheet()->getColumnDimension( 'G' )->setWidth( 18 );
			$objPHPExcel->getActiveSheet()->getColumnDimension( 'H' )->setWidth( 14 );
			$objPHPExcel->getActiveSheet()->getColumnDimension( 'I' )->setWidth( 24 );
			$objPHPExcel->getActiveSheet()->getColumnDimension( 'J' )->setWidth( 12 );

			// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle( 'Reports for ' . date( 'Y-m-d' ) );


			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex( 0 );

			$filename = 'Reports ' . strtolower( $this->data['0']->stagedescription ) . ' from ' . trim( $params['from-date'] ) . ' to ' . $params['to-date'];

			// Redirect output to a client’s web browser (Excel2007)
			header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
			header( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' );
			header( 'Cache-Control: max-age=0' );

			$objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel , 'Excel2007' );
			$objWriter->save( 'php://output' );
			exit;
		else :
			die( 'Export method is experiencing technical errors please contact your administrator' );
		endif;

	}
	public function getReportData() {
		$sql = "SELECT * FROM applications ORDER BY control ASC";
		$statement = $this->prepare($sql);
		$statement->execute();
		

		return $this->fetchObjects($statement);
	}
	public function getGrid($control) {
		$sql = "SELECT * FROM applicationstages 
				INNER JOIN audit ON applicationstages.control = audit.applicationstagecontrol WHERE audit.applicationcontrol = ?";
		$statement = $this->prepare($sql);

		$statement->execute(array($control));

		return $this->fetchObjects($statement);
	}
	public function getStages() {
		$sql = "SELECT * FROM applicationstages ORDER BY stageno ASC";
		$statement = $this->prepare($sql);
		$statement->execute();
		return $this->fetchObjects($statement);
	}
	public function getStageControls() {
		$sql = "SELECT applicationcontrol,GROUP_CONCAT(',',applicationstagecontrol) as controls FROM audit GROUP BY applicationcontrol";
		$statement = $this->prepare($sql);
		$statement->execute(array($applicationcontrol));
		$data = array();
		while($row = $this->fetchObject($statement)) {
			
			$data[$row->applicationcontrol] = $row->controls;
		}
		return $data;
	}

}