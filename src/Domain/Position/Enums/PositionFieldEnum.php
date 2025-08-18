<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum PositionFieldEnum: string
{
    case NAME = 'name';
    case DEPARTMENT = 'department';
    case FIELD = 'field';
    case JOB_SEATS_NUM = 'jobSeatsNum';
    case DESCRIPTION = 'description';
    case ADDRESS = 'address';
    case SALARY_FROM = 'salaryFrom';
    case SALARY_TO = 'salaryTo';
    case SALARY_TYPE = 'salaryType';
    case SALARY_FREQUENCY = 'salaryFrequency';
    case SALARY_CURRENCY = 'salaryCurrency';
    case SALARY_VAR = 'salaryVar';
    case MIN_EDUCATION_LEVEL = 'minEducationLevel';
    case EDUCATION_FIELD = 'educationField';
    case SENIORITY = 'seniority';
    case EXPERIENCE = 'experience';
    case HARD_SKILLS = 'hardSkills';
    case ORGANISATION_SKILLS = 'organisationSkills';
    case TEAM_SKILLS = 'teamSkills';
    case TIME_MANAGEMENT = 'timeManagement';
    case COMMUNICATION_SKILLS = 'communicationSkills';
    case LEADERSHIP = 'leadership';
    case NOTE = 'note';
    case WORKLOADS = 'workloads';
    case EMPLOYMENT_RELATIONSHIPS = 'employmentRelationships';
    case EMPLOYMENT_FORMS = 'employmentForms';
    case BENEFITS = 'benefits';
    case FILES = 'files';
    case LANGUAGE_REQUIREMENTS = 'languageRequirements';
    case HIRING_MANAGERS = 'hiringManagers';
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
    case EXTERN_NAME = 'externName';
    case SHARE_SALARY = 'shareSalary';
    case SHARE_CONTACT = 'shareContact';
    case TAGS = 'tags';
}
