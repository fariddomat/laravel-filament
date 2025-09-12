<?php

use App\Models\Customer;
use App\Models\Status;
use App\Models\User;
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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Customer::class, 'customer_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->decimal('total_billed', 15, 2)->nullable();
            $table->foreignIdFor(User::class, 'created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignIdFor(Status::class, 'status_id')->nullable()->constrained()->nullOnDelete();
            $table->string('billing_type')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->boolean('is_visible_to_client')->default(false);
            $table->boolean('allow_client_comments')->default(false);
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
        Schema::dropIfExists('projects');
    }
};
