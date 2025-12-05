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
        Schema::table('demandes', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas déjà avant de les ajouter
            if (!Schema::hasColumn('demandes', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('description');
            }
            if (!Schema::hasColumn('demandes', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('demandes', 'address')) {
                $table->text('address')->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('demandes', 'region')) {
                $table->string('region')->nullable()->after('address');
            }
            if (!Schema::hasColumn('demandes', 'commune')) {
                $table->string('commune')->nullable()->after('region');
            }
            if (!Schema::hasColumn('demandes', 'departement')) {
                $table->string('departement')->nullable()->after('commune');
            }
            if (!Schema::hasColumn('demandes', 'geolocation_manual')) {
                $table->boolean('geolocation_manual')->default(false)->after('departement');
            }
            if (!Schema::hasColumn('demandes', 'geolocation_date')) {
                $table->timestamp('geolocation_date')->nullable()->after('geolocation_manual');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->dropColumn([
                'latitude',
                'longitude',
                'address',
                'region',
                'commune',
                'departement',
                'geolocation_manual',
                'geolocation_date'
            ]);
        });
    }
};
