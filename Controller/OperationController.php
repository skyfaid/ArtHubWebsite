<?php

namespace App\Controller;

use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Omnipay\Omnipay;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class OperationController extends AbstractController
{
    
     private $passerelle;

     private $manager;
     
     public function __construct(EntityManagerInterface $manager)
     {
        $this->passerelle=Omnipay::create('PayPal_Rest');

        $this->passerelle->setClientId($_ENV['PAYPAL_CLIENT_ID']);
        $this->passerelle->setSecret($_ENV['PAYPAL_SECRET_KEY']);
        $this->passerelle->setTestMode(true);

        $this->manager=$manager;

     }




    //Page d'accueil
    #[Route('/', name: 'app_cart')]
    public function cart(): Response
    {
        return $this->render('operation/cart.html.twig');
    }


    //Page d'accueil
    #[Route('/payment', name: 'app_payment')]
    public function payment(Request $request): Response
    {
       $token=$request->request->get('token');

       if(!$this->isCsrfTokenValid('myform',$token))
       {
            return new Response('Operation non autorisée', Response::HTTP_BAD_REQUEST,
            ['content-type'=>'text/plain']);
       }


       $response=$this->passerelle->purchase(array(
        'amount'=>$request->request->get('amount'),
        'currency'=>$_ENV['PAYPAL_CURRENCY'],
        'returnUrl'=>'https://127.0.0.1:8000/success',
        'cancelUrl'=>'https://127.0.0.1:8000/error'
      
        ))->send();
     
        try {
            if($response->isRedirect())
            {
                $response->redirect();
            }

            else
            {
                return $response->getMessage();
            }
        } catch (\Throwable $th) {
            
            return $th->getMessage();
        }
      
       
        return $this->render('operation/index.html.twig');
    }


    //Page de succès de la transaction
    #[Route('/success', name: 'app_success')]
    public function success(Request $request): Response
    {
       if($request->query->get('paymentId') && $request->query->get('PayerID'))
       {
         $operation=$this->passerelle->completePurchase(array(
            'payer_id'=>$request->query->get('PayerID'),
            'transactionReference'=>$request->query->get('paymentId')
         ));

         $response=$operation->send();

         if($response->isSuccessful())
         {
             $data=$response->getData();

              
             $payment = new Payment();

             $payment->setPaymentId($data['id'])
                     ->setPayerId($data['payer']['payer_info']['payer_id'])
                     ->setPayerEmail($data['payer']['payer_info']['email'])
                     ->setAmount($data['transactions'][0]['amount']['total'])
                     ->setCurrency($_ENV['PAYPAL_CURRENCY'])
                     ->setPurchasedAt(new \DateTime())
                     ->setPaymentStatus($data['state']);


            $this->manager->persist($payment);

            $this->manager->flush();
            
            return $this->render('Operation/success.html.twig',
              [
                'message'=>'Votre paiement a été un succès, voici l\'id de votre transaction:' .$data['id']
              ]
              );
         }

         else
         {
            return $this->render('Operation/success.html.twig',
              [
                'message'=>'Le paiement a échoué !'
              ]
              );
         }
         
       } 

       else
       {
        return $this->render('Operation/success.html.twig',
              [
                'message'=>'l\'utilisateur a annulé son paiement'
              ]
              );
       }
       
    }



    //Page d'error de la transaction
    #[Route('/error', name: 'app_error')]
    public function error(): Response
    {
        return $this->render('Operation/success.html.twig',
              [
                'message'=>'le paiement a échoué'
              ]
              );
    }
}