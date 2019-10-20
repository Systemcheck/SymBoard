<?php

namespace App\Controller;

use App\Repository\ReportRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/members", name="page.members")
     * @param UserRepository $repo
     * @return Response
     */
    public function members(UserRepository $repo): Response
    {
        $members = $repo->findAll();

        return $this->render('pages/members.html.twig', [
            'members' => $members
        ]);
    }

    /**
     * @Route("/team", name="page.team")
     * @param UserRepository $repo
     * @return Response
     */
    public function team(UserRepository $repo): Response
    {
        $administrators = $repo->findByRole('ROLE_ADMIN');
        $moderators = $repo->findByRole('ROLE_MODERATOR');

        return $this->render('pages/team.html.twig', [
            'administrators' => $administrators,
            'moderators' => $moderators
        ]);
    }

    /**
     * @Route("/panel", name="page.panel")
     * @IsGranted("ROLE_MODERATOR")
     * @param ReportRepository $repo
     * @return Response
     */
    public function panel(ReportRepository $repo): Response
    {
        $nbUntreatedReports = $repo->countUntreatedReports();
        return $this->render('pages/panel.html.twig', [
            'nbUntreatedReports' => $nbUntreatedReports
        ]);
    }
}