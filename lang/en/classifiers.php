<?php

declare(strict_types=1);

use Support\Classifier\Enums\ClassifierTypeEnum;

return [

    ClassifierTypeEnum::GENDER->value => [
        'male' => 'Male',
        'female' => 'Female',
    ],

    ClassifierTypeEnum::LANGUAGE->value => [
        'english' => 'English',
        'czech' => 'Czech',
        'slovak' => 'Slovak',
        'german' => 'German',
        'russian' => 'Russian',
        'spanish' => 'Spanish',
        'french' => 'French',
        'polish' => 'Polish',
        'chinese' => 'Chinese',
    ],

    ClassifierTypeEnum::LANGUAGE_LEVEL->value => [
        'a1' => 'A1 - Beginner',
        'a2' => 'A2 - Elementary',
        'b1' => 'B1 - Intermediate',
        'b2' => 'B2 - Upper intermediate',
        'c1' => 'C1 - Advanced',
        'c2' => 'C2 - Proficient',
        'native' => 'Native speaker',
    ],

    ClassifierTypeEnum::BENEFIT->value => [
        'meal_contribution' => 'Meal contribution',
        'transport_contribution' => 'Transport contribution',
        'pension_contribution' => 'Pension plan contribution',
        'life_insurance_contribution' => 'Life insurance contribution',
        'more_vacation' => 'More vacation',
        'sick_days' => 'Sick days (extra paid leave)',
        'home_office' => 'Home office / remote work option',
        'flexible_hours' => 'Flexible working hours',
        'company_car' => 'Company car',
        'company_phone_laptop' => 'Company phone / laptop',
        'bonuses_commissions' => 'Bonuses and commissions',
        'annual_bonus' => 'Annual bonus',
        'sports_card' => 'Multisport card or sports contribution',
        'company_events' => 'Company events (teambuildings, parties)',
        'free_snacks' => 'Free snacks at the workplace',
        'training_education' => 'Training and education',
        'career_growth' => 'Career growth and development',
        'childcare_support' => 'Company childcare or childcare support',
        'work_from_abroad' => 'Work from abroad option',
        'culture_leisure_support' => 'Culture or leisure contribution',
        'employee_discounts' => 'Employee discounts',
        'housing_relocation_support' => 'Housing or relocation support',
        'equity_program' => 'Equity program / profit sharing',
        'unpaid_leave_option' => 'Unpaid leave option',
        'mental_health_support' => 'Mental health and wellbeing support',
        'relax_zones' => 'Relaxation zones at the workplace',
        'company_parking' => 'Company parking',
        'free_coffee_tea' => 'Free coffee / tea',
        'seniority_vacation_bonus' => 'Extra vacation for years of service',
    ],

    ClassifierTypeEnum::WORKLOAD->value => [
        'full_time' => 'Full-time',
        'part_time' => 'Part-time',
    ],

    ClassifierTypeEnum::EMPLOYMENT_RELATIONSHIP->value => [
        'freelance' => 'Freelance',
        'internship' => 'Internship',
        'contract' => 'Work contract',
    ],

    ClassifierTypeEnum::EMPLOYMENT_FORM->value => [
        'on_site' => 'On-site',
        'remote' => 'Remote',
    ],

    ClassifierTypeEnum::SENIORITY->value => [
        'junior' => 'Junior',
        'medior' => 'Medior',
        'senior' => 'Senior',
    ],

    ClassifierTypeEnum::EDUCATION_LEVEL->value => [
        'primary' => 'Primary education',
        'secondary_no_certificate' => 'Secondary education without diploma or certificate',
        'secondary_certificate' => 'Secondary education with high school diploma',
        'secondary_practice_certificate' => 'Secondary education with practical certificate',
        'higher' => 'Higher professional education',
        'bachelor' => 'Bachelor’s degree',
        'master' => 'Master’s degree',
        'doctor' => 'Doctorate / PhD',
    ],

    ClassifierTypeEnum::FIELD->value => [
        'admin' => 'Administration',
        'auto_moto' => 'Automotive',
        'banking_finance' => 'Banking and Financial Services',
        'tourism_hospitality' => 'Tourism and Hospitality',
        'chemical_industry' => 'Chemical Industry',
        'logistics_supply' => 'Transport, Logistics and Supply',
        'corporate_finance' => 'Economy and Corporate Finance',
        'electrical_energy' => 'Electrical Engineering and Energy',
        'pharmacy' => 'Pharmaceuticals',
        'hospitality' => 'Gastronomy and Hospitality',
        'it_consulting' => 'IT: Consulting, Analysis and Project Management',
        'it_admin' => 'IT: System and Hardware Administration',
        'it_development' => 'IT: Application and System Development',
        'arts_creative' => 'Culture, Arts and Creative Work',
        'quality_control' => 'Quality Assurance and Control',
        'marketing' => 'Marketing',
        'media_advertising_pr' => 'Media, Advertising and PR',
        'procurement' => 'Procurement',
        'security' => 'Security',
        'hr' => 'Human Resources',
        'insurance' => 'Insurance',
        'food_industry' => 'Food Industry',
        'sales' => 'Sales and Trade',
        'legal' => 'Legal Services',
        'services' => 'Services',
        'construction_realestate' => 'Construction and Real Estate',
        'engineering' => 'Mechanical Engineering',
        'public_admin' => 'Public Administration',
        'technical_development' => 'Engineering and Development',
        'telecom' => 'Telecommunications',
        'executive_management' => 'Executive Management',
        'publishing_print' => 'Publishing, Printing and Polygraphy',
        'education' => 'Education and Schooling',
        'manufacturing_industry' => 'Manufacturing and Industry',
        'science_research' => 'Science and Research',
        'healthcare_social' => 'Healthcare and Social Care',
        'agriculture_ecology' => 'Agriculture, Forestry and Ecology',
        'customer_service' => 'Customer Service',
        'manual_labor' => 'Craft and Manual Work',
    ],

    ClassifierTypeEnum::INTERVIEW_TYPE->value => [
        'screening' => 'Screening',
        'hr' => 'HR',
        'manager' => 'Hiring Manager',
        'competency' => 'Competency/Behaviour',
        'technical' => 'Technical',
        'final' => 'With leadership/CEO'
    ],

    ClassifierTypeEnum::INTERVIEW_FORM->value => [
        'personal' => 'Personal',
        'phone' => 'Phone',
        'online' => 'Online',
        'async' => 'Asynchronous',
        'chat' => 'Chat',
    ],

    ClassifierTypeEnum::TASK_TYPE->value => [
        'language' => 'Language',
        'expertise' => 'Proficiency',
        'psychometric' => 'Psychometric',
        'skills' => 'Skills',
        'case_study' => 'Case study',
        'other' => 'Other',
    ],

    ClassifierTypeEnum::REFUSAL_REASON->value => [
        'accepted_other_offer' => 'Accepted another job offer',
        'no_longer_available' => 'No longer available',
        'lost_interest' => 'Lost interest in the position',
        'personal_change' => 'Change in personal circumstances',
        'unsatisfied_offer' => 'Unsatisfied with the offer (salary, benefits)',
        'location_issue' => 'Location not suitable',
        'schedule_issue' => 'Working hours not suitable',
        'process_too_long' => 'Recruitment process too long',
        'negative_interview_experience' => 'Negative interview experience',
        'career_misalignment' => 'Does not align with career goals',
        'poor_communication' => 'Poor communication from the company',
        'company_instability' => 'Concerns about company stability',
        'staying_with_current_employer' => 'Decided to stay with current employer',
        'family_reasons' => 'Family reasons',
        'other_reason' => 'Other reason',
    ],

    ClassifierTypeEnum::REJECTION_REASON->value => [
        'lack_experience' => 'Lack of experience',
        'insufficient_education' => 'Insufficient education',
        'skill_mismatch' => 'Skill mismatch',
        'salary_expectations' => 'Salary expectations not met',
        'location_mismatch' => 'Location mismatch',
        'availability_issue' => 'Availability issue',
        'schedule_conflict' => 'Schedule conflict',
        'lack_motivation' => 'Lack of motivation',
        'failed_interview' => 'Failed interview',
        'failed_testing' => 'Failed testing',
        'cultural_mismatch' => 'Cultural mismatch',
        'candidate_declined' => 'Candidate declined the offer',
        'position_canceled' => 'Position canceled',
        'position_filled' => 'Position filled by another candidate',
        'overqualified' => 'Overqualified',
        'incomplete_application' => 'Incomplete application',
        'lacking_language_skills' => 'Lacking required language skills',
        'failed_reference_check' => 'Failed reference check',
        'failed_background_check' => 'Failed background check',
        'other_reason' => 'Other reason',
    ],

    ClassifierTypeEnum::SALARY_FREQUENCY->value => [
        'monthly' => 'Month',
        'yearly' => 'Year',
        'hourly' => 'Hour',
        'daily' => 'Day',
        'quarterly' => 'Quarter',
        'md' => 'MD',
    ],

    ClassifierTypeEnum::SALARY_TYPE->value => [
        'gross' => 'Gross',
        'net' => 'Net',
    ],

    ClassifierTypeEnum::EMPLOYMENT_DURATION->value => [
        'certain' => 'For a certain period of time',
        'indefinite' => 'For an indefinite period of time',
    ],

];
