<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('geolocations', function (Blueprint $table) {
            $table->id('geo_location_id');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->string('ip', 50);
            $table->string('hostname', 150);
            $table->string('city', 50);
            $table->string('region', 50);
            $table->string('country', 50);
            $table->string('loc', 50);
            $table->string('org', 50);
            $table->string('postal', 50);
            $table->string('time_zone', 150);
            $table->string('readme', 150)->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geolocations');
    }
};
