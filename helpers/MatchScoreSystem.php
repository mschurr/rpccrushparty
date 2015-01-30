<?php

interface IMatchScoreSystem {
	public /*double*/ function matchScore($participant, $otherParticipant);
	public /*String*/ function formatScore($score, $best=true);
}

/**
 * Provides the match scoring algorithm.
 */
class MatchScoreSystem implements IMatchScoreSystem
{
	public /*double*/ function matchScore($participant, $otherParticipant)
	{
		$score = 0;
		$possible = 0;

		for($q = 0; $q < sizeof(SurveyConstants::$questions); $q++) {
			$points = $this->pointsForQuestion($q+1, $participant['question_'.$q], $otherParticipant['question_'.$q]);
			$score += $points[0];
			$possible += $points[1];
		}

		return $score / $possible;
	}

	public /*String*/ function formatScore($score, $best=true)
	{
		if($best === true) {
				$score = ($score/0.85);
				if($score > 0.9999)
				$score = 0.9999;
		}

		return number_format($score*100,2).'%';
	}

	public /*array<double>*/ function pointsForQuestion($q, $a1, $a2)
	{
		// All questions are weighted equally.
		$possible = 10;

		// 1) Personality
		if($q == 1) {
			$map = array(
				array(0, 3, 4),
				array(0, 5, 4),
				array(1, 2, 7),
				array(3, 5, 7),
			);
			$points = $this->pointsFromMap($map, $a1, $a2, 0, $possible) * 1.5;
			$possible = 15;
		}

		// 2) Purity Score
		else if($q == 2) {
			$possible = 30;
			$points = (5 - abs($a2 - $a1)) * 6;
		}

		// 3) TV

		// 4) Favorite Drink
		else if($q == 4) {
			$points = $this->pointsFromMap(array(
				array(0, 1, 4),
				array(0, 2, 4),
				array(0, 3, 2),
				array(0, 4, 2),
				array(0, 5, 3),
				array(0, 6, 4),
				array(1, 2, 5),
				array(1, 6, 5),
				array(2, 6, 5),
				array(3, 4, 7),
				array(3, 5, 7),
				array(4, 5, 7)
			), $a1, $a2, 0, $possible) * 1.5;
			$possible = 15;
		}

		// 5) Hook-up Spot
		else if($q == 5) {
			$points = $this->pointsFromMap(array(
				array(0, 5, 3),
				array(0, 4, 3),
				array(1, 2, 3),
				array(1, 3, 5),
			), $a1, $a2, 1, $possible) * 1.5;
			$possible = 15;
		}

		// 6) RPC Event

		// 7) Favorite Professor
		else if($q == 7) {
			$points = $this->pointsFromMap(array(
			), $a1, $a2, 3, $possible);
		}

		// 8) Food
		else if($q == 8) {
			$points = $this->pointsFromMap(array(
			), $a1, $a2, 3, $possible);
		}

		// 9) Ideal Date
		else if($q == 9) {
			$points = $this->pointsFromMap(array(
			), $a1, $a2, 0, $possible) * 1.5;
			$possible = 15;
		}

		// 10) Pick-Up Lines
		else if($q == 10) {
			$points = $this->pointsFromMap(array(
				array(0, 2, 5),
				array(1, 3, 2),
				array(1, 4, 5),
				array(3, 4, 2)
			), $a1, $a2, 1, $possible) * 1.5;
			$possible = 15;
		}

		// 11) First Physical Move
		else if($q == 11) {
			$points = $this->pointsFromMap(array(
				array(0, 1, 4),
				array(0, 3, 6),
				array(0, 4, 2),
				array(0, 5, 3),
				array(1, 2, 4),
				array(1, 3, 2),
				array(1, 4, 3),
				array(3, 5, 4)
			), $a1, $a2, 1, $possible) * 1.5;
			$possible = 15;
		}

		// 12) Spirit Animal
		else if($q == 12) {
			$points = $this->pointsFromMap(array(
			), $a1, $a2, 3, $possible);
		}

		// 13) Drinking
		else if($q == 13) {
			$points = $this->pointsFromMap(array(
				array(0, 1, 8),
				array(0, 2, 6),
				array(0, 3, 4),
				array(0, 4, 2),
				array(1, 2, 6),
				array(1, 3, 4),
				array(1, 4, 2),
				array(2, 3, 6),
				array(2, 4, 4),
				array(2, 5, 2),
				array(3, 4, 7),
				array(3, 5, 4),
				array(4, 5, 6)
			), $a1, $a2, 0, $possible) * 1.5;
			$possible = 15;
		}

		// 14) Music
		else if($q == 14) {
			$points = $this->pointsFromMap(array(
				array(0, 2, 5),
				array(1, 6, 5),
				array(2, 3, 2),
				array(3, 4, 4)
			), $a1, $a2, 1, $possible) * 1.5;
			$possible = 15;
		}

		// 15) Ex Text
		else if($q == 15) {
			$points = $this->pointsFromMap(array(
				array(0, 1, 3),
				array(0, 2, 5),
				array(0, 3, 3),
				array(0, 4, 3),
				array(0, 5, 1),
				array(1, 2, 3),
				array(1, 3, 3),
				array(1, 4, 3),
				array(1, 5, 1),
				array(2, 3, 3),
				array(2, 4, 7),
				array(2, 5, 1),
				array(3, 4, 1),
				array(3, 5, 1),
				array(4, 5, 1)
			), $a1, $a2, 1, $possible);
		}

		// 16) GS Cookie
		// 17) College Course

		// 18) HIMYM Characters
		else if($q == 18) {
			$points = $this->pointsFromMap(array(
				array(0, 1, 4),
				array(0, 2, 3),
				array(0, 3, 4),
				array(1, 3, 6),
				array(2, 3, 4),
				array(2, 4, 6)
			), $a1, $a2, 2, $possible);
		}

		// 19) Most Important Quality
		else if($q == 19) {
			$points = $this->pointsFromMap(array(
				array(0, 1, 4),
				array(1, 2, 4),
				array(1, 3, 4),
				array(1, 4, 3),
				array(3, 4, 4)
			), $a1, $a2, 0, $possible) * 1.5;
			$possible = 15;
		}

		// 20) Million Dollars
		else if($q == 20) {
			$points = $this->pointsFromMap(array(
			), $a1, $a2, 0, $possible) * 1.5;
			$possible = 15;
		}

		// (Default) All other questions are binary 0 or 1.
		else {
			$points = ($a1 == $a2 ? $possible : 0);
		}

		return array($points, $possible);
	}

	public function pointsFromMap($map, $a1, $a2, $notInMapPoints, $sameAnswerPoints) {
		if($a1 == $a2)
			return $sameAnswerPoints;

		foreach($map as $distance) {
			if($distance[0] == $a1 && $distance[1] == $a2
			|| $distance[1] == $a1 && $distance[0] == $a2)
				return $distance[2];
		}

		return $notInMapPoints;
	}
}
