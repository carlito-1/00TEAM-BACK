<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


final class UsuarioController extends AbstractController
{
    #[Route('/usuarios/ver', name: 'app_usuario')]
    public function index(EntityManagerInterface $em): JsonResponse
    {   
        $usuarios = $em->getRepository(Usuario::class)->findAll();
        $data = [];
        foreach($usuarios as $user){
            $data[] = [
                "id" => $user->getId(),
                "nombre_usuario" => $user->getNombreUsuario()
            ];
        }

        return $this->json([
            $data, 200
        ]);
    }

    #[Route('/usuarios/crear', name: 'app_crear', methods:["POST"])]
    public function crear(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {   
        $data = json_decode($request->getContent(), true);
       
        $usuario = new Usuario();
        $usuario->setNombreUsuario($data['nombre']);
        $hashedPassword = $passwordHasher->hashPassword($usuario, $data['contrasena']);
        $usuario->setPassword($hashedPassword); 
        $em->persist($usuario);
        $em->flush();
        

        return $this->json([
            $data, 200
        ]);
    }
    #[Route('/comprobar_usuario', name : "_comprobar", methods:["POST"])]
    public function comprobar(EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher, Request $request): JsonResponse{
        $data = json_decode($request->getContent(), true);
        $usuario = $entityManager->getRepository(Usuario::class)->findOneBy(['nombre' => $data["nombre"]]);
        if(!$usuario){
            return $this->json(['error'=>'Contraseña o email incorrecto'],400); 
        }
        $pass = $passwordHasher->isPasswordValid($usuario, $data["contrasena"]);
        if($pass){
            return $this->json(['message'=>'Inicio de sesión con exito'],200); 
        }else{
            return $this->json(['error'=>'Contraseña o email incorrecto'],400); 
        }
    }


   
}
