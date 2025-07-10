<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('saga_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saga_run_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('status')->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('saga_steps');
    }
};
