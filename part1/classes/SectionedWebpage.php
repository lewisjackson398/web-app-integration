<?php
class SectionedWebpage extends WebPageWithNav
{
    /**
     * SectionedWebpage constructor.
     * @param $pageTitle
     * @param $pageHeading1
     * @param $navItems
     * @param $footerText
     */
    function __construct($pageTitle, $pageHeading1, $navItems, $footerText) {
        $this->pageStart = $this->makePageStart($pageTitle);
        $this->header = $this->makeHeader($pageHeading1);
        $this->nav = $this->makeNav($navItems);
        $this->main = "";
        $this->footer = $this->makeFooter($footerText);
        $this->pageEnd = $this->makePageEnd();

    }

    public function addApi($endpoint, $name, $text){
        $section = "
        <h1>$name</h1>\n 
        <div>$text</div>
        <a href='$endpoint'>$endpoint</a>
        ";
        $this->addToBody($section);
    }

    /**
     * Override the parents method
     */
    public function getPage() {

        $this->main = $this->makeMain($this->main);

        return 	$this->pageStart.
        $this->header.
        $this->nav.
        $this->main.
        $this->footer.
        $this->pageEnd;
    }


}