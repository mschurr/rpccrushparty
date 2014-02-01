<?php
/* Provides a data structure that remembers at all times the $size best items ever added to the structure.
   The "best" items are determined by the outcome of the ->compare(a,b) function on scores.
   To retireve the items from the array, call ->toArray(). 

   Implemented using a heap for fast access times.
   */

abstract class Accumulator extends SplHeap
{
	protected $maxSize;

	public function __construct(/*int*/ $maxSize = 10)
	{
		$this->maxSize = $maxSize;
	}

	public /*void*/ function insert(/*mixed*/ $value)
	{
		if(sizeof($this) >= $this->maxSize) {
			if($this->isBetter($value, parent::top()) === true) {
				parent::extract();
				parent::insert($value);
			}
		}
		else {
			parent::insert($value);
		}
	}

	public /*void*/ function add(/*mixed*/  $item, /*double*/ $score)
	{
		$this->insert(
			array(
				'item' => $item,
				'score' => $score
			)
		);
	}

	protected abstract /*bool*/ function isBetter($new, $worst);
	//protected abstract /*int*/ function compare(/*mixed*/ $value1, /*mixed*/ $value2); // Order should place objects opposed to order you actually want them in

	/* Returns items as an array of arraymaps in sorted order by score. */
	public function toArray()
	{
		$array = array();

		foreach($this as $item) {
			array_unshift($array, $item);
		}

		return $array;
	}
}

class MaxAccumulator extends Accumulator
{
	/* Controls the ordering of items within the heap. */
	protected /*int*/ function compare(/*mixed*/ $value1, /*mixed*/ $value2)
	{
		if($value2['score'] > $value1['score'])
			return 1;
		if($value2['score'] < $value1['score'])
			return -1;
		return 0;
	}

	/* Returns whether or not an item should be added to the heap. */
	protected /*bool*/ function isBetter($new, $worst)
	{
		if($new['score'] < $worst['score'])
			return false;
		return true;
	}
}

class MinAccumulator extends Accumulator
{
	/* Controls the ordering of items within the heap. */
	protected /*int*/ function compare(/*mixed*/ $value1, /*mixed*/ $value2)
	{
		if($value2['score'] > $value1['score'])
			return -1;
		if($value2['score'] < $value1['score'])
			return 1;
		return 0;
	}

	/* Returns whether or not an item should be added to the heap. */
	protected /*bool*/ function isBetter($new, $worst)
	{
		if($new['score'] > $worst['score'])
			return false;
		return true;
	}
}