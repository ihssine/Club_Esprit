<?php
namespace App\Controller;
use App\Repository\PlayerRepository;
use App\Entity\Player;
use App\Repository\StadeRepository;
use App\Form\PlayerType;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;


class PlayerController extends AbstractController
{
    #[Route('/player', name: 'app_player')]
    public function index(): Response
    {
        return $this->render('player/index.html.twig', [
            'controller_name' => 'PlayerController',
        ]);
    }
    #[Route('/fetch', name: 'fetch')]
    public function fetch(PlayerRepository $repo): Response
    {
        $result=$repo->findAll();
        return $this-> render('player/liste.html.twig', [
            'response'=>$result,
        ]);
    }
    #[Route('/add', name: 'add')]
    public function addF(StadeRepository $repo , ManagerRegistry $mr , Request $req): Response
    {
        $P=new Player();
        $form=$this->createForm(PlayerType::class,$P);
        
        $form->handleRequest($req);
        if($form->isSubmitted()){
        $em=$mr->getManager();
        $em->persist($P);
        $em->flush();
        return $this->redirectToRoute('fetch');
        }
        return $this->render('player/add.html.twig',['f'=>$form->createView()]);
    }
    #[Route('/remove/{id}', name: 'remove')]
    public function remove(PlayerRepository $repo , $id ,ManagerRegistry $mr): Response
    {
        $player=$repo->find($id);
        $em=$mr->getManager();
        $em->remove($player);
        $em->flush();
        return new Response ('removed');
    }
    #[Route('/update/{id}', name: 'update')]
    public function update($id, PlayerRepository $repo, ManagerRegistry $mr, Request $req): Response
    {
        $player = $repo->find($id);
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($req);

    if ($form->isSubmitted()) {
        $em = $mr->getManager();
        $em->flush();
        return $this->redirectToRoute('fetch');
    }

    return $this->render('player/update.html.twig', ['f' => $form->createView()]);
    }

}
