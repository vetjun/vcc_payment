<?php


namespace App\VccVendors;


use App\Controller\CurrencyController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Bucket;
use App\Entity\VccVendor;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Vendor2 implements VendorInterface
{

    protected $bucket;
    protected $request_json;
    protected $currency;
    protected $em;
    protected $url = "http://www.mocky.io/v2/5da5dc72340000d325632d4d";
    /**
     * Vendor2 constructor.
     */
    public function __construct()
    {
    }

    public function process($requestJson, $bucket, $em)
    {
        $this->bucket = $bucket;
        $this->em = $em;
        $this->request_json = $requestJson;
        $this->currency = new CurrencyController();
        $api_result = $this->api();
        if($api_result !== null){
            $store_result = $this->store($api_result);
            return $store_result;
        }
        return ["success" => false, "vcc_vendor" => null, "message" => "Vendor api request is failed"];
    }
    private function api(){
        $client = new Client();
        try {
            $res = $client->request('GET', $this->url, [
            ]);
        } catch (GuzzleException $e) {
        }
        if($res->getStatusCode() == 200){
            $result = $res->getBody();
            $result_obj = json_decode($result);
            return $result_obj;
        }
        return null;
    }
    private function store($api_result){
        $vcc_vendor = new VccVendor();
        $vcc_vendor->setProcessId($this->request_json['processId']);
        $vcc_vendor->setActivationDate(\DateTime::createFromFormat('Y-m-d', $this->request_json['activationDate']));
        $vcc_vendor->setExpireDate(\DateTime::createFromFormat('Y-m-d', $this->request_json['expireDate']));
        $vcc_vendor->setBalance($this->request_json['balance']);
        $vcc_vendor->setCurrency($this->request_json['currency']);
        $vcc_vendor->setCardNumber($api_result->cardNumber);
        $vcc_vendor->setReference($api_result->referenceCode);
        $vcc_vendor->setCvc($api_result->cvv);
        $vcc_vendor->setVendor($this->bucket->getVendor());
        $vcc_vendor->setNotes($this->request_json['notes']);

        $converted_balance = $this->currency->convert($this->request_json['currency'], $this->request_json['balance']);
        if($this->bucket->getLimitVal() >= $converted_balance){
            $limit_val = $this->bucket->getLimitVal();
            $limit_val -= $converted_balance;
            $this->bucket->setLimitVal($limit_val);
            $this->em->persist($this->bucket);
            $this->em->persist($vcc_vendor);
            $this->em->flush();
            return ["success" => true, "vcc_vendor" => $vcc_vendor->getId()];
        }else{
            return ["success" => false, "vcc_vendor" => null];
        }
    }
}