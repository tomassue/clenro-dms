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
        Schema::create('tbl_accomplishments', function (Blueprint $table) {
            $table->id();
            $table->string('accomplishment_category_id'); // Foreign Key
            $table->date('date');
            $table->text('details');
            $table->string('no_of_participants');
            $table->longText('remarks')->nullable();
            $table->json('file_id')->nullable();
            $table->string('user_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_accomplishments');
    }
};
