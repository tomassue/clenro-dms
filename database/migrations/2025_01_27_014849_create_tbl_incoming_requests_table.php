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
        Schema::create('tbl_incoming_requests', function (Blueprint $table) {
            $table->id();
            $table->string('incoming_request_no')->unique();
            $table->string('office_or_barangay_or_organization_name');
            $table->date('date_requested');
            $table->string('category_id'); // Foreign Key
            $table->string('date_and_time');
            $table->string('contact_person_name');
            $table->string('contact_person_number');
            $table->text('description');
            $table->json('file_id');
            $table->string('status_id'); // Foreign Key
            $table->string('forwarded_to_division_id')->nullable(); // Foreign Key
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_incoming_requests');
    }
};
