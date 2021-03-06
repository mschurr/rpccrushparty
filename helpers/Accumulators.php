<?php
/* Provides a data structure that remembers at all times the $size best items ever added to the structure.
   The "best" items are determined by the outcome of the ->compare and ->isBetter functions on scores.
   To retireve the items from the array, call ->toArray(). 

   Implemented using a heaps for fast access time. To implement a MinAccumulator, we actually use a MaxHeap.
   The MaxHeap ensures that the maximum (worst) value is always at the top of the heap. When adding new values, 
   we check to see if they are better than the worst value. If they are, we pop the worst value and add the new
   one.

   This means that iterating over the heap will return the top best $maxSize values in order from worst to best,
     but this that is easily reversible and well worth the saved time.
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

	public abstract /*bool*/ function isBetter($new, $worst);
	
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
	public /*int*/ function compare(/*mixed*/ $value1, /*mixed*/ $value2)
	{
		if($value2['score'] > $value1['score'])
			return 1;
		if($value2['score'] < $value1['score'])
			return -1;
		return 0;
	}

	/* Returns whether or not an item should be added to the heap. */
	public /*bool*/ function isBetter($new, $worst)
	{
		if($new['score'] < $worst['score'])
			return false;
		return true;
	}
}

class MinAccumulator extends Accumulator
{
	/* Controls the ordering of items within the heap. */
	public /*int*/ function compare(/*mixed*/ $value1, /*mixed*/ $value2)
	{
		if($value2['score'] > $value1['score'])
			return -1;
		if($value2['score'] < $value1['score'])
			return 1;
		return 0;
	}

	/* Returns whether or not an item should be added to the heap. */
	public /*bool*/ function isBetter($new, $worst)
	{
		if($new['score'] > $worst['score'])
			return false;
		return true;
	}
}