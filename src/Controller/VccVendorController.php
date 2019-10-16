<?php

namespace App\Controller;


use App\Entity\Bucket;
use App\Entity\VccVendor;
use App\Repository\BucketRepository;
use App\Repository\VccVendorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\VccVendors\VendorManagerFactory;

class VccVendorController extends AbstractController
{
    /**
     * @Route("/vcc/vendor", name="vcc_vendor_list", methods={"GET"})
     */
    public function index(Request $request, VccVendorRepository $vccVendorRepository)
    {
        $currency = $request->get('currency', null);
        $vendor = $request->get('vendor', null);
        $activation_date = $request->get('activation_date', null);
        $filters = ["currency" => $currency, "vendor" => $vendor, "activation_code" => $activation_date];
        $filters = array_filter($filters, function($v) { return !is_null($v); });
        $result = $vccVendorRepository->findByFilter($filters);
        $response = new Response();
        $serializer = $this->get('serializer');
        $response->setContent($serializer->serialize($result, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("/vcc/vendor", name="vcc_vendor_create", methods={"POST"})
     */
    public function create(Request $request, BucketRepository $bucketRepository, VendorManagerFactory $vendorManagerFactory){
        $response = new Response();
        $requestJson = json_decode($request->getContent(), true);
        $activationDate = (isset($requestJson['activationDate'])) ? $requestJson['activationDate'] : null;
        if(is_null($activationDate)){
            $response->setContent(json_encode([
                'success' => false,
                'message' => "There is no parameter with name 'activationDate' "
            ]));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        $bucket = $bucketRepository->findVendorByActivationDate($activationDate);
        if($bucket == null){
            $response->setContent(json_encode(["success" => false, "message" => "There is no matched Vendor Date"]));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        # var_dump($bucket);
        # exit();
        $vendorManager = $vendorManagerFactory->get($bucket);
        # $projectDir = $this->getParameter('vendor1');
        # $vendor = $vendorManager->get('Vendor1');
        $result = $vendorManager->process($requestJson, $bucket, $this->getDoctrine()->getManager());
        # $serializer = $this->get('serializer');
        $response->setContent(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("vcc/vendor/{id}", name="vcc_vendor_delete", methods={"DELETE"})
     */
    public function delete(Request $request, VccVendor $vcc_vendor): Response
    {
        $response = new Response();
        try{
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vcc_vendor);
            $entityManager->flush();
            $response->setContent(json_encode([
                'success' => true
            ]));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }catch (\Exception $e){

            $response->setContent(json_encode([
                'success' => false,
                'message' => "invalid parameters"
            ]));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }
}
