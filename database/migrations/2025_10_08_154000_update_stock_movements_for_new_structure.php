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
            // Ajouter les colonnes manquantes pour la nouvelle structure
            $table->string('produit')->nullable()->after('type');
            $table->string('unite')->nullable()->after('produit');
            $table->decimal('prix_unitaire', 10, 2)->nullable()->after('unite');
            $table->decimal('total', 10, 2)->nullable()->after('prix_unitaire');
            $table->unsignedBigInteger('entrepot_id')->nullable()->after('total');
            $table->string('responsable')->nullable()->after('entrepot_id');
            $table->text('motif')->nullable()->after('responsable');
            $table->datetime('date_mouvement')->nullable()->after('motif');
            
            // Champs spécifiques selon le type
            $table->string('fournisseur')->nullable()->after('date_mouvement');
            $table->string('numero_facture')->nullable()->after('fournisseur');
            $table->string('destinataire')->nullable()->after('numero_facture');
            $table->string('numero_bon')->nullable()->after('destinataire');
            $table->unsignedBigInteger('entrepot_destination_id')->nullable()->after('numero_bon');
            $table->string('numero_transfert')->nullable()->after('entrepot_destination_id');
            $table->string('raison_ajustement')->nullable()->after('numero_transfert');
            
            // Ajouter les clés étrangères
            $table->foreign('entrepot_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('entrepot_destination_id')->references('id')->on('warehouses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            // Supprimer les clés étrangères
            $table->dropForeign(['entrepot_id']);
            $table->dropForeign(['entrepot_destination_id']);
            
            // Supprimer les colonnes ajoutées
            $table->dropColumn([
                'produit', 'unite', 'prix_unitaire', 'total', 'entrepot_id',
                'responsable', 'motif', 'date_mouvement', 'fournisseur',
                'numero_facture', 'destinataire', 'numero_bon',
                'entrepot_destination_id', 'numero_transfert', 'raison_ajustement'
            ]);
        });
    }
};


