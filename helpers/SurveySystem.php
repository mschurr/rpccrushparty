<?php

import('SurveyConstants');
import('SurveyParticipantIterator');
import('Accumulators');

class SurveyMatcher
{
	/* Adds random records the database to test the match maker. */
	public function seed($amount=200)
	{
		$db = App::getDatabase();
		$fields = SurveyConstants::fields();
		$stmt = $db->prepare("INSERT INTO `surveys` (".sql_keys($fields).") VALUES (".sql_values($fields).");");
			
		for($i = 0; $i < $amount; $i++) {
			$data = array(
				':student_id' => '0',
				':send_results' => '0',
				':email_address' => 'person'.$i.'@rice.edu',
				':net_id' => 'p'.$i,
				':first_name' => 'Random',
				':last_name' => 'Person'.$i,
				':college' => rand(0, sizeof(SurveyConstants::$colleges)-1),
				':gender' => rand(0, sizeof(SurveyConstants::$genders)-1),
				':year' => rand(0, sizeof(SurveyConstants::$years)-1),
				':major' => rand(0, sizeof(SurveyConstants::$majors)-1)
			);

			// Interested
			$interestedAnything = false;
			for($j = 0; $j < sizeof(SurveyConstants::$genders); $j++) {
				$v = rand(0, 1);
				if($v == 1) $interestedAnything = true;
				$data[':interested_'.$j] = $v;
			}

			if(!$interestedAnything) {
				$data[':interested_'.rand(0,sizeof(SurveyConstants::$genders)-1)] = 1;
			}

			// Questions
			for($j = 0; $j < sizeof(SurveyConstants::$questions); $j++)
				$data[':question_'.$j] = rand(0, sizeof(SurveyConstants::$questions[$j]['options'])-1);

			$stmt->execute($data);
		}
	}

	/* Performs matching on all participants and displays the results, which can then be printed. */
	public function match($limit=null)
	{
		ini_set('max_execution_time', 1000);

		$participants = new SurveyParticipantIterator();

		echo '
		<!DOCTYPE HTML><html>
			<head>
				<link rel="stylesheet" type="text/css" href="'.URL::asset('css/master.css').'" />
			</head>
			<body>';

		$i = 0;

		foreach($participants as $participant) {
			if($limit !== null && $i >= $limit)
				break;

			$this->printMatches($participant);

			$i++;
		}

		echo '
			</body>
		</html>';
	}

	/* Returns the matches for participant with id. */
	public /*array*/ function getParticipantById($id)
	{
		$stmt = App::getDatabase()->prepare("SELECT * FROM `surveys` WHERE `id` = ? LIMIT 1;");
		$r = $stmt->execute($id);
		return $r->row;
	}

