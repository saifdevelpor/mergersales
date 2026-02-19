<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->enum('deal_type', ['Sell business', 'Raise capital', 'Find buyer', 'Find partner']);
            $table->string('business_img')->nullable();
            $table->string('business_name')->nullable();

            $table->foreignId('industry_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_industry_id')->constrained()->onDelete('cascade');

            $table->string('country');
            $table->string('region')->nullable();

            $table->string('currency')->default('USD');
            $table->string('revenue_range');
            $table->string('ebitda_range');
            $table->string('asking_price_range')->nullable();

            $table->string('employee_range');
            $table->year('year_established')->nullable();
            $table->string('reason_for_sale')->nullable();
            $table->longText('description');

            $table->string('teaser_path')->nullable();
            $table->string('im_path')->nullable();
            $table->boolean('nda_required')->default(false);
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
