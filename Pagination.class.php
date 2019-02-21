<?php
/*
-- Hamilton Cline
-- www.hamiltondraws.com
-- hamdiggy@gmail.com

To use, initialize with an object including limit, offset, total, and template
    $paginate = new Pagination((object)array(
        "limit"=>10,
        "offset"=>0,
        "totalitems"=>100,
        "template"=>'?item={{id}}'
        ));
Then call $paginate->makePagination() to print the links
All created variables are available publicly such as offsetstart and totalpages
*/
class Pagination {
    public $limit = 0;
    public $offset = 0;
    public $totalitems = 0;
    public $lastitem = 0;
    public $offsetstart = 0;

    public $totalpages = 0;
    public $paginateamount = 3;

    private $template = "";

    function __construct($params = null) {
        $this->limit = @$params->limit ?: 10;
        $this->offset = @$params->offset ?: 0;
        $this->totalitems = @$params->totalitems ?: 0;
        $this->template = @$params->template ?: "?item={{id}}";

        $this->lastitem = max(0,$this->totalitems - 1) ?: 0;
        $this->offsetstart = $this->lastitem - ($this->limit * $this->offset) ?: 0;

        $this->startitem = $this->limit * $this->offset ?: 0;
        $this->lastoffsetitem = min($this->startitem + $this->limit, $this->totalitems) ?: 0;

        $this->totalpages = floor($this->totalitems / $this->limit);
        $this->lastpage = max(0,$this->totalpages - 1) ?: 0;
    }

    private function mustache($id){return preg_replace('/\{\{id}}/',$id,$this->template);}

    // echo "$this->offset";
    public function makePagination(){
        echo "<ul class='pagination'>";

        if($this->offset > 0) {
            echo "<li><a href='".$this->mustache($this->offset - 1)."' rel='previous'>&lt;</a></li>";
        }

        if($this->offset - $this->paginateamount>0) {
            echo "<li><a href='".$this->mustache(0)."'>0</a></li>";
        }

        for($i = $this->offset - $this->paginateamount; $i < $this->offset; $i++) {
            if($i>=0) echo "<li><a href='".$this->mustache($i)."'>$i</a></li>";
        }

        echo "<li><span>{$this->offset}</span></li>";
        
        for($i = $this->offset + 1; $i < $this->totalpages && $i <= $this->offset + $this->paginateamount; $i++) {
            echo "<li><a href='".$this->mustache($i)."'>$i</a></li>";
        }
        
        if($this->offset + $this->paginateamount < $this->lastpage) {
            echo "<li><a href='".$this->mustache($this->lastpage)."'>{$this->lastpage}</a></li>";
        }

        if($this->offset < $this->lastpage) {
            echo "<li><a href='".$this->mustache($this->offset + 1)."' rel='next'>&gt;</a></li>";
        }

        echo "</ul>";
    }

}
