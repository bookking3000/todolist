<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RandomNumber extends AbstractController
{
    /**
     * @Route("/random/number")
     */
    public function number()
    {
        try {
            $number = random_int(0, 100);
        } catch (\Exception $e) {
        }

        return $this->render('random/number.html.twig', [
            'number' => $number,
        ]);
    }
}