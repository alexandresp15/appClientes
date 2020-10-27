<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Form\ClienteType;
use App\Repository\ClienteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class ClienteController extends AbstractController
{
    /**
     * @Route("/", name="cliente_listar", methods={"GET"})
     */
    public function listar(ClienteRepository $clienteRepository): Response
    {
        return $this->render('cliente/listar.html.twig', [
            'clientes' => $clienteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/criar", name="cliente_criar", methods={"GET","POST"})
     */
    public function criar(Request $request): Response
    {
        $cliente = new Cliente();
        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cliente);
            $entityManager->flush();

            return $this->redirectToRoute('cliente_listar');
        }

        return $this->render('cliente/criar.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cliente_mostrar", methods={"GET"})
     */
    public function mostrar(Cliente $cliente): Response
    {
        return $this->render('cliente/mostrar.html.twig', [
            'cliente' => $cliente,
        ]);
    }

    /**
     * @Route("/{id}/editar", name="cliente_editar", methods={"GET","POST"})
     */
    public function editar(Request $request, Cliente $cliente): Response
    {
        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cliente_listar');
        }

        return $this->render('cliente/editar.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cliente_deletar", methods={"DELETE"})
     */
    public function deletar(Request $request, Cliente $cliente): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cliente->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cliente);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cliente_listar');
    }
}
