<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RandomNumber extends AbstractController
{
    /**
     * Just for fun...and 42
     * @Route("/random/number")
     */
    public function number()
    {
        $number = 0;
        try {
            $number = random_int(40, 44);
        } catch (Exception $e) {
        }

        return $this->render('random/number.html.twig', [
            'number' => $number,
        ]);
    }
}