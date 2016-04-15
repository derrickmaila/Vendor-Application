<?php
class PDF_Class {
	private $content;
	private $pdffile;
	private $pdfuniqfile;

	public function setContent($html ="") {
		$this->content = $html;

		$uniqfile = uniqid().".html";
		file_put_contents("/tmp/".$uniqfile, $html);
		$this->pdfuniqfile = $uniqpdf = uniqid().".pdf";

		$cmd = shell_exec("/usr/bin/wkhtmltopdf.sh /tmp/{$uniqfile} /tmp/{$uniqpdf}");
		$this->pdffile = "/tmp/{$uniqpdf}";
		
		
		return $this->pdffile;
	}
	public function createPDF($filename = "") {
		header("Content-type:application/pdf");
		if($filename == "") {
			header("Content-Disposition:attachment;filename='{$this->pdfuniqfile}'");
		} else {
			header("Content-Disposition:attachment;filename='{$filename}'");
		}
		readfile($this->pdffile);
	}
}

?>