<?php

declare(strict_types=1);

use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;

return [

    /*
    |--------------------------------------------------------------------------
    | Models attributes Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used mainly during validation to
    | translate model attribute names.
    |
    */

    'common' => [
        'id' => 'ID',
        'email' => 'Email address',
        'phone' => 'Phone',
        'phonePrefix' => 'Phone prefix',
        'phoneNumber' => 'Phone number',
        'name' => 'Name',
        'firstname' => 'Firstname',
        'lastname' => 'Lastname',
        'title' => 'Name',
        'description' => 'Description',
        'type' => 'Type',
        'language' => 'Language',
        'note' => 'Note',
        'role' => 'Role',
        'state' => 'State',
        'files' => 'Files',
        'operation' => 'Operation',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'deleted_at' => 'Deleted at',
    ],

    'user' => [
        'password' => 'Password',
        'old_password' => 'Old password',
        'current_password' => 'Current password',
        'password_confirm' => 'Password confirmation',
        'new_password' => 'New password',
        'prefix' => 'Titles before name',
        'postfix' => 'Titles after name',
    ],

    'company' => [
        'name' => 'Company name',
        'id_number' => 'Company ID number',
        'email' => 'Company contact e-mail address',
        'website' => 'Company website',
    ],

    'position' => [
        'name' => 'Position name',
        'externName' => 'Extern position name',
        'approveUntil' => 'Approve until',
        'approveMessage' => 'Message for approvers',
        'department' => 'Department',
        'field' => 'Field',
        'workload' => 'Work load',
        'employmentRelationship' => 'Employment relationship',
        'employmentForm' => 'Form of cooperation',
        'jobSeatsNum' => 'Number of job seats',
        'description' => 'Description',
        'address' => 'Workplace address',
        'salarySpan' => 'Salary range from - to',
        'salaryFrom' => 'Salary from',
        'salaryTo' => 'Salary to',
        'salary' => 'Salary',
        'salaryType' => 'Salary type',
        'salaryFrequency' => 'Salary frequency',
        'salaryCurrency' => 'Currency',
        'salaryVar' => 'Variable component',
        'benefits' => 'Benefits',
        'minEducationLevel' => 'Minimum education level',
        'educationField' => 'Education field',
        'seniority' => 'Seniority',
        'experience' => 'Min. number of years worked',
        'hardSkills' => 'Other hard skills',
        'organisationSkills' => 'Organisational skills',
        'teamSkills' => 'Teamwork',
        'timeManagement' => 'Time management',
        'communicationSkills' => 'Communication skills',
        'leadership' => 'Leading people',
        'languageSkills' => 'Language skills',
        'hiringManagers' => 'Hiring managers',
        'recruiters' => 'Recruiters',
        'approvers' => 'Approvers',
        'externalApprovers' => 'External approvers',
        'hardSkillsWeight' => 'Hard skills weight',
        'softSkillsWeight' => 'Soft skills weight',
        'languageSkillsWeight' => 'Language skills weight',
        'experienceWeight' => 'Work experience weight',
        'educationWeight' => 'Education weight',
        'shareSalary' => 'Share salary',
        'shareContact' => 'Share contact',
        'roles' => [
            PositionRoleEnum::HIRING_MANAGER->value => 'Hiring manager',
            PositionRoleEnum::APPROVER->value => 'Approver',
            PositionRoleEnum::EXTERNAL_APPROVER->value => 'External approver',
        ],
        'states' => [
            PositionStateEnum::DRAFT->value => 'Draft',
            PositionStateEnum::APPROVAL_PENDING->value => 'Pending approval',
            PositionStateEnum::APPROVAL_APPROVED->value => 'Approved',
            PositionStateEnum::APPROVAL_REJECTED->value => 'Approval rejected',
            PositionStateEnum::APPROVAL_CANCELED->value => 'Approval canceled',
            PositionStateEnum::APPROVAL_EXPIRED->value => 'Approval expired',
            PositionStateEnum::OPENED->value => 'Opened',
            PositionStateEnum::CLOSED->value => 'Closed',
            PositionStateEnum::CANCELED->value => 'Canceled',
        ],

    ],

    'candidate' => [
        'cv' => 'Resume',
        'linkedin' => 'LinkedIn',
    ],

];
