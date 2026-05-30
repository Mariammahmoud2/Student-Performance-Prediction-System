<?php

namespace App\Helpers;

class QuestionMapper
{
    /**
     * Display question in human-friendly English
     */
    public static function getDisplayQuestion(string $originalQuestion): string
    {
        $map = [
            // ===== BATCH 1 =====
            'How many [digital devices] with screens are there in your [home]?'
                => 'How many digital devices with screens (mobile, tablet, laptop...) are in your home?',

            'How many books are there in your [home]?'
                => 'Approximately how many books are there in your home?',

            'How many of these books at [home]: Contemporary literature'
                => 'How many contemporary literature books are in your home?',

            'LANGN'
                => 'What language do you speak at home? (Enter code)',

            'Agree/disagree: Mathematics is easy for me.'
                => 'How much do you agree with: "Mathematics is easy for me"?',

            'How many of the following [digital devices] are in your [home]: [Cell phones] with Internet access (i.e. smartphones)'
                => 'How many smartphones (with internet access) are in your home?',

            'Student (Standardized) Gender'
                => 'What is your gender?',

            'How often use out of school: Smartphone (i.e. mobile phone with Internet access)'
                => 'How often do you use your smartphone outside of school?',

            '[Additional math instruction] received: One-on-one tutoring with a person'
                => 'Do you receive one-on-one math tutoring with a private tutor?',

            'How many days/wk after school: Eat dinner'
                => 'How many days per week do you eat dinner after school?',

            // ===== BATCH 2 =====
            'Have you ever repeated a [grade]: At [ISCED 2]'
                => 'Have you ever repeated a grade in middle school?',

            'Now think about where you would place your family on this scale. Where would you say your family stands at this time?'
                => 'On a scale of 1 to 10, where would you place your family socially and economically?',

            'How much effort would you have invested? (after cognitive assessment)'
                => 'How much effort did you actually put into the test? (1 to 10)',

            'Which of the following qualifications do you expect to complete: [ISCED level 3.3]'
                => 'What is the highest level of education you expect to complete?',

            'Grade repetition'
                => 'Have you ever repeated a school year?',

            'How often use [digital resources] in lessons in: [Computer science], [information technology], [informatics] or similar'
                => 'How often do you use digital tools in computer science or IT lessons?',

            'How confident in math tasks: Calculating how many square metres of tiles you need to cover a floor'
                => 'How confident are you in calculating the number of tiles needed to cover a floor?',

            'Agree/disagree: I share made-up information on social networks without flagging its inaccuracy.'
                => 'How much do you agree: "I sometimes share inaccurate information on social media without flagging it"?',

            'Agree/disagree: Creativity can only be expressed through the arts (e.g. drawing, music, or writing).'
                => 'How much do you agree: "Creativity can only be expressed through the arts like drawing, music, or writing"?',

            'How many days/wk before school: Exercise or practise a sport (e.g. running, cycling, aerobics, soccer, skating, [country'
                => 'How many days per week do you exercise or practise a sport before school?',

            // ===== BATCH 3 =====
            'How many siblings (including brothers, sisters, step-brothers, and step-sisters) do you have?'
                => 'How many siblings (including step-brothers and step-sisters) do you have?',

            'How confident in math tasks: Solving an equation like 3x+5=17'
                => 'How confident are you in solving a linear equation like 3x+5=17?',

            'How confident in math tasks: Solving an equation like 6x[sup]2[/sup]+5=29'
                => 'How confident are you in solving a quadratic equation like 6x²+5=29?',

            'Highest expected educational level'
                => 'What is the highest level of education you expect to reach?',

            'How familiar are you with the following mathematical terms: Pythagorean theorem'
                => 'How familiar are you with the Pythagorean theorem?',

            'How many of these items are there at your [home]: Musical instruments (e.g. guitar, piano, [country-specific example])'
                => 'How many musical instruments (guitar, piano, etc.) are in your home?',

            'Agree/disagree: Mathematics is one of my favourite subjects.'
                => 'How much do you agree: "Mathematics is one of my favourite subjects"?',

            'How much effort would you have invested if your results from the PISA test were counted in your [school marks]?'
                => 'How much effort would you have put in if PISA results counted toward your school grades? (1 to 10)',

            'In the past 30 days, how often did you not eat because there was not enough money to buy food?'
                => 'In the past 30 days, how often did you skip a meal due to lack of money?',

            'Agree/disagree: [Test language] is one of my favourite subjects.'
                => 'How much do you agree: "The test language is one of my favourite subjects"?',
        ];

        return $map[$originalQuestion] ?? $originalQuestion;
    }

