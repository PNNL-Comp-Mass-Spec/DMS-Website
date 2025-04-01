<?php
namespace App\Libraries;

/**
 *
 */
class List_report_pager {

    private $cur_row_function   = 'tableRep.setListReportCurRow'; // Javascript function that sets current row
    private $total_rows         = ''; // Total number of items (database results)
    private $per_page           = 10; // Max number of items you want shown per page
    private $num_links          =  5; // Number of "digit" links to show before/after the currently viewed page
    private $cur_page           =  0; // The current page being viewed
    private $num_pages          = 0;
    private $first_link         = '';
    private $next_link          = '';
    private $prev_link          = '';
    private $last_link          = '';
    private $full_tag_open      = '';
    private $full_tag_close     = '';
    private $first_tag_open     = '';
    private $first_tag_close    = '&nbsp;';
    private $last_tag_open      = '&nbsp;';
    private $last_tag_close     = '';
    private $cur_tag_open       = '&nbsp;<b>';
    private $cur_tag_close      = '</b>';
    private $next_tag_open      = '&nbsp;';
    private $next_tag_close     = '&nbsp;';
    private $prev_tag_open      = '&nbsp;';
    private $prev_tag_close     = '';
    private $num_tag_open       = '&nbsp;';
    private $num_tag_close      = '';

    /**
     * Constructor
     * @param type $params
     */
    function __construct($params = array()) {
        if (count($params) > 0) {
            $this->initialize($params);
        }
        $this->first_link = expansion_link_icon('first');
        $this->next_link = expansion_link_icon('next');
        $this->prev_link = expansion_link_icon('prev');
        $this->last_link = expansion_link_icon('last');
    }

    /**
     * Initialize the pager
     * @param type $params
     */
    function initialize($params = array()) {
        if (count($params) > 0) {
            foreach ($params as $key => $val) {
                if (isset($this->$key)) {
                    $this->$key = $val;
                }
            }
        }
    }

    /**
     * Update the page number and row info
     * @param type $first_row
     * @param type $total_rows
     * @param type $per_page
     */
    function set($first_row, $total_rows, $per_page) {
        $this->total_rows = $total_rows;
        $this->per_page = $per_page;
        $this->num_pages = ceil($this->total_rows / $this->per_page);

        if (is_numeric($first_row)) {
            $this->cur_page = floor(($first_row / $this->per_page) + 1);
        } else {
            $this->cur_page = 1;
        }
        $this->cur_page = ($this->cur_page > $this->num_pages) ? $this->num_pages : $this->cur_page;
    }

    /**
     * Return a description of paging, for example Rows 251 through 375 of 2249
     * @return string
     */
    function create_stats($controller) {
        // If our item count or per-page total is zero there is no need to continue.
        if ($this->total_rows <= 0 || $this->per_page == 0) {
            return '';
        }

        $mrr = $controller->getPreferences()->get_preference('max_report_rows');

        $firstRowForPage = $this->first_row_for_page($this->cur_page); //($this->cur_page - 1) * $this->per_page;

        $start_row = ($firstRowForPage == 0) ? 1 : $firstRowForPage;

        $end_row = $start_row + $this->per_page - 1;
        if ($end_row > $this->total_rows) {
            $end_row = $this->total_rows;
        }

        $icon_adjust = cmd_link_icon('adjust');
        $page_sizer = '<a class="cmd_link_a" href="javascript:tableRep.setPageSize(' . $this->per_page . ', ' . $this->total_rows . ',' . $mrr . ')" title="Click to change setting for number of rows on a page">Set Rows ' . $icon_adjust . '</a>';
        $show_all = '<a class="cmd_link_a" href="javascript:tableRep.setPageSize(\'all\', ' . $this->total_rows . ',' . $mrr . ')" title="Click to show maximum allowed number of rows (' . $mrr . ') on the page">Max Rows ' . $icon_adjust . '</a>';
        $show_all = ($this->per_page < $this->total_rows) ? $show_all : '';
        //
//      return "&nbsp; Rows $start_row through $end_row of <span id='total_rowcount'>$this->total_rows</span> &nbsp;" . $page_sizer. " &nbsp; " .$show_all;
        return "<span class='LRepPagerCtl'>Rows $start_row through $end_row of <span id='total_rowcount'>$this->total_rows</span> </span> <span class='LRepPagerCtl'>" . $page_sizer . "</span> <span class='LRepPagerCtl'>" . $show_all . "</span>";
    }

