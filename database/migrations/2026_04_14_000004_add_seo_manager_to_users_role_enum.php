<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM(
                'Admin',
                'Seller',
                'Buyer',
                'Investor',
                'Advisor',
                'Corporate',
                'Partner',
                'seo_manager'
            ) NULL
        ");
    }

    public function down(): void
    {
        DB::statement("UPDATE users SET role = 'Seller' WHERE role = 'seo_manager'");

        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM(
                'Admin',
                'Seller',
                'Buyer',
                'Investor',
                'Advisor',
                'Corporate',
                'Partner'
            ) NULL
        ");
    }
};
