<?php


namespace App\Controller;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CurrencyController extends AbstractController
{
    protected $url = "http://www.mocky.io/v2/5da5e0e3340000dd1a632d70";
    protected $currency;

    public function __construct()
    {
        $this->fetch();
    }

    public function fetch(){
        $client = new Client();
        try {
            $res = $client->request('GET', $this->url, [
            ]);
        } catch (GuzzleException $e) {
        }
        if($res->getStatusCode() == 200){
            $result = $res->getBody();
            $result_obj = json_decode($result);
            $eur_usd = $result_obj->EURUSD;
            $eur_try = $result_obj->EURTRY;
            $returnObj = [];
            $returnObj['TRY'] = floatval(1 / $eur_usd) * $eur_try;
            $returnObj['EUR'] = floatval(1 / $eur_usd);
            $this->currency = $returnObj;
            return $returnObj;
        }
        return null;
    }
    public function convert($from, $amount, $to='USD'){
        if(in_array($from, array_keys($this->currency))){
            return (1 / $this->currency[$from]) * $amount;
        }elseif ($from == 'USD'){
            return $amount;
        }else{
            die("There is no matched currency variation");
        }
    }
}