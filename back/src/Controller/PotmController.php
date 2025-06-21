<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Potm;
use Symfony\Component\HttpFoundation\Request;

final class PotmController extends AbstractController
{
    #[Route('/potm/ver_votos', name: 'app_potm')]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $votos = $em->getRepository(Potm::class)->findAll();
        $data = [];
        foreach($votos as $voto){
            $usuario = $voto->getUsuario();
            $data[] = [
                "id" => $voto->getId(),
                "voto" => $voto->getVoto(),
                "usuario" => $usuario
            ];
        }
        return $this->json([
           $data, 200
        ]);
    }
    #[Route('potm/votar', name:'app_votar', methods:["POST"])]
    public function votar(EntityManagerInterface $em, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $voto = new Potm();
        $voto->setVoto($data["voto"]);
        $voto->setUsuario($data["usuario"]);
        
        return $this->json(["message" => "Has votado correctamente", 200]);
    }
}
