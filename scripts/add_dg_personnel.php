<?php

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
/** @var \Illuminate\Contracts\Console\Kernel $kernel */
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Personnel;

// Resolve photo source in public/images
$projectRoot = realpath(__DIR__ . '/..');
$candidates = [
    $projectRoot . '/public/images/dg.jpg',
    $projectRoot . '/public/images/DG.jpg',
    $projectRoot . '/public/images/dg.jpeg',
    $projectRoot . '/public/images/dg.png',
];
$sourcePhoto = null;
foreach ($candidates as $c) {
    if (file_exists($c)) { $sourcePhoto = $c; break; }
}

// Ensure destination directory
$destDir = $projectRoot . '/storage/app/public/personnel';
if (!is_dir($destDir)) {
    mkdir($destDir, 0777, true);
}

$photoFileName = null;
if ($sourcePhoto) {
    $photoFileName = 'dg_' . time() . '.' . pathinfo($sourcePhoto, PATHINFO_EXTENSION);
    @copy($sourcePhoto, $destDir . '/' . $photoFileName);
}

// Build payload (fill required fields)
$payload = [
    'prenoms_nom' => 'Directeur Général CSAR',
    'date_naissance' => '1970-01-15',
    'lieu_naissance' => 'Dakar, Sénégal',
    'tranche_age' => '46-55',
    'nationalite' => 'Sénégalaise',
    'numero_cni' => 'DG-CNI-0001',
    'sexe' => 'Masculin',
    'situation_matrimoniale' => 'Marie',
    'nombre_enfants' => 2,
    'contact_telephonique' => '+221 77 000 00 00',
    'email' => 'dg.personnel@csar.sn',
    'groupe_sanguin' => 'O+',
    'adresse_complete' => 'CSAR, Dakar, Sénégal',
    'matricule' => Personnel::genererMatricule(),
    'date_recrutement_csar' => date('Y-m-d'),
    'date_prise_service_csar' => date('Y-m-d'),
    'statut' => 'Fonctionnaire',
    'poste_actuel' => 'Directeur Général',
    'direction_service' => 'Direction Generale',
    'localisation_region' => 'Dakar',
    'dernier_poste_avant_csar' => 'Haut fonctionnaire',
    'formations_professionnelles' => 'Management public, Leadership',
    'diplome_academique' => 'Doctorat',
    'autres_diplomes_certifications' => 'Gouvernance & Gestion',
    'logiciels_maitrises' => json_encode(['Word','Excel','PowerPoint']),
    'langues_parlees' => json_encode(['Français']),
    'autres_aptitudes' => 'Leadership, stratégie',
    'aspirations_professionnelles' => 'Renforcement institutionnel',
    'interet_nouvelles_responsabilites' => 'Non',
    'photo_personnelle' => $photoFileName,
    'taille_vetements' => 'XL',
    'contact_urgence_nom' => 'Conjoint(e) DG',
    'contact_urgence_telephone' => '+221 77 111 11 11',
    'contact_urgence_lien_parente' => 'Conjoint(e)',
    'observations_personnelles' => 'Fiche créée automatiquement',
];

// Create or update by email
$p = Personnel::updateOrCreate(
    ['email' => $payload['email']],
    $payload
);

echo "DG personnel créé/mis à jour: ID={$p->id}, email={$p->email}\n";
echo "Photo: " . ($photoFileName ?: 'Aucune (avatar)') . "\n";






