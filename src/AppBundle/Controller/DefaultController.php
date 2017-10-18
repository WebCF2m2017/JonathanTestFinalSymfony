<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query\ResultSetMapping;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need

        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('AppBundle:Article')->findAll();
        $sections = $em->getRepository('AppBundle:Section')->findAll();

        return $this->render('default/index.html.twig', ['articles' => $articles, 'sections' => $sections]);
    }


    /**
     * @Route("/section/{section_name}", name="show_article_section")
     */
    public function showArticleSection($section_name)
    {
        $em = $this->getDoctrine()->getManager();

        $section_exist = $em->getRepository('AppBundle:Section')->findOneBy(["thetitle" => $section_name]);
        if(!$section_exist)
            return $this->redirectToRoute('homepage');

        $id = $section_exist->getId();
        $articles = $em->getRepository('AppBundle:Article');
        $articles = $articles->createQueryBuilder('a')
            ->innerJoin('a.section', 'g')
            ->where('g.id = :idactu')
            ->setParameter('idactu', $id)
            ->getQuery()->getResult();

        $sections = $em->getRepository('AppBundle:Section')->findAll();


        return $this->render('default/show_section_article.html.twig', ['articles' => $articles, 'titre' => $section_exist, 'sections' => $sections]);

    }

    /**
     * @Route("/article/{id}", name="show_article")
     */

    public function showArticle($id)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('AppBundle:Article')->findOneBy(["id" => $id]);
        $sections = $em->getRepository('AppBundle:Section')->findAll();

        return $this->render('default/show_article.html.twig', ['article' => $article, 'sections' =>$sections]);
    }
}
