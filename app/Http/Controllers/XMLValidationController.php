<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\XMLValidationService;
use Illuminate\Http\JsonResponse;

class XMLValidationController extends Controller
{

    public function index()
    {
            return view('profile.edit');
    }
    
    public function upload()
    {
        $request->validate([
            'xml_file' => 'required|file|mimes:xml,txt,json'
        ]);

        $path = $request->file('xml_file')->getRealPath();

        ProcessXMLClaimsJob::dispatch($path);

        return response()->json([
            'message' => 'XML Queued successfully'
        ]);
    }

    public function parse(string $filePath): iterable
    {
        $reader = new XMLReader();
        $reader->open($filePath);

        while ($reader->read()) {
            if ($reader->nodeType === XMLReader::ELEMENT && $reader->name === 'Claim') {
                $claimNode = $reader->expand();
                // $claimData = $this->extractClaimData($claimNode);
                yield $simplexml_load_string($reader->readOuterXML()
                );
            }
        }
        $reader->close();
    }

    public function validate_xml(Request $request, XMLValidationService $validator ): JsonResponse
    {

        // // check and validate XML file
        // $response->validate([
        //     'xml_file' => 'required|file|mimes:xml'
        // ]);

        // $xml_content = $request->file('xml_file')->get();

        // //  check if XML is well-formed
        // $dom = new \DOMDocument();

        // if(!@$dom->loadXML($xml_content)){
        //         return response()->json(
        //             [
        //                 'valid' => false,
        //                 'errors' => [
        //                     'The uploaded file is not well formed XML document'
        //                     ],
        //             ], 422);
        //     }

        //    $result = $validator->valodate($xml_content);

        //    return response()->json($result);  
       
    }
}
