<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert into groups table
        DB::table('groups')->insert([
            'name' => 'Super Admin',
            'type' => 'super_admin',
            'created_at' => now(),
            'updated_at' => now(),
            'createdBy' => 1
        ]);

        // Get the last inserted group id
        $groupId = DB::getPdo()->lastInsertId();

        // Insert into group_modules table
        $modules = [
            ['module_name' => 'Dashboard', 'module_page' => 'dashboard'],
            ['module_name' => 'User Management', 'module_page' => 'user-management'],
            ['module_name' => 'User Management > Users', 'module_page' => 'users'],
            ['module_name' => 'User Management > Roles', 'module_page' => 'roles'],
            ['module_name' => 'Clients', 'module_page' => 'clients'],
            ['module_name' => 'Cases', 'module_page' => 'cases'],
            ['module_name' => 'Case Fee Details', 'module_page' => 'case_fee_details'],
            ['module_name' => 'Case Payment Details', 'module_page' => 'case_payment_details'],
            ['module_name' => 'Invoice', 'module_page' => 'invoice'],
            ['module_name' => 'Court', 'module_page' => 'court'],
            ['module_name' => 'Court > List', 'module_page' => 'court_list'],
            ['module_name' => 'Court > Categories', 'module_page' => 'court_categories'],
            ['module_name' => 'Quotations', 'module_page' => 'quotations'],
            ['module_name' => 'Settings', 'module_page' => 'settings'],
            ['module_name' => 'Settings > General Settings', 'module_page' => 'general_settings'],
            ['module_name' => 'Settings > SMTP Settings', 'module_page' => 'smtp_settings'],
            ['module_name' => 'Master Data', 'module_page' => 'master_data'],
            ['module_name' => 'Master Data > Country', 'module_page' => 'country'],
            ['module_name' => 'Master Data > State', 'module_page' => 'state'],
            ['module_name' => 'Master Data > City', 'module_page' => 'city'],
            ['module_name' => 'Master Data > Fee Description', 'module_page' => 'fee_description'],
            ['module_name' => 'Master Data > Case Acts', 'module_page' => 'case_acts'],
        ];

        foreach ($modules as $module) {
            $module['created_at'] = now();
            $module['updated_at'] = now();
            DB::table('group_modules')->insert($module);
        }

        // Get all inserted modules
        $insertedModules = DB::table('group_modules')->get();

        // Insert into group_permissions table
        $permissions = [];
        foreach ($insertedModules as $module) {
            $permissions[] = [
                'group_id' => $groupId,
                'module_id' => $module->id,
                'module_name' => $module->module_name,
                'module_page' => $module->module_page,
                'access' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('group_permissions')->insert($permissions);
        // Insert a user with the same group ID
        DB::table('users')->insert([
            'user_type' => User::USER_TYPE,
            'username' => 'adminuser',
            'fname' => 'Admin',
            'lname' => 'User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'code' => '12345678',
            'group_id' => $groupId,
            'firm_name' => 'Law Firm',
            'created_at' => now(),
            'updated_at' => now(),
            'theme_mode' => 'light',
            'createdBy' => 1
        ]);
        // Insert General Settings....
        DB::table('general_settings')->insert([
            'sitename' => 'Advocate Management',
            'title' => 'Law Firm',
            'address' => 'Firm Short Address',
            'mobile' => '1234567890',
            'email' => 'firm@gmail.com',
            'law_firm_admin' => 'law firm admin name',
            'law_firm_lawyer' => 'law firm lawyer name',
            'copy_r' => 'Copyright © Designed & Developed by IrFan MirZa 2024',
            'logo' => 'logo.png',
            'default_image' => 'default_image.png'
        ]);

        DB::table('email_templates')->insert([
            [
                'name' => 'PASSWORD_RESET',
                'subject' => 'Password Reset',
                'template' => '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding: 10px;
            background-color: #007BFF;
            color: #ffffff;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .content p {
            font-size: 16px;
            line-height: 1.5;
            color: #333333;
        }

        .reset-code {
            font-size: 24px;
            font-weight: bold;
            color: #007BFF;
            margin: 20px 0;
            display: inline-block;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #f7f7f7;
            color: #999999;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Request Password</h1>
        </div>
        <div class="content">
            <p>Hi {username},</p>
            <p>We received a request to reset your password. Use Your last password:</p>
            <p class="reset-code">{code}</p>
            <p>If you did not request a password reset, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>&copy; 2024 {sent_from}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
',
                'meaning' => '{"username":"Email Receiver Name", "code" : "Email Verification Code", "sent_from" : "Email Sent from"}
',
            ],
            [
                'name' => 'CASE_INVOICE',
                'subject' => 'Case Invoice',
                'template' => '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding: 10px;
            background-color: #007BFF;
            color: #ffffff;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .content p {
            font-size: 16px;
            line-height: 1.5;
            color: #333333;
        }

        .reset-code {
            font-size: 24px;
            font-weight: bold;
            color: #007BFF;
            margin: 20px 0;
            display: inline-block;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #f7f7f7;
            color: #999999;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Case Invoice # {invoice_no}</h1>
        </div>
        <div class="content">
            <p>Hi {username},</p>
            <p>Please check invoice in attachment.</p>

        </div>
        <div class="footer">
            <p>&copy; 2024 {sent_from}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
',
                'meaning' => '{"username":"Email Receiver Name","invoice_no":"invoice no","sent_from":"Email Sent from"}',
            ],

        ]);
    }
}