    /**
     * Display option value in human-friendly English
     */
    public static function getDisplayOption(string $questionKey, string $value): string
    {
        $optionMaps = [

            // ===== Gender =====
            'Student (Standardized) Gender' => [
                '1' => 'Male',
                '2' => 'Female',
            ],

            // ===== Agree/Disagree - 4 options =====
            'Agree/disagree: Mathematics is easy for me.' => [
                '1' => 'Strongly Agree',
                '2' => 'Agree',
                '3' => 'Disagree',
                '4' => 'Strongly Disagree',
            ],
            'Agree/disagree: I share made-up information on social networks without flagging its inaccuracy.' => [
                '1' => 'Strongly Agree',
                '2' => 'Agree',
                '3' => 'Disagree',
                '4' => 'Strongly Disagree',
            ],
            'Agree/disagree: Creativity can only be expressed through the arts (e.g. drawing, music, or writing).' => [
                '1' => 'Strongly Agree',
                '2' => 'Agree',
                '3' => 'Disagree',
                '4' => 'Strongly Disagree',
            ],
            'Agree/disagree: Mathematics is one of my favourite subjects.' => [
                '1' => 'Strongly Agree',
                '2' => 'Agree',
                '3' => 'Disagree',
                '4' => 'Strongly Disagree',
            ],
            'Agree/disagree: [Test language] is one of my favourite subjects.' => [
                '1' => 'Strongly Agree',
                '2' => 'Agree',
                '3' => 'Disagree',
                '4' => 'Strongly Disagree',
            ],

            // ===== Math Confidence =====
            'How confident in math tasks: Calculating how many square metres of tiles you need to cover a floor' => [
                '1' => 'Very Confident',
                '2' => 'Confident',
                '3' => 'Not Confident',
                '4' => 'Not Confident at All',
            ],
            'How confident in math tasks: Solving an equation like 3x+5=17' => [
                '1' => 'Very Confident',
                '2' => 'Confident',
                '3' => 'Not Confident',
                '4' => 'Not Confident at All',
            ],
            'How confident in math tasks: Solving an equation like 6x[sup]2[/sup]+5=29' => [
                '1' => 'Very Confident',
                '2' => 'Confident',
                '3' => 'Not Confident',
                '4' => 'Not Confident at All',
            ],

            // ===== Contemporary literature books =====
            'How many of these books at [home]: Contemporary literature' => [
                '1' => 'None',
                '2' => '1-10 books',
                '4' => '11-25 books',
                '5' => 'More than 25 books',
                'Other' => 'Other',
            ],

            // ===== Smartphones at home =====
            'How many of the following [digital devices] are in your [home]: [Cell phones] with Internet access (i.e. smartphones)' => [
                '1' => 'None',
                '2' => 'One',
                '3' => 'Two',
                '4' => 'Three or more',
                'Other' => 'Other',
            ],

            // ===== Grade repetition ISCED 2 =====
            'Have you ever repeated a [grade]: At [ISCED 2]' => [
                '1' => 'No, I have never repeated a grade',
                '2' => 'Yes, once',
                '3' => 'Yes, more than once',
            ],

            // ===== Expected qualification =====
            'Which of the following qualifications do you expect to complete: [ISCED level 3.3]' => [
                '1' => 'High school diploma',
                '2' => 'Diploma or vocational studies',
                '3' => 'University degree or higher',
            ],

            // ===== Grade repetition =====
            'Grade repetition' => [
                '0' => 'No',
                '1' => 'Yes',
            ],

            // ===== Private tutoring =====
            '[Additional math instruction] received: One-on-one tutoring with a person' => [
                '0' => 'No',
                '1' => 'Yes',
            ],

            // ===== Siblings =====
            'How many siblings (including brothers, sisters, step-brothers, and step-sisters) do you have?' => [
                '1' => 'No siblings',
                '2' => '1-2 siblings',
                '3' => '3-4 siblings',
                '4' => '5 or more',
            ],

            // ===== Pythagorean theorem familiarity =====
            'How familiar are you with the following mathematical terms: Pythagorean theorem' => [
                '2' => 'Heard of it a little',
                '3' => 'Know it in general',
                '4' => 'Know it well',
                '5' => 'Know it very well and can explain it',
                'Other' => 'Never heard of it',
            ],

            // ===== Musical instruments =====
            'How many of these items are there at your [home]: Musical instruments (e.g. guitar, piano, [country-specific example])' => [
                '1' => 'None',
                '2' => 'One',
                '3' => 'Two',
                '4' => 'Three or more',
            ],

            // ===== Food insecurity =====
            'In the past 30 days, how often did you not eat because there was not enough money to buy food?' => [
                '1' => 'Never',
                '2' => 'Once or twice',
                '3' => 'Sometimes',
                '5' => 'Often',
                'Other' => 'Other',
            ],
        ];

        return $optionMaps[$questionKey][$value] ?? $value;
    }

    /**
     * Get mapped options array for a question
     * Returns: [ 'display' => 'Male', 'value' => '1' ]
     */
    public static function getMappedOptions(string $questionKey, array $options): array
    {
        return array_map(function ($option) use ($questionKey) {
            return [
                'value'   => $option,
                'display' => self::getDisplayOption($questionKey, $option),
            ];
        }, $options);
    }
}