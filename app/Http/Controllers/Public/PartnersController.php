<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TechnicalPartner;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PartnersController extends Controller
{
    public function index()
    {
        $partners = TechnicalPartner::where('status', 'active')
            ->orderBy('name')
            ->get();

        // Partners coming from database (normalized to a simple array structure)
        $dbItems = $partners->map(function (TechnicalPartner $p) {
            return [
                'name' => $p->name,
                'organization' => $p->organization,
                'type' => $p->type ?: 'institution',
                'website' => $p->website,
                'logo_url' => $p->logo ? \Storage::url($p->logo) : null,
            ];
        })->values()->all();

        // Curated partners from the annual report (logos expected in public/images/partners)
        $staticItems = [
            // FSRP - Programme de Résilience du Système Alimentaire
            [
                'name' => 'FSRP',
                'organization' => 'Programme de Résilience du Système Alimentaire en Afrique de l\'Ouest',
                'type' => 'agency',
                'website' => 'https://fsrp.araa.org/fr',
                'logo_url' => asset('images/partners/fsrp.png')
            ],
            // JICA - Agence Japonaise de Coopération Internationale
            ['name' => 'JICA – Agence Japonaise de Coopération Internationale', 'type' => 'agency', 'website' => 'https://www.jica.go.jp/french/', 'logo_url' => asset('images/partners/jica.jpg')],
            // ANSD - Agence Nationale de la Statistique et de la Démographie
            ['name' => 'ANSD – Agence Nationale de la Statistique et de la Démographie', 'type' => 'institution', 'website' => 'https://recrute.ansd.sn/', 'logo_url' => asset('images/partners/ANSD.png')],
            // FONGIP - Fonds de Garantie des Investissements Prioritaires
            ['name' => 'FONGIP – Fonds de Garantie des Investissements Prioritaires', 'type' => 'institution', 'website' => 'https://www.fongip.sn/', 'logo_url' => asset('images/partners/fongip.jpeg')],
        ];

        // Map known slugs to direct websites and types
        $slugMap = [
            'fsrp' => ['url' => 'https://fsrp.araa.org/fr', 'type' => 'agency', 'name' => 'FSRP'],
            'jica' => ['url' => 'https://www.jica.go.jp/french/', 'type' => 'agency', 'name' => 'JICA'],
            'ansd' => ['url' => 'https://recrute.ansd.sn/', 'type' => 'institution', 'name' => 'ANSD'],
            'fongip' => ['url' => 'https://www.fongip.sn/', 'type' => 'institution', 'name' => 'FONGIP'],
        ];

        // Read all files from public/images/partners and build clickable items
        $fileItems = [];
        $files = File::exists(public_path('images/partners')) ? File::files(public_path('images/partners')) : [];
        foreach ($files as $file) {
            $basename = $file->getFilename();
            $slug = strtolower(pathinfo($basename, PATHINFO_FILENAME));
            $map = $slugMap[$slug] ?? null;
            $fileItems[] = [
                'name' => $map['name'] ?? Str::headline(str_replace(['_', '-'], ' ', $slug)),
                'organization' => null,
                'type' => $map['type'] ?? 'agency',
                'website' => $map['url'] ?? '#',
                'logo_url' => asset('images/partners/' . $basename),
            ];
        }

        // N'afficher QUE les logos présents dans public/images/partners
        $allItems = collect($fileItems)
            ->unique(fn ($item) => ($item['logo_url'] ?? '') . '|' . ($item['name'] ?? ''))
            ->values();
        $grouped = $allItems->groupBy('type');

        return view('public.partners', [
            'grouped' => $grouped,
        ]);
    }
}