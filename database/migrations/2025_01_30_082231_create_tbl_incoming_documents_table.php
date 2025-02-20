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
        Schema::create('tbl_incoming_documents', function (Blueprint $table) {
            $table->id();
            $table->string('category_id'); // foreign_key
            $table->text('info');
            $table->json('file_id');
            $table->date('date');
            $table->string('status_id'); // foreign_key
            $table->text('remarks')->nullable();
            $table->string('forwarded_to_division_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_incoming_documents');
    }
};