	/* Returns the matches for the provided participant. */
	public /*array*/ function matchesForParticipant($participant)
	{
		// Create an iterator over survey participants.
		$otherParticipants = new SurveyParticipantIterator();

		// Remember best and worst matches in this participant's year.
		$bestMatchesYear = new MaxAccumulator(10);
		$worstMatchesYear = new MinAccumulator(10);

		// Remember best and worst matches in this participant's college.
		$bestMatchesCollege = new MaxAccumulator(10);
		$worstMatchesCollege = new MinAccumulator(10);

		// Remember the best and worst matches in this participant's major.
		$bestMatchesMajor = new MaxAccumulator(10);
		$worstMatchesMajor = new MinAccumulator(10);

		// Remember the best and worst matches in all other participants.
		$bestMatchesRest = new MaxAccumulator(14);
		$worstMatchesRest = new MinAccumulator(14);

		// Iterate through all of the other participants.
		foreach($otherParticipants as $otherParticipant) {
			// IMPORTANT: Don't match the participant with himself/herself.
			if($participant['id'] === $otherParticipant['id'])
				continue;

			// IMPORTANT: Make sure match is feasible.
			if(!$this->matchIsFeasible($participant, $otherParticipant))
				continue;

			// Calculate the match score.
			$score = $this->matchScore($participant, $otherParticipant);

			// Add the match to the structures.
			$sorted = false;
			$oP = array(
				'name' => $otherParticipant['first_name'].' '.$otherParticipant['last_name'],
				'year' => $otherParticipant['year'],
				'college' => $otherParticipant['college'],
				'major' => $otherParticipant['major']
			);

			if($participant['year'] == $otherParticipant['year']) {
				$bestMatchesYear->add($oP, $score);
				$worstMatchesYear->add($oP, $score);
				$sorted = true;
			}

			if($participant['college'] == $otherParticipant['college']) {
				$bestMatchesCollege->add($oP, $score);
				$worstMatchesCollege->add($oP, $score);
				$sorted = true;
			}

			if($participant['major'] == $otherParticipant['major']) {
				$bestMatchesMajor->add($oP, $score);
				$worstMatchesMajor->add($oP, $score);
				$sorted = true;
			}

			if(!$sorted) {
				$bestMatchesRest->add($oP, $score);
				$worstMatchesRest->add($oP, $score);
			}
		}

		// The structures should now contain the best and worst matches.
		return array(
			'bestMatchesYear' => $bestMatchesYear->toArray(),
			'bestMatchesCollege' => $bestMatchesCollege->toArray(),
			'bestMatchesMajor' => $bestMatchesMajor->toArray(),
			'bestMatchesRest' => $bestMatchesRest->toArray(),
			'worstMatchesYear' => $worstMatchesYear->toArray(),
			'worstMatchesCollege' => $worstMatchesCollege->toArray(),
			'worstMatchesMajor' => $worstMatchesMajor->toArray(),
			'worstMatchesRest' => $worstMatchesRest->toArray()
		);
	}

	/* Returns whether or not a match is possible between two participants.*/
	public /*bool*/ function matchIsFeasible($participant, $otherParticipant)
	{
		// Make sure that the otherParticipant is a member of a gender this participant is interested in.
		if($participant['interested_'.$otherParticipant['gender']] != 1)
			return false;

		// Make sure that the otherParticipant is interested in this gender of this user.
		if($otherParticipant['interested_'.$participant['gender']] != 1)
			return false;

		return true;
	}

	/* Returns a match score between two participants. */
	public /*double*/ function matchScore($participant, $otherParticipant)
	{
		$score = 0;
		$possible = 0;

		// The score is the number of questions answered the same divided by the total number of questions.
		foreach($participant as $key => $value) {
			if(!str_startswith($key, 'question_'))
				continue;

			$possible += 1;

			if($value === $otherParticipant[$key])
				$score += 1;
		}

		return $score / $possible;
	}

	/* Prints out the matches for a given participant. */
	public function printMatches($participant)
	{
		$matches = $this->matchesForParticipant($participant);
		
		echo '
		<div class="_match">
		<div class="sponsor">Crush Party '.date('Y').' Results</div>
		<img src="'.URL::asset('img/crush.png').'" />
		<div class="name">'.$participant['first_name'].' '.$participant['last_name'].'</div>
		<div class="descr">'.SurveyConstants::$years[$participant['year']].', '.SurveyConstants::$colleges[$participant['college']].'</div>
		
		<div class="section">Best Matches</div>
		<div class="tbl">';

		$limit = 0;
		foreach($matches as $type => $people)
		{
			if($limit >= 3) break;
			if(str_startswith($type, 'best')) {
				$this->drawMatches($type,$people);
				$limit++;
			}
		}

		echo '</div>';

		$this->drawMatchesWide('bestMatchesRest', $matches['bestMatchesRest']);

		echo '
		<div class="section">Worst Matches</div>
		<div class="tbl">';

		$limit = 0;
		foreach($matches as $type => $people)
		{
			if($limit >= 3) break;
			if(str_startswith($type, 'worst')) {
				$this->drawMatches($type,$people);
				$limit++;
			}
		}

		echo '</div>';


		$this->drawMatchesWide('worstMatchesRest', $matches['worstMatchesRest']);


		echo '</div>';

		//App::getResponse()->dump($matches);
		// Include total participants (overall, for each college, for each year) broken down by gender
	}

