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
         Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Customer::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Invoice::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Project::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Contract::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Quote::class)->nullable()->constrained()->nullOnDelete();
            $table->string('sale_number')->unique();
            $table->date('sale_date')->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->string('currency')->nullable()->default('USD');
            $table->foreignIdFor(\App\Models\User::class, 'created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('items')->nullable();
            $table->decimal('commission', 15, 2)->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'commission_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->json('custom_fields')->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->foreignIdFor(\App\Models\Tax::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Status::class)->nullable()->constrained()->nullOnDelete();
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
        Schema::dropIfExists('sales');
    }
};
