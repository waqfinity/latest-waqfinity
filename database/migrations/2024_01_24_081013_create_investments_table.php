<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('property_id'); // Assuming property_id is a foreign key
            $table->unsignedBigInteger('patch_id'); // Assuming patch_id is a foreign key
            $table->decimal('returns', 10, 2)->default(0); // Assuming returns is a decimal column with default value
            $table->timestamps();

            // Define foreign key constraint if property_id and patch_id are foreign keys
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('patch_id')->references('id')->on('patches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investments');
    }
};
