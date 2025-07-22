<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum PositionFieldEnum: string
{
    case NAME = 'name';
    case EXTERN_NAME = 'externName';
    case DEPARTMENT = 'department';
    case FIELD = 'field';
    case JOB_SEATS_NUM = 'jobSeatsNum';
    case DESCRIPTION = 'description';
    case ADDRESS = 'address';
    case SALARY = 'salary';
    case SALARY_TYPE = 'salaryType';
    case SALARY_FREQUENCY = 'salaryFrequency';
    case SALARY_CURRENCY = 'salaryCurrency';
    case SALARY_VAR = 'salaryVar';
    case MIN_EDUCATION_LEVEL = 'minEducationLevel';
    case SENIORITY = 'seniority';
    case EXPERIENCE = 'experience';
    case HARD_SKILLS = 'hardSkills';
    case ORGANISATION_SKILLS = 'organisationSkills';
    case TEAM__SKILLS = 'teamSkills';
    case TIME__MANAGEMENT = 'timeManagement';
    case COMMUNICATION__SKILLS = 'communicationSkills';
    case LEADERSHIP = 'leadership';
    case NOTE = 'note';
    case WORKLOADS = 'workloads';
    case EMPLOYMENT__RELATIONSHIPS = 'employmentRelationships';
    case EMPLOYMENT__FORMS = 'employmentForms';
    case BENEFITS = 'benefits';
    case FILES = 'files';
    case LANGUAGE__REQUIREMENTS = 'languageRequirements';
    case HIRING__MANAGERS = 'hiringManagers';
    case RECRUITERS = 'recruiters';
    case APPROVERS = 'approvers';
    case EXTERNAL_APPROVERS = 'externalApprovers';
    case APPROVE_UNTIL = 'approveUntil';
    case APPROVE_MESSAGE = 'approveMessage';
    case HARD_SKILLS_WEIGHT = 'hardSkillsWeight';
    case SOFT_SKILLS_WEIGHT = 'softSkillsWeight';
    case LANGUAGE_SKILLS_WEIGHT = 'languageSkillsWeight';
    case EXPERIENCE_WEIGHT = 'experienceWeight';
    case EDUCATION_WEIGHT = 'educationWeight';
    case SHARE_SALARY = 'shareSalary';
    case SHARE_CONTACT = 'shareContact';
}
