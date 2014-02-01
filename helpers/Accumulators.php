<?php
/* Provides a data structure that remembers at all times the $size best items ever added to the structure.
   The "best" items are determined by the outcome of the ->compare(a,b) function on scores.
   To retireve the items from the array, call ->toArray(). 

   Note: This structure is slow; operations occur in O(N) time. It can be done faster, but I don't have enoguh time.
   */
abstract class Accumulator
{
	protected /*int*/ $size;
	protected /*array<Object>*/ $items;
	protected /*array<double>*/ $scores;
	
	public function __construct(/*int*/ $size)
	{
		$this->size = $size;
		$this->items = array();
		$this->scores = array();

		for($i = 0; $i < $size; $i++) {
			 $this->items[$i] = null;
			 $this->scores[$i] = 0;
		}
	}

	public function add(/*Object*/ $item, /*double*/ $score)
	{
		for($i = 0; $i < $this->size; $i++) {
			if($this->items[$i] === null || $this->compare($score, $this->scores[$i]) > 0) {
				$this->items[$i] = $item;
				$this->scores[$i] = $score;
				break;
			}
		}
	}

	public function toArray()
	{
		$array = array();

		for($i = 0; $i < $this->size; $i++) {
			if($this->items[$i] === null)
				break;

			$array[] = array(
				'item' => $this->items[$i],
				'score' => $this->scores[$i]
			);
		}

		return $array;
	}
	
	public abstract /*int*/ function compare(/*double*/ $a, /*double*/ $b);
}

class MaxAccumulator extends Accumulator
{
	public function compare($a, $b)
	{
		return $a > $b;
	}
}

class MinAccumulator extends Accumulator
{
	public function compare($b, $a)
	{
		return $a < $b;
	}
}