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
        Schema::create('discussion_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Discussion::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class, 'created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('content');
            $table->boolean('is_client_reply')->default(false);
            $table->json('custom_fields')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discussion_replies');
    }
};
