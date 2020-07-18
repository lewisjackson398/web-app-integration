<?php
abstract class WebPage {

	protected $pageStart;
	protected $header; 
	protected $main; 
	protected $footer; 
	protected $pageEnd;

	function __construct($pageTitle, $pageHeading1, $footerText) {
		$this->pageStart = $this->makePageStart($pageTitle);
		$this->header = $this->makeHeader($pageHeading1);
		$this->main = "";
		$this->footer = $this->makeFooter($footerText);
		$this->pageEnd = $this->makePageEnd();
	}

	/**
  	* @return string The initial HTML for a webpage
  	*
  	* @param string $pageTitle The title for the webpage
  	*/
	protected function makePageStart($pageTitle) {

		$mycss = $this->makeCSS();

		return <<< PAGESTART
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
  	<title>$pageTitle</title>
  	$mycss 
</head>
<body>

PAGESTART;
	}

	/**
  	* Abstract function 
  	*
  	* This has changed from version 7.1.x
  	*/
	abstract protected function makeCSS();

	/**
  	* @return string The header and h1 for a webpage
  	*
  	* @param string $pageHeading1 The h1 for the webpage
  	*/
	protected function makeHeader($pageHeading1) {
		return <<< HEADER
    <header class="container">
        <h1>$pageHeading1</h1>
    </header>

HEADER;
	}

	/**
  	* @return string The contents of the webpage wrapped in main tags.
  	*
  	* @param string $main The content of the main block 
  	*/
	protected function makeMain($main) {
		return <<< MAIN
    <main class="container">
        $main
    </main>

MAIN;
	}

	/**
  	* @return string The footer section and footer text.
  	*
  	* @param string $footer The content of the footer block
  	*/
	protected function makeFooter($footerText) {
		return <<< FOOTER
		<footer id="sticky-footer" class="py-4 bg-dark text-white-50">
		<div class="container text-center">
		  <small>2020, Lewis Jackson, W16018797</small>
		</div>
	  </footer>
FOOTER;
	}

	/**
  	* Abstract function 
  	*
  	* This has changed from version 7.1.x
  	*/
	abstract protected function makeJS();

	/**
  	* @return string Final closing tags for webpage. JS added at end of body.
  	*/
	protected function makePageEnd() {
		$myJS = $this->makeJS();
		return <<< PAGEEND
 $myJS
 </body>
</html>

PAGEEND;
	}

	/**
  	* This is a public fuction that can be used to add text to the webpage
  	*
  	* @param string $text Content to add to the webpage
  	*/
	public function addToBody($text) {
	      $this->main .= $text; 
	}

	/**
  	* This is a public fuction will return the completed webpage
  	*
  	* @return String The HTML for a compelete webpage
  	*/
	public function getPage() {

		$this->main = $this->makeMain($this->main);

		return 	$this->pageStart.
				$this->header.
				$this->main.
				$this->footer.
				$this->pageEnd; 
	}
}
