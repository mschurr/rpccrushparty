<?php

class SurveyConstants
{
	public static $title = 'RPC Crush Party 2015 Survey';
	public static $eventDate = 'February 12th, 2015 9:00 PM CST';
	public static $expDate = 'February 11th, 2015 9:00 AM CST';

	public static $majors = array(
		'Undecided',
		'Other (Not Listed)',
		'Architecture',
		'Art',
		'Music',
		'Visual Arts',
		'Biochemistry',
		'Biology',
		'Ecology',
		'Business',
		'Computer Science',
		'Information Sciences',
		'Education',
		'Agricultural Engineering',
		'Environmental Engineering',
		'Biological Engineering',
		'Chemical Engineering',
		'Civil Engineering',
		'Electrical Engineering',
		'Mechanical Engineering',
		'Materials Engineering',
		'Environmental Studies',
		'English Language',
		'Language Studies and Linguistics',
		'Regional Language and Literature',
		'Romance Languages',
		'Comp. and Applied Mathematics',
		'Mathematics',
		'Statistics',
		'Philosophy',
		'Religious Studies',
		'Astrophysics',
		'Astronomy',
		'Chemistry',
		'Earth Sciences',
		'Physics',
		'Psychology',
		'Kinesiology and Exercise Science',
		'Sport and Fitness Management',
		'Area and Ethnic Studies',
		'Economics',
		'History',
		'Human Development',
		'Intl. Studies and Government',
		'Liberal Arts and Humanities',
		'Social Sciences',
		'Sociology',
		'Cognitive Science',
		'Women\'s Studies',
		'Political Science and Government',
		'Anthropology and Archaeology',
		'Public Policy',
	);

	public static $years = array(
		'Freshman',
		'Sophomore',
		'Junior',
		'Senior'
	);

	public static $genders = array(
		'Female',
		'Male'
	);

	public static $colleges = array(
		'Baker',
		'Brown',
		'Duncan',
		'Hanszen',
		'Jones',
		'Lovett',
		'Martel',
		'McMurtry',
		'Will Rice',
		'Sid Richardson',
		'Wiess'
	);

	public static $fields = array(
		//'id',
		'student_id',
		'net_id',
		'first_name',
		'last_name',
		'college',
		'email_address',
		'gender',
		'send_results',
		'year',
		'major'
		//'interested_%genders',
		//'question_%questions',
	);

