<?php

use App\Models\Idea;
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
        Schema::create('idea_whiteboards', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Idea::class, 'idea_id')->unique()->constrained()->cascadeOnDelete();
            $table->json('drawing_data');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('idea_whiteboards');
    }
};
