<?php

declare(strict_types=1);

namespace Domain\Auth\Database\Seeders;

use App\Enums\LanguageEnum;
use App\Models\Company;
use App\Models\User;
use Domain\Company\Enums\RoleEnum;
use Illuminate\Database\Seeder;

class AuthDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // seed default company and user

        $company = new Company();
        $company->name = 'Hirable';
        $company->email = (string) config('app.contact_email');
        $company->id_number = '123456789';
        $company->website = 'https://www.example.com';
        $company->save();

        $admin = new User();
        $admin->company_id = $company->id;
        $admin->company_role = RoleEnum::ADMIN;
        $admin->language = LanguageEnum::CS;
        $admin->firstname = 'Hirable';
        $admin->lastname = 'Admin';
        $admin->prefix = null;
        $admin->postfix = null;
        $admin->email = (string) config('app.admin_email');
        $admin->email_verified_at = now();
        $admin->password = 'NY0KLZ7g3ZL6xyRP';
        $admin->agreement_ip = '127.0.0.1';
        $admin->agreement_accepted_at = now();
        $admin->save();
    }
}
