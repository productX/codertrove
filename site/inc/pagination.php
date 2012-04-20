<?php

/**
 * A (non-Ajax) pagination class.  It uses a sql query, number of records per page and 
 * a get parameter "page" to determine which part of the result to gather from the database.  
 * In the process, it also sets variables such as number of pages for rendering the paging nav.
 *
 * You can overwrite the render_nav function if you'd like to create your own paging navigation or
 * add a function, render_page to render the results of a page in a custom way.  Most of the 
 * value of this class comes purely from the constructor.
 */
class Pagination {

	public $total_rows;
	public $num_pages;
	public $page_rs;
	public $num_recs_rs;
	public $page;
	public $recs_per_page;

	// IMPORTANT: All sql queries must be selects
	function __construct($conn, $sql, $recs_per_page) {
		$this->recs_per_page = intval($recs_per_page);
		$this->page = intval($_GET['page']) < 1 ? 1 : intval($_GET['page']);
		$this->page_rs = array();
		// Hmm is currently case sensitive...
		$sql = ereg_replace("^SELECT ", "SELECT SQL_CALC_FOUND_ROWS ", $sql);

		if ($this->page < 1) {
			$this->page = 1;
			$sql .= " LIMIT 0, " . $this->recs_per_page;
		}
		else {
			$sql .= " LIMIT " . (($this->page - 1) * $this->recs_per_page) . 
				", " . $this->recs_per_page;
		}
		$rs = mysql_query($sql, $conn);
		$this->num_recs_rs = mysql_num_rows($rs);

		if ($this->num_recs_rs > 0) {
			while ($row = mysql_fetch_array($rs)) {
				$this->page_rs[] = $row;
			}
		}

		$sql = "SELECT FOUND_ROWS() as found_rows";
		$rs = mysql_query($sql, $conn);
		$row = mysql_fetch_array($rs);
		$this->total_rows = $row['found_rows'];
		$this->num_pages = ceil($this->total_rows / $this->recs_per_page);
	}

	function render_nav($url) {
		if ($this->num_pages <= 1) {
			return;
		}
?>
		<style> 
			.current_page {
				font-weight: bold;
			}
		</style>
<?php
		if ($this->page > 1) {
			$prev_page = $this->page - 1;
			echo "<a href='{$url}?page={$prev_page}'> < Prev </a>";
		}

		for($i = 1; $i <= $this->num_pages; $i++) {
			if ($i == 1 && $this->page != 1) {
				echo "|";
			}
			if ($i == $this->page) {
				echo "<span class='current_page'> $i </span>";
			}
			else {
				echo "<span class='paging_link'> <a href='{$url}?page=$i'> $i </a> </span>";
			}
			if ($i != $this->num_pages) {
				echo "|";
			}
		}

		if ($this->page < $this->num_pages) {
			$next_page = $this->page + 1;
			echo "<a href='{$url}?page={$next_page}'> | Next > </a>";
		}
	}
}

?>
