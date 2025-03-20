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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id')->unique()->comment('DICOM Patient ID');
            $table->string('name')->nullable();
            $table->string('mrn')->nullable()->comment('Medical Record Number');
            $table->date('birth_date')->nullable();
            $table->string('sex', 10)->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('orthancId')->nullable()->comment('Orthanc Patient ID');
            $table->json('dicom_tags')->nullable()->comment('Additional DICOM tags');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
