<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illu1minate\Support\Facade\Storage;
use Spatie\PdfToText\Pdf;

class KnowledgeBaseService
{
    protected string $docPath = 'documents';
    protected int $cacheTtl = 3600; //1 hour

    public function get_knowledge_base(): string
    {
        return Cache::remember('knowledge_base', 
            $this->cacheTtl, function() {
                $files = Storage::disk('local')->files($this->docPath);
                $content = '';
                foreach ($files as $file){
                    $full_path = Storage::path($file);
                    $content .= $this->extractText($fullPath) . "\n\n"; 
                }
                return $content;
        });
    }


    protected function extract_text(string $path): string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if($extension === 'pdf'){
            return Pdf::getText($path);
         }
        //txt, md, etc
        return file_get_contents($path);
    }


    public function refresh_cache(): void
    {
        Cache::forget('knowledge_base');
        $this->get_knowledge_base();
    }

    
}