<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('position')->nullable();
            $table->string('interest_type'); // buy, partner, nda
            $table->string('budget')->nullable();
            $table->string('timeline')->nullable();
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('nda_required')->default(0);
            $table->string('nda_status')->default('not_sent');
            // not_sent | sent | signed | declined (optional)

            $table->string('nda_file_path')->nullable();          // generated NDA PDF path
            $table->string('signed_nda_file_path')->nullable(); // buyer upload
             $table->string('buyer_signature_path')->nullable(); // path to buyer's signature image

            $table->timestamp('nda_sent_at')->nullable();
            $table->timestamp('nda_signed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
