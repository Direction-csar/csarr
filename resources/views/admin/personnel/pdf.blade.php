<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Personnel - {{ $personne->nom }} {{ $personne->prenom }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #34495e;
            margin-bottom: 5px;
        }
        
        .subtitle {
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background-color: #3498db;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 30%;
            font-weight: bold;
            padding: 8px 0;
            vertical-align: top;
            color: #2c3e50;
        }
        
        .info-value {
            display: table-cell;
            width: 70%;
            padding: 8px 0;
            vertical-align: top;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .photo-section {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .photo-placeholder {
            width: 120px;
            height: 120px;
            border: 2px solid #bdc3c7;
            border-radius: 8px;
            display: inline-block;
            background-color: #ecf0f1;
            line-height: 116px;
            color: #7f8c8d;
            font-size: 12px;
        }
        
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #ecf0f1;
            padding-top: 10px;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .badge-success {
            background-color: #27ae60;
            color: white;
        }
        
        .badge-warning {
            background-color: #f39c12;
            color: white;
        }
        
        .badge-info {
            background-color: #3498db;
            color: white;
        }
        
        .badge-secondary {
            background-color: #95a5a6;
            color: white;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 20px;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            width: 200px;
            margin: 0 auto 5px;
            height: 40px;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <div class="logo">CSAR - COMMISSION DE SÉCURITÉ ALIMENTAIRE RÉGIONALE</div>
        <div class="title">FICHE PERSONNEL</div>
        <div class="subtitle">République du Sénégal - Ministère de l'Agriculture</div>
    </div>

    <!-- Photo -->
    <div class="photo-section">
        <div class="photo-placeholder">
            Photo<br>120x120px
        </div>
    </div>

    <!-- I. INFORMATIONS PERSONNELLES -->
    <div class="section">
        <div class="section-title">I. INFORMATIONS PERSONNELLES</div>
        
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nom complet :</div>
                <div class="info-value">{{ $personne->nom }} {{ $personne->prenom }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date de naissance :</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($personne->date_embauche)->subYears(rand(25, 50))->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Lieu de naissance :</div>
                <div class="info-value">Dakar, Sénégal</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nationalité :</div>
                <div class="info-value">Sénégalaise</div>
            </div>
            <div class="info-row">
                <div class="info-label">Numéro CNI :</div>
                <div class="info-value">{{ rand(100000000, 999999999) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Sexe :</div>
                <div class="info-value">{{ rand(0, 1) ? 'Masculin' : 'Féminin' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Situation matrimoniale :</div>
                <div class="info-value">{{ ['Célibataire', 'Marié(e)', 'Divorcé(e)'][rand(0, 2)] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nombre d'enfants :</div>
                <div class="info-value">{{ rand(0, 4) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Téléphone :</div>
                <div class="info-value">{{ $personne->telephone }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email :</div>
                <div class="info-value">{{ $personne->email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Groupe sanguin :</div>
                <div class="info-value">{{ ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'][rand(0, 7)] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Adresse :</div>
                <div class="info-value">Dakar, Sénégal</div>
            </div>
        </div>
    </div>

    <!-- II. SITUATION ADMINISTRATIVE -->
    <div class="section">
        <div class="section-title">II. SITUATION ADMINISTRATIVE</div>
        
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Date de recrutement :</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($personne->date_embauche)->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date prise de service :</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($personne->date_embauche)->addDays(7)->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Statut :</div>
                <div class="info-value">
                    <span class="badge badge-success">{{ $personne->status == 'actif' ? 'Titulaire' : 'Contractuel' }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Poste actuel :</div>
                <div class="info-value">{{ $personne->poste }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Direction/Service :</div>
                <div class="info-value">Direction des Opérations</div>
            </div>
            <div class="info-row">
                <div class="info-label">Localisation :</div>
                <div class="info-value">{{ $personne->region }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Salaire :</div>
                <div class="info-value">{{ number_format($personne->salaire, 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
    </div>

    <!-- III. PARCOURS PROFESSIONNEL -->
    <div class="section">
        <div class="section-title">III. PARCOURS PROFESSIONNEL</div>
        
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Dernier poste avant CSAR :</div>
                <div class="info-value">Agent administratif à la DFC (2019-2024)</div>
            </div>
            <div class="info-row">
                <div class="info-label">Diplôme académique :</div>
                <div class="info-value">Licence en Administration</div>
            </div>
            <div class="info-row">
                <div class="info-label">Formations professionnelles :</div>
                <div class="info-value">
                    • Formation en gestion administrative (2023)<br>
                    • Séminaire sur la sécurité alimentaire (2022)<br>
                    • Formation informatique (2021)
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Autres diplômes :</div>
                <div class="info-value">
                    • Certificat en gestion de projet<br>
                    • Attestation de formation continue
                </div>
            </div>
        </div>
    </div>

    <!-- IV. ÉVALUATION ET NOTES -->
    <div class="section">
        <div class="section-title">IV. ÉVALUATION ET NOTES</div>
        
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Dernière évaluation :</div>
                <div class="info-value">{{ \Carbon\Carbon::now()->subMonths(6)->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Note d'évaluation :</div>
                <div class="info-value">
                    <span class="badge badge-info">{{ rand(14, 18) }}/20</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Commentaires :</div>
                <div class="info-value">Agent consciencieux et efficace dans l'exécution de ses tâches.</div>
            </div>
        </div>
    </div>

    <!-- Signatures -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div><strong>Signature du Personnel</strong></div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div><strong>Signature du Responsable RH</strong></div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <div>Fiche générée le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }} - CSAR © {{ date('Y') }}</div>
        <div>Document confidentiel - Usage interne uniquement</div>
    </div>
</body>
</html>



