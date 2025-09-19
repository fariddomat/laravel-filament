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
        Schema::create('discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Project::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Customer::class)->nullable()->constrained()->nullOnDelete();
            $table->string('subject');
            $table->text('content');
            $table->foreignIdFor(\App\Models\User::class, 'created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('visibility')->nullable();
            $table->string('category')->nullable();
            $table->json('custom_fields')->nullable();
            $table->boolean('is_visible_to_client')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discussions');
    }
};
