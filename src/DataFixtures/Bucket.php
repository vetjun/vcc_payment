<?php

namespace App\DataFixtures;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Bucket extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $this->insertVendors("Vendor1", ["size" => 30, "limit" => 200000.00, "interval_day" => 30], $manager);
        $this->insertVendors("Vendor2", ["size" => 30, "limit" => 50000.00, "interval_day" => 20], $manager);
        $this->insertVendors("Vendor3", ["size" => 30, "limit" => 100000.00, "interval_day" => 15], $manager);
        # $manager->flush();
    }

    public function insertVendors($vendor, $opt, ObjectManager $manager){
        $i = 0;
        $datetime = new DateTime();
        $initialDate = $datetime->createFromFormat('Y-m-d', '2020-01-01');
        $lastDate = clone $initialDate;
        $diff_day = (int)($opt["interval_day"] / 10);
        # var_dump($lastDate);
        while($i < $opt["size"]){
            $bucket = new \App\Entity\Bucket();
            $start_date = clone $lastDate;
            $end_date = $start_date->modify('+' . $opt["interval_day"] .' day');
            $bucket->setVendor($vendor);
            $bucket->setLimitVal($opt["limit"]);
            # var_dump($lastDate);
            $bucket->setStartDate($lastDate);
            $bucket->setEndDate($end_date);
            $manager->persist($bucket);
            $lastDate = clone $end_date;
            $lastDate = $lastDate->modify('+' . $diff_day .' day');
            $i++;
        }
        $manager->flush();
    }
}
