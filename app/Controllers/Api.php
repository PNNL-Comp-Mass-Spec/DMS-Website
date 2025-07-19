<?php

namespace App\Controllers;

class Api extends BaseController
{
    public function index()
    {
        // Return a basic note on API calls and what's supported
        \Config\Services::response()->setContentType("application/json");

        $apiCalls = [
            'GET api/[entity_type]' => 'List entities of type',
            'GET api/[entity_type]/[id]' => 'Show detail for entity matching [id]',
            'GET api/[entity_type]/new' => 'Get template for creating a new entity',
            'POST api/[entity_type]' => 'Create a new entity with JSON data in POST data',
            'GET api/[entity_type]/[id]/edit' => 'Get JSON data/fields with current data for editing',
            'PUT/PATCH/POST api/[entity_type]/[id]' => 'Update entity with JSON in data. Requires full set of JSON fields (excluding \'doc_...\' fields) from /edit',
            'DELETE api/[entity_type]/[id]' => 'Delete entity matching ID. Not supported for most entity types',
            'POST api/[entity_type]/[id]/delete' => 'Delete entity matching ID. Not supported for most entity types',
        ];

        $data = [
            'DMS API' => 'RESTful API for supported DMS entity types',
            'Supported APIs' => $apiCalls
        ];
        
        \Config\Services::response()->setContentType("application/json");
        echo json_encode($data);
    }
    
    public function error_report()
    {
        $data = [
            'error' => 'Unsupported REST call. Possible causes: incorrect HTTP method, URI type, entity not supported by REST API, or REST method not supported for the entity type.',
        ];
        return $this->response->setStatusCode(405)->setJSON($data);
    }
}
