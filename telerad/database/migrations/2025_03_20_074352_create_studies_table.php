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
        Schema::create('studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('study_uid')->unique()->comment('DICOM Study Instance UID');
            $table->string('accession_number')->nullable();
            $table->string('study_id')->nullable()->comment('DICOM Study ID');
            $table->string('study_description')->nullable();
            $table->date('study_date')->nullable();
            $table->time('study_time')->nullable();
            $table->string('referring_physician')->nullable();
            $table->string('orthancId')->nullable()->comment('Orthanc Study ID');
            $table->json('modalities')->nullable()->comment('Array of modalities');
            $table->json('dicom_tags')->nullable()->comment('Additional DICOM tags');
            $table->boolean('is_fetched')->default(false)->comment('Whether study is fetched from Orthanc');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('study_date');
            $table->index('accession_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studies');
    }
};
