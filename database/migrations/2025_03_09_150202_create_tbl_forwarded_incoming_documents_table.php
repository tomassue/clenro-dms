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
        Schema::create('tbl_forwarded_incoming_documents', function (Blueprint $table) {
            $table->id();
            $table->string('incoming_document_id'); // Reference Key
            $table->string('division_id'); // Reference Key
            $table->boolean('is_opened')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_forwarded_incoming_documents');
    }
};
