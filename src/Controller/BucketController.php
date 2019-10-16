<?php

namespace App\Controller;

use App\Entity\Bucket;
use App\Form\BucketType;
use App\Repository\BucketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/bucket")
 */
class BucketController extends AbstractController
{
    /**
     * @Route("/", name="bucket_index", methods={"GET"})
     */
    public function index(BucketRepository $bucketRepository): Response
    {
        $response = new Response();
        $serializer = $this->get('serializer');
        $response->setContent($serializer->serialize($bucketRepository->findAll(), 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/new", name="bucket_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $bucket = new Bucket();
        $form = $this->createForm(BucketType::class, $bucket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bucket);
            $entityManager->flush();

            return $this->redirectToRoute('bucket_index');
        }

        $response = new Response();
        $response->setContent(json_encode([
            'success' => true,
            'bucket' => $bucket
        ]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/{id}", name="bucket_show", methods={"GET"})
     */
    public function show(Bucket $bucket): Response
    {
        $response = new Response();
        $serializer = $this->get('serializer');
        $response->setContent($serializer->serialize($bucket, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/{id}/edit", name="bucket_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Bucket $bucket): Response
    {
        $form = $this->createForm(BucketType::class, $bucket);
        $form->handleRequest($request);
        $response = new Response();
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();


            $serializer = $this->get('serializer');
            $response->setContent($serializer->serialize($bucket, 'json'));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $serializer = $this->get('serializer');
        $response->setContent($serializer->serialize($bucket, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/{id}", name="bucket_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Bucket $bucket): Response
    {
        $response = new Response();
        try{
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bucket);
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
