<?php

require_once "web_scraper/support/web_browser.php";
require_once "web_scraper/support/tag_filter.php";

class BaseAdapter
{

    public function __construct($url)
    {
        $this->htmloptions = TagFilter::GetHTMLOptions();
        $this->url = $url;
        $this->web = new WebBrowser(array("extractforms" => true));
        $this->result = $this->web->Process($url);

        if (!$this->result["success"]) {
            echo "Error retrieving URL.  " . $this->result["error"] . "\n";
            exit();
        }

        if ($this->result["response"]["code"] != 200) {
            echo "Error retrieving URL.  Server returned:  " . $this->result["response"]["code"] . " " . $result["response"]["meaning"] . "\n";
            exit();
        }

    }
}
