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
        Schema::table('stock_movements', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas déjà avant de les ajouter
            if (!Schema::hasColumn('stock_movements', 'produit')) {
                $table->string('produit')->nullable()->after('type');
            }
            if (!Schema::hasColumn('stock_movements', 'unite')) {
                $table->string('unite')->nullable()->after('produit');
            }
            if (!Schema::hasColumn('stock_movements', 'prix_unitaire')) {
                $table->decimal('prix_unitaire', 10, 2)->nullable()->after('unite');
            }
            if (!Schema::hasColumn('stock_movements', 'total')) {
                $table->decimal('total', 10, 2)->nullable()->after('prix_unitaire');
            }
            if (!Schema::hasColumn('stock_movements', 'entrepot_id')) {
                $table->unsignedBigInteger('entrepot_id')->nullable()->after('total');
            }
            if (!Schema::hasColumn('stock_movements', 'responsable')) {
                $table->string('responsable')->nullable()->after('entrepot_id');
            }
            if (!Schema::hasColumn('stock_movements', 'motif')) {
                $table->text('motif')->nullable()->after('responsable');
            }
            if (!Schema::hasColumn('stock_movements', 'date_mouvement')) {
                $table->datetime('date_mouvement')->nullable()->after('motif');
            }
            
            // Champs spécifiques selon le type
            if (!Schema::hasColumn('stock_movements', 'fournisseur')) {
                $table->string('fournisseur')->nullable()->after('date_mouvement');
            }
            if (!Schema::hasColumn('stock_movements', 'numero_facture')) {
                $table->string('numero_facture')->nullable()->after('fournisseur');
            }
            if (!Schema::hasColumn('stock_movements', 'destinataire')) {
                $table->string('destinataire')->nullable()->after('numero_facture');
            }
            if (!Schema::hasColumn('stock_movements', 'numero_bon')) {
                $table->string('numero_bon')->nullable()->after('destinataire');
            }
            if (!Schema::hasColumn('stock_movements', 'entrepot_destination_id')) {
                $table->unsignedBigInteger('entrepot_destination_id')->nullable()->after('numero_bon');
            }
            if (!Schema::hasColumn('stock_movements', 'numero_transfert')) {
                $table->string('numero_transfert')->nullable()->after('entrepot_destination_id');
            }
            if (!Schema::hasColumn('stock_movements', 'raison_ajustement')) {
                $table->string('raison_ajustement')->nullable()->after('numero_transfert');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropColumn([
                'produit', 'unite', 'prix_unitaire', 'total', 'entrepot_id',
                'responsable', 'motif', 'date_mouvement', 'fournisseur',
                'numero_facture', 'destinataire', 'numero_bon',
                'entrepot_destination_id', 'numero_transfert', 'raison_ajustement'
            ]);
        });
    }
};


