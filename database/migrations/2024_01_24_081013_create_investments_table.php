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
            $table->unsignedBigInteger('property_id');
            $table->json('patch_id');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('returns', 10, 2)->default(0);
            $table->string('doc_url')->nullable();
            $table->string('status');
            $table->date('start_date')->nullable();
            $table->timestamps();

            // Define foreign key constraint if property_id is a foreign key
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
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
