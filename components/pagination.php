<?php

class Pagination {

    private $page;
    private $postsBy = '';
    private $link;
    private $totalPages;
    private $previousDisabled = '';
    private $nextDisabled = '';

    public function __construct($_page, $_postsBy, $_link, $_totalPages) {
        $this->page = $_page;
        $this->postsBy = $_postsBy;
        $this->link = $_link;
        $this->totalPages = $_totalPages;

        if (($this->page - 1) <= 0) $this->previousDisabled = 'disabled';
        if (($this->page + 1) > $this->totalPages) $this->nextDisabled = 'disabled';
    }

    public function display() {

        $pagination = '';

        $pagination .= '
            <nav aria-label="pagination">
            <ul class="pagination justify-content-center">
            <li class="page-item '.$this->previousDisabled.'">
                <a class="page-link" href="'.$this->link.''.$this->postsBy.'/page/'.($this->page - 1).'">Previous</a>
            </li>
        ';
        
        for ($pages=1; $pages<=$this->totalPages; $pages++) {
            $active = '';
            if ($pages == $this->page) $active = 'active';
            $pagination .= '
                <li class="page-item '.$active.'">
                    <a class="page-link" href="'.$this->link.''.$this->postsBy.'/page/'.$pages.'">'.$pages.'
                    </a>
                </li>
            ';
        }

        $pagination .= '
            <li class="page-item '.$this->nextDisabled.'">
                <a class="page-link" href="'.$this->link.''.$this->postsBy.'/page/'.($this->page + 1).'">Next</a>
            </li>
            </ul>
        </nav>
        ';

        echo $pagination;
    }

}

?>