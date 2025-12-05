<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use App\Models\Stock;
use App\Models\SimReport;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportController extends Controller
{
    /**
     * Exporter les demandes
     */
    public function exportDemandes(Request $request)
    {
        $user = Auth::user();
        $format = $request->get('format', 'excel');
        $filters = $request->only(['status', 'region', 'date_from', 'date_to']);

        $demandes = PublicRequest::query();

        // Filtrage par rôle
        if ($user->role === 'agent') {
            $demandes->where('user_id', $user->id);
        } elseif ($user->role === 'responsable') {
            $demandes->where('warehouse_id', $user->warehouse_id);
        }

        // Application des filtres
        if (!empty($filters['status'])) {
            $demandes->where('status', $filters['status']);
        }
        if (!empty($filters['region'])) {
            $demandes->where('region', $filters['region']);
        }
        if (!empty($filters['date_from'])) {
            $demandes->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $demandes->whereDate('created_at', '<=', $filters['date_to']);
        }

        $demandes = $demandes->with(['user', 'warehouse'])->get();

        if ($format === 'excel') {
            return $this->exportToExcel($demandes, 'demandes');
        } elseif ($format === 'csv') {
            return $this->exportToCsv($demandes, 'demandes');
        } elseif ($format === 'pdf') {
            return $this->exportToPdf($demandes, 'demandes');
        }

        return back()->with('error', 'Format d\'export non supporté.');
    }

    /**
     * Exporter les demandes publiques (PublicRequest)
     */
    public function exportPublicRequests($demandes, $format = 'excel')
    {
        if ($format === 'excel') {
            return $this->exportPublicRequestsToExcel($demandes);
        } elseif ($format === 'csv') {
            return $this->exportPublicRequestsToCsv($demandes);
        } elseif ($format === 'pdf') {
            return $this->exportPublicRequestsToPdf($demandes);
        }

        return back()->with('error', 'Format d\'export non supporté.');
    }

    /**
     * Exporter les stocks
     */
    public function exportStocks(Request $request)
    {
        $user = Auth::user();
        $format = $request->get('format', 'excel');
        $filters = $request->only(['warehouse_id', 'stock_type_id', 'low_stock']);

        $stocks = Stock::query();

        // Filtrage par rôle
        if ($user->role === 'responsable') {
            $stocks->where('warehouse_id', $user->warehouse_id);
        }

        // Application des filtres
        if (!empty($filters['warehouse_id'])) {
            $stocks->where('warehouse_id', $filters['warehouse_id']);
        }
        if (!empty($filters['stock_type_id'])) {
            $stocks->where('stock_type_id', $filters['stock_type_id']);
        }
        if (!empty($filters['low_stock'])) {
            $stocks->where('quantity', '<', 100);
        }

        $stocks = $stocks->with(['stockType', 'warehouse'])->get();

        if ($format === 'excel') {
            return $this->exportToExcel($stocks, 'stocks');
        } elseif ($format === 'csv') {
            return $this->exportToCsv($stocks, 'stocks');
        }

        return back()->with('error', 'Format d\'export non supporté.');
    }

    /**
     * Exporter les rapports SIM
     */
    public function exportReports(Request $request)
    {
        $format = $request->get('format', 'excel');
        $filters = $request->only(['report_type', 'region', 'status', 'date_from', 'date_to']);

        $reports = SimReport::query();

        // Application des filtres
        if (!empty($filters['report_type'])) {
            $reports->where('report_type', $filters['report_type']);
        }
        if (!empty($filters['region'])) {
            $reports->where('region', $filters['region']);
        }
        if (!empty($filters['status'])) {
            $reports->where('status', $filters['status']);
        }
        if (!empty($filters['date_from'])) {
            $reports->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $reports->whereDate('created_at', '<=', $filters['date_to']);
        }

        $reports = $reports->get();

        if ($format === 'excel') {
            return $this->exportToExcel($reports, 'reports');
        } elseif ($format === 'csv') {
            return $this->exportToCsv($reports, 'reports');
        }

        return back()->with('error', 'Format d\'export non supporté.');
    }

    /**
     * Exporter vers Excel
     */
    private function exportToExcel($data, $type)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Configuration de base
        $sheet->setTitle(ucfirst($type));

        // En-têtes selon le type
        $headers = $this->getHeaders($type);
        $row = 1;

        // Style des en-têtes
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2c5530']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];

        // Ajouter les en-têtes
        $col = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col, $row, $header);
            $sheet->getStyleByColumnAndRow($col, $row)->applyFromArray($headerStyle);
            $col++;
        }

        // Ajouter les données
        $row = 2;
        foreach ($data as $item) {
            $col = 1;
            $rowData = $this->getRowData($item, $type);
            
            foreach ($rowData as $value) {
                $sheet->setCellValueByColumnAndRow($col, $row, $value);
                $col++;
            }
            $row++;
        }

        // Ajuster la largeur des colonnes
        foreach (range('A', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Créer le fichier
        $filename = $type . '_export_' . date('Y-m-d_H-i-s') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        $tempFile = tempnam(sys_get_temp_dir(), 'export');
        $writer->save($tempFile);

        return Response::download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Exporter vers CSV
     */
    private function exportToCsv($data, $type)
    {
        $headers = $this->getHeaders($type);
        $filename = $type . '_export_' . date('Y-m-d_H-i-s') . '.csv';

        $callback = function() use ($data, $headers, $type) {
            $file = fopen('php://output', 'w');
            
            // BOM pour UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // En-têtes
            fputcsv($file, $headers, ';');
            
            // Données
            foreach ($data as $item) {
                $rowData = $this->getRowData($item, $type);
                fputcsv($file, $rowData, ';');
            }
            
            fclose($file);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Obtenir les en-têtes selon le type
     */
    private function getHeaders($type)
    {
        switch ($type) {
            case 'demandes':
                return [
                    'ID', 'Description', 'Statut', 'Région', 'Utilisateur', 
                    'Entrepôt', 'Quantité Demandée', 'Date de Création', 'Date de Mise à Jour'
                ];
            case 'stocks':
                return [
                    'ID', 'Type de Stock', 'Quantité', 'Entrepôt', 'Région', 
                    'Prix Unitaire', 'Valeur Totale', 'Date de Mise à Jour'
                ];
            case 'reports':
                return [
                    'ID', 'Titre', 'Type', 'Région', 'Secteur', 'Statut', 
                    'Date de Création', 'Créateur'
                ];
            default:
                return [];
        }
    }

    /**
     * Obtenir les données de ligne selon le type
     */
    private function getRowData($item, $type)
    {
        switch ($type) {
            case 'demandes':
                return [
                    $item->id,
                    $item->description,
                    ucfirst($item->status),
                    $item->region ?? 'N/A',
                    $item->user->name ?? 'N/A',
                    $item->warehouse->name ?? 'N/A',
                    $item->quantity_requested ?? 'N/A',
                    $item->created_at->format('d/m/Y H:i'),
                    $item->updated_at->format('d/m/Y H:i')
                ];
            case 'stocks':
                return [
                    $item->id,
                    $item->stockType->name ?? 'N/A',
                    $item->quantity,
                    $item->warehouse->name ?? 'N/A',
                    $item->warehouse->region ?? 'N/A',
                    $item->stockType->unit_price ?? 'N/A',
                    ($item->quantity * ($item->stockType->unit_price ?? 0)),
                    $item->updated_at->format('d/m/Y H:i')
                ];
            case 'reports':
                return [
                    $item->id,
                    $item->title,
                    $item->report_type,
                    $item->region ?? 'N/A',
                    $item->market_sector ?? 'N/A',
                    ucfirst($item->status),
                    $item->created_at->format('d/m/Y H:i'),
                    $item->creator->name ?? 'N/A'
                ];
            default:
                return [];
        }
    }

    /**
     * Exporter vers PDF (basique)
     */
    private function exportToPdf($data, $type)
    {
        // Cette fonction nécessiterait une bibliothèque PDF comme DomPDF
        // Pour l'instant, on redirige vers Excel
        return $this->exportToExcel($data, $type);
    }

    /**
     * Télécharger un modèle d'import
     */
    public function downloadTemplate($type)
    {
        $templates = [
            'demandes' => [
                'ID', 'Description', 'Statut', 'Région', 'Quantité Demandée'
            ],
            'stocks' => [
                'Type de Stock', 'Quantité', 'Entrepôt ID'
            ],
            'users' => [
                'Nom', 'Email', 'Rôle', 'Entrepôt ID'
            ]
        ];

        if (!isset($templates[$type])) {
            return back()->with('error', 'Type de modèle non supporté.');
        }

        $filename = 'template_' . $type . '.csv';
        $headers = $templates[$type];

        $callback = function() use ($headers) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // BOM pour UTF-8
            fputcsv($file, $headers, ';');
            fclose($file);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Exporter les demandes publiques vers Excel
     */
    private function exportPublicRequestsToExcel($demandes)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Demandes Publiques');

        // En-têtes
        $headers = [
            'Code de Suivi', 'Type', 'Statut', 'Nom Complet', 'Email', 'Téléphone',
            'Adresse', 'Région', 'Description', 'Commentaire Admin', 'Assigné à',
            'Date de Demande', 'Date de Traitement', 'SMS Envoyé', 'Consulté',
            'Date de Consultation', 'Date de Création', 'Date de Mise à Jour'
        ];

        // Style des en-têtes
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2c5530']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];

        // Ajouter les en-têtes
        $col = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col, 1, $header);
            $sheet->getStyleByColumnAndRow($col, 1)->applyFromArray($headerStyle);
            $col++;
        }

        // Ajouter les données
        $row = 2;
        foreach ($demandes as $demande) {
            $sheet->setCellValue('A' . $row, $demande->tracking_code ?? 'N/A');
            $sheet->setCellValue('B' . $row, ucfirst($demande->type ?? 'N/A'));
            $sheet->setCellValue('C' . $row, ucfirst($demande->status ?? 'N/A'));
            $sheet->setCellValue('D' . $row, $demande->full_name ?? 'N/A');
            $sheet->setCellValue('E' . $row, $demande->email ?? 'N/A');
            $sheet->setCellValue('F' . $row, $demande->phone ?? 'N/A');
            $sheet->setCellValue('G' . $row, $demande->address ?? 'N/A');
            $sheet->setCellValue('H' . $row, $demande->region ?? 'N/A');
            $sheet->setCellValue('I' . $row, $demande->description ?? 'N/A');
            $sheet->setCellValue('J' . $row, $demande->admin_comment ?? 'N/A');
            $sheet->setCellValue('K' . $row, $demande->assignedTo->name ?? 'N/A');
            $sheet->setCellValue('L' . $row, $demande->request_date ? $demande->request_date->format('d/m/Y') : 'N/A');
            $sheet->setCellValue('M' . $row, $demande->processed_date ? $demande->processed_date->format('d/m/Y') : 'N/A');
            $sheet->setCellValue('N' . $row, $demande->sms_sent ? 'Oui' : 'Non');
            $sheet->setCellValue('O' . $row, $demande->is_viewed ? 'Oui' : 'Non');
            $sheet->setCellValue('P' . $row, $demande->viewed_at ? $demande->viewed_at->format('d/m/Y H:i') : 'N/A');
            $sheet->setCellValue('Q' . $row, $demande->created_at->format('d/m/Y H:i'));
            $sheet->setCellValue('R' . $row, $demande->updated_at->format('d/m/Y H:i'));
            $row++;
        }

        // Ajuster la largeur des colonnes
        foreach (range('A', 'R') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Créer le fichier
        $filename = 'demandes_publiques_export_' . date('Y-m-d_H-i-s') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        $tempFile = tempnam(sys_get_temp_dir(), 'export');
        $writer->save($tempFile);

        return Response::download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Exporter les demandes publiques vers CSV
     */
    private function exportPublicRequestsToCsv($demandes)
    {
        $filename = 'demandes_publiques_export_' . date('Y-m-d_H-i-s') . '.csv';

        $callback = function() use ($demandes) {
            $file = fopen('php://output', 'w');
            
            // BOM pour UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // En-têtes
            $headers = [
                'Code de Suivi', 'Type', 'Statut', 'Nom Complet', 'Email', 'Téléphone',
                'Adresse', 'Région', 'Description', 'Commentaire Admin', 'Assigné à',
                'Date de Demande', 'Date de Traitement', 'SMS Envoyé', 'Consulté',
                'Date de Consultation', 'Date de Création', 'Date de Mise à Jour'
            ];
            fputcsv($file, $headers, ';');
            
            // Données
            foreach ($demandes as $demande) {
                $rowData = [
                    $demande->tracking_code ?? 'N/A',
                    ucfirst($demande->type ?? 'N/A'),
                    ucfirst($demande->status ?? 'N/A'),
                    $demande->full_name ?? 'N/A',
                    $demande->email ?? 'N/A',
                    $demande->phone ?? 'N/A',
                    $demande->address ?? 'N/A',
                    $demande->region ?? 'N/A',
                    $demande->description ?? 'N/A',
                    $demande->admin_comment ?? 'N/A',
                    $demande->assignedTo->name ?? 'N/A',
                    $demande->request_date ? $demande->request_date->format('d/m/Y') : 'N/A',
                    $demande->processed_date ? $demande->processed_date->format('d/m/Y') : 'N/A',
                    $demande->sms_sent ? 'Oui' : 'Non',
                    $demande->is_viewed ? 'Oui' : 'Non',
                    $demande->viewed_at ? $demande->viewed_at->format('d/m/Y H:i') : 'N/A',
                    $demande->created_at->format('d/m/Y H:i'),
                    $demande->updated_at->format('d/m/Y H:i')
                ];
                fputcsv($file, $rowData, ';');
            }
            
            fclose($file);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Exporter les demandes publiques vers PDF
     */
    private function exportPublicRequestsToPdf($demandes)
    {
        // Pour l'instant, on redirige vers Excel car DomPDF n'est pas installé
        // Vous pouvez installer DomPDF avec: composer require barryvdh/laravel-dompdf
        return $this->exportPublicRequestsToExcel($demandes);
    }
}
