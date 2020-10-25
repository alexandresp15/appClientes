<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Cliente;

/**
 * @Route("/clientes", name="cliente_")
 */
class ClienteController extends AbstractController {

    /**
     * @Route("/", name="index", methods={"GET"}) 
     */
    public function index(): Response {
        $clientes = $this->getDoctrine()->getRepository(Cliente::class)->findAll();

        return $this->json([
             'data' => $clientes
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show($id) {
        $cliente = $this->getDoctrine()->getRepository(Cliente::class)->find($id);

        return $this->json([
            'data' => $cliente
        ]);
    } 

    /**
     * @Route("/", name="create", methods={"POST"})
     */
    public function create(Request $request) {
        $data = $request->request->all(); 

        $cliente = new Cliente();
        $cliente->setNome($data['nome']);
        $cliente->setEmail($data['email']);
        $cliente->setSexo($data['sexo']);
        $cliente->setCpf($data['cpf']);

        $doctrine = $this->getDoctrine()->getManager();

        $doctrine->persist($cliente);
        $doctrine->flush();

        return $this->json([
            'data' => 'Cliente cadastrado com sucesso!'
        ]);
    } 

    /**
     * @Route("/{id}", name="update", methods={"PUT", "PATCH"})
     */
    public function update($id, Request $request) {
        $data = $request->request->all(); 

        $doctrine = $this->getDoctrine();
        $cliente = $doctrine->getRepository(Cliente::class)->find($id);

        if($request->request->has('nome'))
            $cliente->setNome($data['nome']);
        
        if($request->request->has('email'))
            $cliente->setEmail($data['email']);
        
        if($request->request->has('sexo'))
            $cliente->setSexo($data['sexo']);
        
        if($request->request->has('cpf'))
            $cliente->setCpf($data['cpf']);

        $doctrine = $doctrine->getManager();

        $doctrine->flush();

        return $this->json([
            'data' => 'Cliente atualizado com sucesso!'
        ]);

    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete($id) {

        $doctrine = $this->getDoctrine();
        $cliente = $doctrine->getRepository(Cliente::class)->find($id);

        $doctrine = $doctrine->getManager();
        $doctrine->remove($cliente);
        $doctrine->flush(); 

        return $this->json([
            'data' => 'Cliente removido com sucesso!'
        ]);
    } 
}