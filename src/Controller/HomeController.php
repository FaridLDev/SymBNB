<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {
    /**
     * @Route("/" , name="homepage")
     */
    public function home(){

        $hello = 'Hello';
        return $this->render('home.html.twig',[
            'hello' => $hello
        ]) ;
    }
}
