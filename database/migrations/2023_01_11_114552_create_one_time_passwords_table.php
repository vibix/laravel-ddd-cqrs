<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('one_time_passwords', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('code');
            $table->string('subject_id')->index();
            $table->tinyInteger('attempts')->unsigned()->default(0);
            $table->tinyInteger('max_attempts')->unsigned();
            $table->dateTime('expiration_date');
            $table->dateTime('confirmed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('one_time_passwords');
    }
};