    /**
     * Make the JavaScript invocation that sets the current page
     * @param type $row
     * @return type
     */
    private function page_link($row) {
        return "javascript:" . $this->cur_row_function . "('" . $row . "')";
    }

    /**
     * Determine the first row number visible on the page
     * @param type $page
     * @return type
     */
    private function first_row_for_page($page) {
        return (($page - 1) * $this->per_page) + 1;
    }

    /**
     * Create the links: First, Previous, Next, Last
     * @return string HTML
     */
    function create_links() {
        // There are certain situations where no action if possible
        if ($this->total_rows <= 0 || $this->per_page == 0 || $this->num_pages == 1) {
            return '';
        }

        // Unused:
        // $uri_page_number = (($this->cur_page - 1) * $this->per_page);

        $output = '';

        // Render the "First" link
        if ($this->cur_page > 1) {
            $output .= $this->first_tag_open . '<a href="' . $this->page_link('1') . '">' . $this->first_link . '</a>' . $this->first_tag_close;
        } else {
            $output .= $this->first_tag_open . $this->first_link . $this->first_tag_close;
        }

        // Render the "previous" link
        if ($this->cur_page > 1) {
            $i = $this->first_row_for_page($this->cur_page - 1);
            $output .= $this->prev_tag_open . '<a href="' . $this->page_link($i) . '">' . $this->prev_link . '</a>' . $this->prev_tag_close;
        } else {
            $output .= $this->prev_tag_open . $this->prev_link . $this->prev_tag_close;
        }

        // Calculate the start and end numbers. These determine which number to start and end the digit links with
        $start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
        $end = (($this->cur_page + $this->num_links) < $this->num_pages) ? $this->cur_page + $this->num_links : $this->num_pages;

        // Write the digit links
        for ($page = $start - 1; $page <= $end; $page++) {
            $i = $this->first_row_for_page($page);
            if ($i >= 0) {
                if ($this->cur_page == $page) {
                    $output .= $this->cur_tag_open . $page . $this->cur_tag_close; // Current page
                } else {
                    $n = ($i == 0) ? '1' : $i;
                    $output .= $this->num_tag_open . '<a class="LRepPagerLink" href="' . $this->page_link($n) . '">' . $page . '</a>' . $this->num_tag_close;
                }
            }
        }

        // Render the "next" link
        if ($this->cur_page < $this->num_pages) {
            $i = $this->first_row_for_page($this->cur_page + 1);
            $output .= $this->next_tag_open . '<a href="' . $this->page_link($i) . '">' . $this->next_link . '</a>' . $this->next_tag_close;
        } else {
            $output .= $this->next_tag_open . $this->next_link . $this->next_tag_close;
        }

        // Render the "Last" link
        if ($this->cur_page < $this->num_pages) {
            $i = $this->first_row_for_page($this->num_pages); //(($this->num_pages * $this->per_page) - $this->per_page);
            $output .= $this->last_tag_open . '<a href="' . $this->page_link($i) . '">' . $this->last_link . '</a>' . $this->last_tag_close;
        } else {
            $output .= $this->last_tag_open . $this->last_link . $this->last_tag_close;
        }

        // Add the wrapper HTML if exists
        $output = $this->full_tag_open . $output . $this->full_tag_close;

        return $output;
    }
}
?>
