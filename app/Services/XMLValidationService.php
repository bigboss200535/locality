<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use Illu1minate\Support\Facade\Log;
// use Spatie\PdfToText\Pdf;

class XMLValidationService
{
   
    protected KnowledgeBaseService $knowledge_base;

    public function __construct(KnowledgeBaseService $knowledge_base)
    {
        $this->knowledge_base = $knowledge_base;
    }

    // Validate document with the AI
    public function validate(string $xml_content): array
    {
        $knowledge = $this->knowledge_base->get_knowledge_base();

        $system_prompt = <<<PROMPT
        You are a strict NHIS Claims vetting officer. Your knowledge base is provided below.
        Use ONLY this knowledge to validate the XML document that the user will send. 

        Knowledge base:
        {$knowledge}

        Validation rules: 
        - Every medicine must exist in the medicine list with G-DRG code. 
        - Every procedure's G-DRG must be in the services list and operational manual.
        - Every ICD-10 code must be valid according to the ICD-10 list.
        - Every service must have a G-DRG code in the services list.
        - If a require field is missing or invalid, report it

        Output format: You MUST respond with a valid JSON object only (no extra text).
        {
            "valid": boolean,
            "error" : [
                "Description of error 1 with claim number",
                "Description of error 2 with claim number"
            ]
        }

        If the document has no errors, set "valid": true and "error":[].
        PROMPT;
        
        $user_prompt = "Validate this XML document:\n\n" . $xml_content;

        try {

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $system_prompt
                    ],
                    [
                        'role' => 'user',
                        'content' => $user_prompt
                    ],
                    [
                        'temperature' => 0.1,
                        'response_format' => 
                            [
                                'type' => 'json_object'
                            ],
                    ]
                ]
            ]);

            $result = json_decode($response->choices[0]->message->content, true);
            
            return [
                'valid' => $result['valid'] ?? false,  
                'errors' => $result['errors'] ?? ['unknown error from LLM'],
                'raw_response' => $response->choices[0]->message->content
            ];

        } catch (\Throwable $th) {
            Log::error('LLM Validation failed: ' . $th->getMessage());
            return [
                'valid' => false,
                'errors' => ['Failed to communicate with validation service'], 
            ];
            //throw $th;
        }
    }
    
}