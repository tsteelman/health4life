<?php

App::uses('AppModel', 'Model');

/**
 * UserHealthHistory Model
 *
 * @property User $User
 * @property City $City
 */
class UserHealthHistory extends AppModel {

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'City' => array(
			'className' => 'City',
			'foreignKey' => 'city_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
		'first_name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please enter your first name'
			),
			'regex' => array(
				'rule' => '/^[a-zA-Z \']+$/',
				'message' => 'Only alphabets, space, and “ ’ ” is allowed'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 30),
				'message' => 'Maximum 30 characters'
			)
		),
		'middle_name' => array(
			'regex' => array(
				'rule' => '/^[a-zA-Z \']+$/',
				'message' => 'Only alphabets, space, and “ ’ ” is allowed',
				'allowEmpty' => true
			),
			'maxLength' => array(
				'rule' => array('maxLength', 10),
				'message' => 'Maximum 10 characters'
			)
		),
		'last_name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please enter your last name'
			),
			'regex' => array(
				'rule' => '/^[a-zA-Z \']+$/',
				'message' => 'Only alphabets, space, and “ ’ ” is allowed'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 30),
				'message' => 'Maximum 30 characters'
			)
		),
		'gender' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please select a gender'
			)
		),
		'dob' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please select date of birth'
			)
		),
		'occupation' => array(
			'regex' => array(
				'rule' => '/^[a-zA-Z ]+$/',
				'message' => 'Only alphabets, and space is allowed',
				'allowEmpty' => true
			)
		),
		'race' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please select race'
			)
		),
		'city_id' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please select a valid location'
			)
		)
	);

	/**
	 * List of smoking statuses
	 * 
	 * @return array
	 */
	public static function listSmokingOptions() {
		$smokingOptions = array(
			-1 => __('None'),
			1 => __('Light'),
			2 => __('Moderate'),
			3 => __('Severe')
		);
		return $smokingOptions;
	}

	/**
	 * List of drinking statuses
	 * 
	 * @return array
	 */
	public static function listDrinkingOptions() {
		$drinkingOptions = array(
			-1 => __('None'),
			1 => __('Rarely'),
			2 => __('Occasionally'),
			3 => __('Daily')
		);
		return $drinkingOptions;
	}

	/**
	 * List of conditions
	 * 
	 * @return array
	 */
	public static function listConditions() {
		$conditions = array(
			1 => "AIDS/HIV Positive",
			2 => "Alzheimer's Disease",
			3 => "Anaphylaxis",
			4 => "Anemia",
			5 => "Angina",
			6 => "Arthritis/Gout",
			7 => "Artificial Heart Valve",
			8 => "Artificial Joint",
			9 => "Asthma",
			10 => "Blood Disease",
			11 => "Blood Transfusion",
			12 => "Breathing Problem",
			13 => "Bruise Easily",
			14 => "Cancer",
			15 => "Chemotherapy",
			16 => "Chest Pains",
			17 => "Cold Sores/Fever ",
			18 => "Blisters",
			19 => "Congential Heart Disorder",
			20 => "Convulsions",
			21 => "Cortisone Medicine",
			22 => "Diabetes",
			23 => "Drug Addiction",
			24 => "Easily Winded",
			25 => "Emphysema",
			26 => "Epilepsy or Seizures",
			27 => "Excessive Bleeding",
			28 => "Excessive Thirst",
			29 => "Fainting Spells/Dizziness",
			30 => "Frequent Cough",
			31 => "Frequent Diarrhea",
			32 => "Frequent Headaches",
			33 => "Genital Herpes",
			34 => "Glaucoma",
			35 => "Hay Fever",
			36 => "Heart Attack",
			37 => "Heart Murmur",
			38 => "Heart Pacemaker",
			39 => "Heart Trouble/Disease",
			40 => "Hemophilia",
			41 => "Hepatitis A",
			42 => "Hepatitis B or C",
			43 => "Herpes",
			44 => "High Blood Pressure",
			45 => "Hives or Rash",
			46 => "Hypoglycemia",
			47 => "Irregular Heartbeat",
			48 => "Kidney Problems",
			49 => "Leukemia,",
			50 => "Liver Disease",
			51 => "Low Blood Pressure",
			52 => "Lung Disease",
			53 => "Mitral Valve Prolapse",
			54 => "Pain in Jaw Joints",
			55 => "Parathyroid",
			56 => "Psychiatric Disease",
			57 => "Psychiatric Care",
			58 => "Radiation Treatments",
			59 => "Recent Weight Loss",
			60 => "Renal Dialysis",
			61 => "Rheumatic Fever",
			62 => "Rheumatism",
			63 => "Scarlet Fever",
			64 => "Shingles",
			65 => "Sickle Cell Disease",
			66 => "Sinus Trouble",
			67 => "Spina Bifida",
			68 => "Stomach/Intestinal Disease",
			69 => "Stroke",
			70 => "Swelling of Limbs",
			71 => "Thyroid Disease",
			72 => "Tonsillitis",
			73 => "Tuberculosis",
			74 => "Tumors or Growths",
			75 => "Ulcers",
			76 => "Venereal Disease",
			77 => "Yellow Jaundice",
			78 => "Tuberculosis",
			79 => "Autoimmune Disorders",
			80 => "Infectious Disease",
			81 => "Migraines",
			82 => "Obesity",
			83 => "STD's",
			84 => "Hives",
			85 => "Psoriasis",
			86 => "Eczema",
			87 => "Immunodeficiency"
		);
		return $conditions;
	}

	/**
	 * List of childhood illnesses
	 * 
	 * @return array
	 */
	public static function listChildhoodIllnesses() {
		$childhoodIllnesses = array(
			1 => __('Chicken pox'),
			2 => __('Measles'),
			3 => __('Mumps'),
			4 => __('5th Disease'),
			5 => __('RSV'),
			6 => __('Scarlet Fever'),
			7 => __('Eczema'),
			8 => __('Chronic Sinus Infections'),
			9 => __('Chronic Ear Infections'),
			10 => __('Chronic Respiratory Infections'),
			11 => __('Excessive Antibiotic treatment')
		);
		return $childhoodIllnesses;
	}

	/**
	 * List of allergic medicines
	 * 
	 * @return array
	 */
	public static function listAllergicMedicines() {
		$allergicMedicines = array(
			1 => __('Aspirin'),
			2 => __('Penicillin'),
			3 => __('Codeine'),
			4 => __('Acrylic'),
			5 => __('Metal'),
			6 => __('Latex'),
			7 => __('Local Anesthetics')
		);
		return $allergicMedicines;
	}

	/**
	 * List of allergic food items
	 * 
	 * @return array
	 */
	public static function listAllergicFoodItems() {
		$allergicFoodItems = array(
			1 => __('Shellfish'),
			2 => __('Milk'),
			3 => __('Peanut')
		);
		return $allergicFoodItems;
	}

	/**
	 * List of environmental allergies
	 * 
	 * @return array
	 */
	public static function listEnvironmentalAllergies() {
		$environmentalAllergies = array(
			1 => __('Pollen'),
			2 => __('Grass'),
			3 => __('Mold'),
			4 => __('Environmental Exposures')
		);
		return $environmentalAllergies;
	}

	/**
	 * List of vaccinations
	 * 
	 * @return array
	 */
	public static function listVaccinations() {
		$vaccinations = array(
			__('Infant to 6 years old') => array(
				1 => __('Hepatitis B'),
				2 => __('RV'),
				3 => __('DTaP'),
				4 => __('Hib'),
				5 => __('PCV'),
				6 => __('IPV'),
				7 => __('Influenza'),
				8 => __('MMR'),
				9 => __('Varicella'),
				10 => __('HepA')
			),
			__('7-18 yrs old') => array(
				11 => __('Tdap'),
				12 => __('MCV4'),
				13 => __('IPV'),
				14 => __('MCV'),
				15 => __('Influenza'),
				16 => __('Pneumococcal Vaccine'),
				17 => __('HepA'),
				18 => __('HepB'),
				19 => __('MMR'),
				20 => __('Varicella Vaccine Series'),
				21 => __('Booster at age 16 years')
			),
			__('Adults (19 or older)') => array(
				22 => __('Influenza'),
				23 => __('Tdap'),
				24 => __('Varicella'),
				25 => __('HPV Vaccine for Women'),
				26 => __('HPV Vaccine for Men'),
				27 => __('Shingles'),
				28 => __('MMR'),
				29 => __('PVC'),
				30 => __('PPSV13'),
				31 => __('PPSV23'),
				32 => __('Meningococcal'),
				33 => __('HepA'),
				34 => __('HepB')
			)
		);
		return $vaccinations;
	}

	/**
	 * List of genders
	 * 
	 * @return array
	 */
	public static function listGenderOptions() {
		$genderOptions = array('M' => 'Male', 'F' => 'Female');
		return $genderOptions;
	}

	/**
	 * List of marital statuses
	 * 
	 * @return array
	 */
	public static function listMaritalStatusOptions() {
		$maritalStatusOptions = array(
			'S' => 'Single',
			'M' => 'Married',
			'W' => 'Widowed',
			'D' => 'Divorced'
		);
		return $maritalStatusOptions;
	}

	/**
	 * List of races
	 * 
	 * @return array
	 */
	public static function listRaceOptions() {
		$raceOptions = array(
			1 => 'White',
			2 => 'Hispanic',
			3 => 'Black or African American',
			4 => 'American Indian/Alaska Native',
			5 => 'Asian or Pacific Islander',
			6 => 'Eurasian',
			7 => 'Indian',
			8 => 'Arab',
			9 => 'Other - Not specified'
		);
		return $raceOptions;
	}
}