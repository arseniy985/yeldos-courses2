<?php

use App\Models\TestQuestion;
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
        Schema::create('test_questions_answers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_valid');
            $table->foreignIdFor(TestQuestion::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_questions_answers');
    }
};
