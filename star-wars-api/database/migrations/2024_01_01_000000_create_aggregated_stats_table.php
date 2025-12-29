<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aggregated_stats', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->string('route', 255)->nullable();
            $table->string('method', 10)->nullable();
            $table->timestamp('measure_date')->nullable();
            $table->string('date_interval', 10)->nullable();
            $table->float('duration_sum')->nullable();
            $table->integer('ticks')->nullable();
            $table->float('average_response_time')->nullable();

            $table->unique(['route', 'method', 'measure_date'], 'unique_data');
            $table->index('route');
            $table->index('method');
            $table->index('measure_date');
            $table->index(['route', 'method'], 'route_method');
            $table->index(['route', 'method', 'measure_date'], 'route_method_measure_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aggregated_stats');
    }
};
