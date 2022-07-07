<?php
     
 namespace App\Classe;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Product;



    class Cart
    {
        private $session;        
        private $entityManager;
        
        public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
        {
            $this->requestStack = $requestStack;
            $this->entityManager = $entityManager;
        }
     
        public function add($id)
        {
            $cart=$this->requestStack->getSession()->get('cart',[]);
     
            if(!empty($cart[$id])){
                $cart[$id]++;
            } else {
                $cart[$id] = 1;
            }
     
     
            $this->requestStack->getSession()->set('cart', $cart);
        }


        public function get()
        {
            return $this->requestStack->getSession()->get('cart');
        }
     

        public function remove(){
     
            return $this->requestStack->getSession()->remove('cart');
        }
        
        
        public  function delete($id){

            $cart = $this->requestStack->getSession()->get('cart');

            unset($cart[$id]);

            return $this->requestStack->getSession()->set('cart', $cart);
        }

        public function decrease($id)
        {
            $cart = $this->requestStack->getSession()->get('cart');

            if ($cart[$id] > 1){
                $cart[$id]--; 
            }else {
                unset($cart[$id]);
            }

            return $this->requestStack->getSession()->set('cart', $cart);
        }
        
        public function getFull()
        {  
            $cartComplete = [];

            if ($this->get()) {
                foreach ($this->get() as $id => $quantity) {
                    $product_object = $this->entityManager->getRepository(Product::class)->findOneByid($id);

                    if (!$product_object){
                        $this->delete($id);
                        continue;
                    }

                    $cartComplete[] = [
                        'product' => $product_object,
                        'quantity' => $quantity
                    ];
                }
            }
            return $cartComplete;
        }
       
}