	protected $headerNames = array(
		'bestMatchesCollege' => 'In Your College...',
		'bestMatchesYear' => 'In Your Year...',
		'bestMatchesMajor' => 'In Your Major...',
		'bestMatchesRest' => 'Everywhere else...',
		'worstMatchesCollege' => 'In Your College...',
		'worstMatchesYear' => 'In Your Year...',
		'worstMatchesMajor' => 'In Your Major...',
		'worstMatchesRest' => 'Everywhere else...'
	);

	public function drawMatchesWide($type, $people)
	{
		echo '<div class="tbl_bot">

			<div class="col_bot">
				<div class="type">'.$this->headerNames[$type].'</div>
				<table>';
					for($i = 0; $i < 4; $i++) {
						if(!isset($people[$i]))
							continue;
						$person = $people[$i];
						echo '
							<tr>
								<td class="num">'.($i+1).')</td>
								<td>'.$person['item']['name'].'<br />
								</td>
								<td class="perc">'.number_format($person['score']*100,2).'%</td>
							</tr>
							<tr><td colspan="3" class="detail">'.SurveyConstants::$years[$person['item']['year']].', 
								'.SurveyConstants::$colleges[$person['item']['college']].': '.SurveyConstants::$majors[$person['item']['major']].'</td></tr>
							';
					}
				echo '</table>
			</div>
			<div class="col_bot">
				<div class="indent">&nbsp;</div>
				<table>
					';
					for($i = 4; $i < 8; $i++) {
						if(!isset($people[$i]))
							continue;
						$person = $people[$i];
						echo '
							<tr>
								<td class="num">'.($i+1).')</td>
								<td>'.$person['item']['name'].'<br />
								</td>
								<td class="perc">'.number_format($person['score']*100,2).'%</td>
							</tr>
							<tr><td colspan="3" class="detail">'.SurveyConstants::$years[$person['item']['year']].', 
								'.SurveyConstants::$colleges[$person['item']['college']].': '.SurveyConstants::$majors[$person['item']['major']].'</td></tr>
							';
					}
					echo '
				</table>
			</div>
			<div class="col_bot">
				<div class="indent">&nbsp;</div>
				<table>';
				for($i = 8; $i < 12; $i++) {
						if(!isset($people[$i]))
							continue;
						$person = $people[$i];
						echo '
							<tr>
								<td class="num">'.($i+1).')</td>
								<td>'.$person['item']['name'].'<br />
								</td>
								<td class="perc">'.number_format($person['score']*100,2).'%</td>
							</tr>
							<tr><td colspan="3" class="detail">'.SurveyConstants::$years[$person['item']['year']].', 
								'.SurveyConstants::$colleges[$person['item']['college']].': '.SurveyConstants::$majors[$person['item']['major']].'</td></tr>
							';
					}
				echo '</table>
			</div>
		</div>';
	}
	
	public function drawMatches($type,$matches)
	{
		echo '
		<div class="col">
			<div class="type">'.$this->headerNames[$type].'</div><table>';

			$i = 1;
			foreach($matches as $person) {
				echo '
							<tr>
								<td class="num">'.($i).')</td>
								<td>'.$person['item']['name'].'<br />
								</td>
								<td class="perc">'.number_format($person['score']*100,2).'%</td>
							</tr>
							<tr><td colspan="3" class="detail">'.SurveyConstants::$years[$person['item']['year']].', 
								'.SurveyConstants::$colleges[$person['item']['college']].': '.SurveyConstants::$majors[$person['item']['major']].'</td></tr>
							';
				$i++;
			}/**/

			echo '</table>
		</div>
		';
	}
}

