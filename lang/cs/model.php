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
        'email' => 'Emailová adresa',
        'phone' => 'Telefonní číslo',
        'name' => 'Jméno',
        'firstname' => 'Křestní jméno',
        'lastname' => 'Příjmení',
        'title' => 'Název',
        'description' => 'Popis',
        'type' => 'Typ',
        'language' => 'Jazyk',
        'note' => 'Poznámka',
        'role' => 'Role',
        'state' => 'Stav',
        'files' => 'Soubory',
        'operation' => 'Operace',
        'created_at' => 'Vytvořeno',
        'updated_at' => 'Aktualizováno',
        'deleted_at' => 'Smazáno',
    ],

    'user' => [
        'password' => 'Heslo',
        'old_password' => 'Staré heslo',
        'current_password' => 'Aktuální heslo',
        'password_confirm' => 'Potvrzení hesla',
        'new_password' => 'Nové heslo',
        'prefix' => 'Titul před jménem',
        'postfix' => 'Titul za jménem',
    ],

    'company' => [
        'name' => 'Název společnosti',
        'id_number' => 'IČO',
        'email' => 'Kontaktní e-mail společnosti',
        'website' => 'Webová stránka společnosti',
    ],

    'position' => [
        'name' => 'Název pozice',
        'approveUntil' => 'Schválit do',
        'approveMessage' => 'Zpráva pro schvalovatele',
        'department' => 'Oddělení',
        'field' => 'Obor',
        'workload' => 'Typ úvazku',
        'employmentRelationship' => 'Pracovní poměr',
        'employmentForm' => 'Forma spolupráce',
        'jobSeatsNum' => 'Počet pracovních míst',
        'description' => 'Popis',
        'isTechnical' => 'Technická pozice',
        'address' => 'Adresa pracoviště',
        'salarySpan' => 'Rozpětí mzdy od - do',
        'salaryFrom' => 'Mzda od',
        'salaryTo' => 'Mzda do',
        'salary' => 'Mzda',
        'salaryType' => 'Typ mzdy',
        'salaryFrequency' => 'Frekvence mzdy',
        'salaryCurrency' => 'Měna',
        'salaryVar' => 'Variabilní složka',
        'benefits' => 'Benefity',
        'minEducationLevel' => 'Minimální dosažené vzdělání',
        'seniority' => 'Seniorita',
        'experience' => 'Min. počet odpracovaných roků',
        'hardSkills' => 'Ostatní tvrdé dovednosti',
        'organisationSkills' => 'Organizační dovednosti',
        'teamSkills' => 'Týmová spolupráce',
        'timeManagement' => 'Time management',
        'communicationSkills' => 'Komunikační schopnosti',
        'leadership' => 'Vedení lidí',
        'languageSkills' => 'Jazykové dovednosti',
        'hiringManagers' => 'Hiring manažeři',
        'recruiters' => 'Náboráři',
        'approvers' => 'Schvalovatelé',
        'externalApprovers' => 'Externí schvalovatelé',
        'hardSkillsWeight' => 'Váha tvrdých dovedností',
        'softSkillsWeight' => 'Váha měkkých dovedností',
        'languageSkillsWeight' => 'Váha jazykových dovedností',
        'shareSalary' => 'Sdílet mzdu',
        'shareContact' => 'Sdílet kontakt',
        'roles' => [
            PositionRoleEnum::HIRING_MANAGER->value => 'Hiring manažer',
            PositionRoleEnum::APPROVER->value => 'Schvalovatel',
            PositionRoleEnum::EXTERNAL_APPROVER->value => 'Externí schvalovatel',
        ],
        'states' => [
            PositionStateEnum::DRAFT->value => 'Rozpracovaná',
            PositionStateEnum::APPROVAL_PENDING->value => 'Čeká na schválení',
            PositionStateEnum::APPROVAL_APPROVED->value => 'Schválená',
            PositionStateEnum::APPROVAL_REJECTED->value => 'Schválení zamítnuto',
            PositionStateEnum::APPROVAL_CANCELED->value => 'Schválení zrušeno',
            PositionStateEnum::APPROVAL_EXPIRED->value => 'Schválení vypršelo',
            PositionStateEnum::OPENED->value => 'Otevřená',
            PositionStateEnum::CLOSED->value => 'Uzavřená',
            PositionStateEnum::CANCELED->value => 'Zrušená',
        ],
    ],

];
