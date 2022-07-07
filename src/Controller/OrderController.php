<?php

namespace App\Controller;
 

use App\Entity\Order;
use App\Classe\Cart;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;
use Symfony\Component\Mime\Encoder\ContentEncoderInterface;

class OrderController extends AbstractController
{   
    private $entityManager;

    public function __construct(EntityManagerInterface  $entityManager)
    {
        $this->entityManager=$entityManager;
    }

    #[Route('/commande', name: 'order')]

    public function index(Cart $cart, Request $request ): Response
    {    
 // la condition qui pemet de rediriger l'utilisateur acréée une nouvelle adresse si il n'a pas enregistre une 
        
        if (!$this->getUser()->getAddresses()->getValues()){

           return $this->redirectToRoute('account_address_add');
        }
         $form =$this->createForm(OrderType::class, null, [
            'user'=>$this->getUser()  //il va afficher que les adresse de l'utilisateur connecte 
         ]);
        
        return $this->render('order/index.html.twig',[
            'form'=>$form->createView(),
            'cart'=>$cart->getFull()
        ]);
    }
    #[Route ("/commande/recapitulatif", name: "order_recap",methods:"POST")]

    public function add(Cart $cart, Request $request): Response

    {    
   
        $form =$this->createForm(OrderType::class, null, [
         'user'=>$this->getUser()  //il va afficher que les adresse de l'utilisateur connecte 
        ]);

        $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid()){
            
            $date = new DateTimeImmutable();
            $carriers = $form->get('Carriers')->getData();
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname().''.$delivery->getLastname();
            $delivery_content .= '<br>'.$delivery->getPhone();
            if($delivery->getCompany()) 
            {
            $delivery_content .= '<br>'.$delivery->getCompany();
            }
            $delivery_content .= '<br>'.$delivery->getAddress();
            $delivery_content .= '<br>'.$delivery->getPostal().''.$delivery->getCity();
            $delivery_content .= '<br>'.$delivery->getCountry();
            

            //enregistrer la commande (order)
            $order= new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            $this->entityManager->persist( $order);
            //enregistrer les produits (orderdetails)

            $orderDetails= new OrderDetails();

            foreach  ( $cart->getFull() as $product ){

                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice()*$product['quantity']); 
                $this->entityManager->persist( $orderDetails);
            }

        $this->entityManager->flush();
          }

        return $this->render('order/add.html.twig',[
            'cart'=>$cart->getFull(),
            'carrier' =>  $carriers,
            'delivery'=> $delivery_content
        ]);
        return $this->redirectToRoute('cart');
    }

   
}



