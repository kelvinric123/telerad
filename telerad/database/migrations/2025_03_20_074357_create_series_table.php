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
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_id')->constrained()->onDelete('cascade');
            $table->string('series_uid')->unique()->comment('DICOM Series Instance UID');
            $table->integer('series_number')->nullable();
            $table->string('modality', 10)->nullable();
            $table->string('series_description')->nullable();
            $table->string('body_part_examined')->nullable();
            $table->integer('number_of_instances')->default(0)->comment('Number of images in series');
            $table->string('orthancId')->nullable()->comment('Orthanc Series ID');
            $table->json('dicom_tags')->nullable()->comment('Additional DICOM tags');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('series_number');
            $table->index('modality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
