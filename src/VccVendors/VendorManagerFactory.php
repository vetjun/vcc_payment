<?php


namespace App\VccVendors;


use App\Entity\Bucket;
use Doctrine\ORM\EntityManagerInterface;

class VendorManagerFactory
{
    public function __construct()
    {
    }

    public function get($bucket){
        #var_dump($bucket);
        #exit();
        switch ($bucket->getVendor()){
            case 'Vendor1': return new Vendor1();
            case 'Vendor2': return new Vendor2();
            case 'Vendor3': return new Vendor3();
            default : die("There is no matched Vendor");
        }
    }
}