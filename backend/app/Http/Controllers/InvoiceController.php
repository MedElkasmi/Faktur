<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Services\TextractService;
use Aws\S3\Exception\S3Exception;
use Aws\Textract\Exception\TextractException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;



class InvoiceController extends Controller
{

    protected $textractService;

    public function __construct(TextractService $textractService){
        $this->textractService = $textractService;
    }

    public function uploadAndExtractText(Request $request) {

        try {
            // Validate the uploaded file
            $request->validate([
                'file_path' => 'required|file|mimes:jpeg,png,pdf|max:5120', // Example validation
            ]);

            // Upload the file to S3
            $path = $request->file('file_path')->store('uploads', 's3');

            // Extract text using Textract
            $extractedText = $this->textractService->extractTextFromDocument($path);

            return response()->json([
                'message' => 'Text extracted successfully',
                'data' => $extractedText,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (S3Exception $e) {
            Log::error('S3 Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to upload file to S3',
                'error' => $e->getAwsErrorMessage(),
            ], 500);
        } catch (TextractException $e) {
            Log::error('Textract Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to extract text from document',
                'error' => $e->getAwsErrorMessage(),
            ], 500);
        } catch (\Exception $e) {
            Log::error('General Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