	public static $questions = array(
		array(
			'text' => 'Which of the following best describes your personality?',
			'options' => array(
				'The Class Clown',
				'The Busy Bee',
				'The Reserved Intellectual',
				'The Jock',
				'The Hipster',
				'The Party Animal'
			)
		),
		array(
			'text' => 'What is your purity score? <a href="http://www.ricepuritytest.com/" target="_blank">[Link]</a>',
			'options' => array(
				'80-100',
				'60-79',
				'40-59',
				'20-39',
				'0-19'
			)
		),
		array(
			'text' => 'Of the following, what is your favorite television show?',
			'options' => array(
				'How I Met Your Mother',
				'Top Chef',
				'Big Bang Theory',
				'Downton Abbey',
				'The Bachelor(ette)',
				'Game of Thrones',
				'I don\'t really watch much television.'
			)
		),
		array(
			'text' => 'What is your favorite drink?',
			'options' => array(
				'Soda',
				'Coffee/Tea',
				'Milk',
				'Beer',
				'Liquor/Mixed Drinks',
				'Mike\'s Hard Lemonade',
				'Water'
			)
		),
		array(
			'text' => 'Of the following, what is your ideal hook up spot on campus?',
			'options' => array(
				'Fondren Basement',
				'45/90/180 Angles',
				'Public Party Dancefloor',
				'Skyspace',
				'Coffeehouse',
				'Sid Rich Big Room',
				'A bed... not neccesarily my own.'
			)
		),
		array(
			'text' => 'What is your favorite RPC event?',
			'options' => array(
				'Esperanza',
				'Beer Bike',
				'Gingerbread House Building',
				'Pub DJ Concert',
				'President and Dean\'s Study Break',
				'Night Bites'
			)
		),
		array(
			'text' => 'Who is your favorite professor?',
			'options' => array(
				'Dr. Zhiyong Gao',
				'Dr. Douglas Brinkley',
				'Dr. Mikki Hebl',
				'Dr. James Tour',
				'Dr. Alma Moon Novotny',
				'Dr. Dennis Huston',
				'Dr. Luay Nakleh',
				'I don\'t know any of these people.'
			)
		),
		array(
			'text' => 'What is your favorite type of food?',
			'options' => array(
				'Mexican',
				'Burgers',
				'Sushi',
				'Chinese',
				'Italian',
				'Indian'
			)
		),
		array(
			'text' => 'What is your ideal first date?',
			'options' => array(
				'Dinner and a movie',
				'Skydiving',
				'Going to Esperanza',
				'Grabbing drinks at a bar',
				'Dinner in the servery',
				'What\'s a date?'
			)
		),
		array(
			'text' => 'Which of the following pick up lines do you like the best?',
			'options' => array(
				'Did it hurt when you fell from heaven?',
				'If I were an enzyme, I would be DNA helicase so I could unzip your genes.',
				'Got a map? Because I\'m lost in your eyes.',
				'My name may not be Luna, but I sure know how to Lovegood.',
				'I wish I were your derivative so I could lie tangent to your curves.',
				'I lost my virginity... can I just have yours?'
			)
		),
		array(
			'text' => 'If you are on a date with someone you like, your first physical move is usually:',
			'options' => array(
				'Holding hands',
				'Kiss on the cheek',
				'A big sloppy wet kiss on the mouth... show them you mean business',
				'A playful shove or smack',
				'A long meaningful stare into your date\'s eyes',
				'Putting your finger into your date\'s ear'
			)
		),
		array(
			'text' => 'What is your spirit animal?',
			'options' => array(
				'Owl',
				'Puppy',
				'Giraffe',
				'Honeybadger',
				'Squirrel',
				'Reptar'
			)
		),
		array(
			'text' => 'How heavy of a drinker are you?',
			'options' => array(
				'I don\'t drink',
				'I might start drinking when I turn 21',
				'I drink occasionally',
				'I drink every weekend',
				'I drink more than 3 times a week',
				'I\'m drunk right now'
			)
		),
		array(
			'text' => 'What is your favorite type of music?',
			'options' => array(
				'Screamo',
				'Classical',
				'Rock',
				'Pop',
				'Hip-Hop / Rap / Electronic',
				'Country',
				'Sounds of Nature'
			)
		),
		array(
			'text' => 'You get a text from an ex, saying &quot;Wanna grab a bite in the village?&quot; How do you respond?',
			'options' => array(
				'Ignore him/her for a few days and then say no',
				'Let him/her down slowly then feel bad',
				'Say HAHA YOU WISH!',
				'Consult your buddies first to see their opinion',
				'Stand him/her up',
				'Go for it - why not?'
			)
		),
		array(
			'text' => 'What is your favorite type of Girl Scout Cookie?',
			'options' => array(
				'Samoa',
				'Trefoils',
				'Tagalongs',
				'Thin Mints',
				'Do-si-dos',
				'Lemonades'
			)
		),
		array(
			'text' => 'Which college course would you be excited to take?',
			'options' => array(
				'Harry Potter/Quidditch',
				'Cooking with Chef Rodger',
				'Brewing 101',
				'Public Policy Bootcamp',
				'Intro to Photoshop',
				'Personal Finanace',
				'Web Application Development'
			)
		),
		array(
			'text' => 'Which <em>How I Met Your Mother</em> character do you identify with the most?',
			'options' => array(
				'Ted',
				'Marshall',
				'Robin',
				'Lily',
				'Barney',
				'I haven\'t watched the show.'
			)
		),
		array(
			'text' => 'What quality do you think is most important in a significant other?',
			'options' => array(
				'Humor',
				'Intelligence',
				'Honesty',
				'Money',
				'Ambition',
				'Sexiness'
			)
		),
		array(
			'text' => 'If you had a million dollars, what would you do with it?',
			'options' => array(
				'Donate to a charity',
				'Buy a mansion',
				'Invest in the stock market',
				'Travel the world',
				'Perform cutting-edge research',
				'Throw an epic rager'
			)
		)
	);

	/* Returns a complete list of every field in the surveys table based on defined genders and questions. */
	public static function fields()
	{
		$array = self::$fields;

		for($g = 0; $g < sizeof(self::$genders); $g++)
			$array[] = 'interested_'.$g;

		for($i = 0; $i < sizeof(self::$questions); $i++)
			$array[] = 'question_'.$i;

		return $array;
	}
}
