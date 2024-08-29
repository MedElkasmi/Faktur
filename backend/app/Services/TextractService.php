<?php

namespace App\Services;

use Aws\Textract\TextractClient;
use Aws\Exception\AwsException;
use App\Models\Invoice;

class TextractService
{
    protected $client;

    public function __construct()
    {
        $this->client = new TextractClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);
    }

    public function extractTextFromDocument($s3Object)
    {
        try {
            $result = $this->client->detectDocumentText([
                'Document' => [
                    'S3Object' => [
                        'Bucket' => env('AWS_BUCKET'),
                        'Name' => $s3Object,
                    ],
                ],
            ]);

            $text = '';
            foreach ($result['Blocks'] as $block) {
                if ($block['BlockType'] == 'LINE') {
                    $text .= $block['Text'] . "\n";
                }
            }

            // Parse and store data
            $this->storeParsedData($this->parseExtractedText($text));

            return $text;
        } catch (AwsException $e) {
            return 'Error: ' . $e->getAwsErrorMessage();
        }
    }

    protected function parseExtractedText($text)
    {
        $data = [];

        // Example of parsing specific details
        if (preg_match('/KASSEN-ID:(\d+)/', $text, $matches)) {
            $data['kassen_id'] = $matches[1];
        }

        if (preg_match('/#(\d{6}) \d{2}\/\d{2}\/\d{4} \d{2}:\d{2}/', $text, $matches)) {
            $data['receipt_number'] = $matches[1];
        }

        if (preg_match('/TRANSAKTION\s+GESAMT\n€([\d,]+\.\d{2})/', $text, $matches)) {
            $data['total_amount'] = str_replace(',', '', $matches[1]);
        }

        if (preg_match('/NETTO\n€([\d,]+\.\d{2})/', $text, $matches)) {
            $data['net_amount'] = str_replace(',', '', $matches[1]);
        }

        return $data;
    }

    protected function storeParsedData($parsedData)
    {
        Invoice::create($parsedData);
    }
}
