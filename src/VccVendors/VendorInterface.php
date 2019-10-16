<?php


namespace App\VccVendors;


interface VendorInterface
{
    public function process($requestJson, $bucket, $em);
}