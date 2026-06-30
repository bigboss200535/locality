<?php

if (!function_exists('get_store_name')) {
    function get_store_name()
    {
        if (!auth()->check() || !auth()->user()->store_id) {
            return 'N/A';
        }
        
        try {
            // Adjust namespace as needed
            $store = \App\Models\Store::where('store_id', auth()->user()->store_id)->first();
            return $store ? $store->store_name : 'N/A';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}