<?php

namespace App\Http\Controllers;

use App\Models\ScannedDocument;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;

class IDScannerController extends Controller
{
    /**
     * Scan an ID document and extract information
     */
    public function scan(Request $request)
    {
        $request->validate([
            'document' => 'required|image|mimes:jpeg,png,jpg|max:10240', // Max 10MB
            'document_type' => 'nullable|string|in:id_card,passport,driver_license',
            'resident_id' => 'nullable|exists:residents,id',
        ]);

        try {
            // Store the uploaded image
            $path = $request->file('document')->store('scanned_documents', 'public');
            $fullPath = storage_path('app/public/' . $path);

            // Get absolute path to Python script
            $scriptPath = base_path('scripts/id_scanner.py');
            
            // Check if Python script exists
            if (!file_exists($scriptPath)) {
                return response()->json([
                    'success' => false,
                    'error' => 'OCR script not found. Please ensure Python dependencies are installed.'
                ], 500);
            }

            // Run Python OCR script
            $result = Process::run("python \"{$scriptPath}\" \"{$fullPath}\"");

            if (!$result->successful()) {
                Log::error('OCR Script Error: ' . $result->errorOutput());
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to process document. Error: ' . $result->errorOutput()
                ], 500);
            }

            // Parse OCR result
            $ocrResult = json_decode($result->output(), true);

            if (!$ocrResult || !isset($ocrResult['success']) || !$ocrResult['success']) {
                return response()->json([
                    'success' => false,
                    'error' => $ocrResult['error'] ?? 'Failed to scan document'
                ], 500);
            }

            // Save to database
            $scannedDoc = ScannedDocument::create([
                'resident_id' => $request->resident_id,
                'user_id' => auth()->id(),
                'document_type' => $request->document_type ?? 'id_card',
                'document_path' => $path,
                'raw_text' => $ocrResult['raw_text'] ?? null,
                'extracted_fields' => $ocrResult['fields'] ?? [],
                'confidence_score' => $ocrResult['confidence'] ?? null,
                'verification_status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document scanned successfully',
                'data' => [
                    'id' => $scannedDoc->id,
                    'extracted_fields' => $scannedDoc->extracted_fields,
                    'confidence_score' => $scannedDoc->confidence_score,
                    'raw_text' => $scannedDoc->raw_text,
                    'document_url' => Storage::url($path),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('ID Scanner Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing the document: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all scanned documents
     */
    public function index(Request $request)
    {
        $query = ScannedDocument::with(['resident', 'user'])
            ->orderBy('created_at', 'desc');

        // Filter by resident
        if ($request->has('resident_id')) {
            $query->where('resident_id', $request->resident_id);
        }

        // Filter by verification status
        if ($request->has('status')) {
            $query->where('verification_status', $request->status);
        }

        $documents = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $documents
        ]);
    }

    /**
     * Get a specific scanned document
     */
    public function show($id)
    {
        $document = ScannedDocument::with(['resident', 'user'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $document->id,
                'resident' => $document->resident,
                'document_type' => $document->document_type,
                'extracted_fields' => $document->extracted_fields,
                'raw_text' => $document->raw_text,
                'confidence_score' => $document->confidence_score,
                'verification_status' => $document->verification_status,
                'document_url' => Storage::url($document->document_path),
                'created_at' => $document->created_at,
                'notes' => $document->notes,
            ]
        ]);
    }

    /**
     * Update verification status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,rejected',
            'notes' => 'nullable|string',
        ]);

        $document = ScannedDocument::findOrFail($id);
        $document->update([
            'verification_status' => $request->status,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $document
        ]);
    }

    /**
     * Delete a scanned document
     */
    public function destroy($id)
    {
        $document = ScannedDocument::findOrFail($id);
        
        // Delete file from storage
        if ($document->document_path && Storage::disk('public')->exists($document->document_path)) {
            Storage::disk('public')->delete($document->document_path);
        }
        
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully'
        ]);
    }

    /**
     * Auto-fill resident form with scanned data
     */
    public function autoFill(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:scanned_documents,id',
        ]);

        $document = ScannedDocument::findOrFail($request->document_id);
        $fields = $document->extracted_fields;

        // Map extracted fields to resident model fields
        $mappedData = [
            'name' => $fields['name'] ?? null,
            'date_of_birth' => $fields['date_of_birth'] ?? null,
            'sex' => $fields['sex'] ?? null,
            'address' => $fields['address'] ?? null,
            'id_number' => $fields['id_number'] ?? null,
        ];

        return response()->json([
            'success' => true,
            'data' => $mappedData
        ]);
    }
}
