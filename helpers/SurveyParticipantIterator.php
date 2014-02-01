<?php

/**
 * Provides an interface to iterator over all participants in the survey. The iteration occurs in batches of $batchSize,
 *  preventing more than $batchSize records from being loaded into memory at the same time.
 */
class SurveyParticipantIterator implements Iterator, Countable
{
	protected /*int*/ $batchSize;
	protected /*int*/ $totalRows;
	protected /*Database*/ $db;
	protected /*int*/ $currentOffset;
	protected /*DB_Result*/ $buffer;

	public function __construct(/*int*/ $batchSize = 25)
	{
		$this->batchSize = $batchSize;
		$this->db = App::getDatabase();
		$this->currentOffset = 0;
		$this->buffer = null;
		return $this;
	}

	public /*int*/ function count()
	{
		if($this->totalRows === null) {
			$q = $this->db->query("SELECT COUNT(`id`) AS `count` FROM `surveys`;");
			$this->totalRows = $q->row['count'];
		}

		return $this->totalRows;
	}

	public /*array<String:mixed>*/ function current()
	{
		if($this->buffer === null) {
			$page = ceil($this->currentOffset/$this->batchSize);
			if($page < 0) $page = 1;
			$this->buffer = $this->db->query("SELECT * FROM `surveys` ORDER BY `id` ASC LIMIT ".($page*$this->batchSize).",".$this->batchSize.";");
		}
		
		return $this->buffer->rows[$this->currentOffset % $this->batchSize];
	}

	public /*int*/ function key()
	{
		return $this->currentOffset;
	}

	public /*void*/ function next()
	{
		$this->currentOffset++;
		if($this->currentOffset % $this->batchSize == 0)
			$this->buffer = null;
	}

	public /*void*/ function rewind()
	{
		if($this->totalRows === null) $this->count();
		$this->currentOffset = 0;
		$this->buffer = null;
	}

	public /*bool*/ function valid()
	{
		return $this->currentOffset < $this->totalRows;
	}
}