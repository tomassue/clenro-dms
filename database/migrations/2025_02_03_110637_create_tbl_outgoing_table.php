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
        Schema::create('tbl_outgoing', function (Blueprint $table) {
            $table->id(); // this will be the document no (incremented)
            $table->morphs('type');
            $table->date('date');
            $table->text('details')->nullable();
            $table->string('destination');
            $table->string('person_responsible');
            $table->json('file_id');
            $table->string('status_id');
            $table->string('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_outgoing');
    }
};
