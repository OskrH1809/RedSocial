<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\PublicationType;
use BackendBundle\Entity\Publication;
use Symfony\Component\HttpFoundation\Session\Session;

class PublicationController extends Controller {

    private $session;

    public function __construct() {
        $this->session = new Session();
    }

    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
//                 para subir la imagen
                $file = $form['image']->getData();

                if (!empty($file) && $file != null) {
                    $ext = $file->guessExtension();

                    if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                        $file_name = $user->getId() . time() . "." . $ext;
                        $file->move("uploads/publications/images", $file_name);
                        $publication->setImage($file_name);
                    } else {
                        $publication->setImage(null);
                    }
                } else {
                    $publication->setImage(null);
                }
                //        para subir el documento

                $doc = $form['document']->getData();

                if (!empty($doc) && $doc != null) {
                    $ext = $doc->guessExtension();
                    if ($ext == 'pdf') {
                        $file_name = $user->getId() . time() . "." . $ext;
                        $doc->move("uploads/publications/documents", $file_name);
                        $publication->setDocument($file_name);
                    } else {
                        $publication->setDocument(null);
                    }
                } else {
                    $publication->setDocument(null);
                }

                $publication->setUser($user);
                $publication->setCreatedAt(new \DateTime("now"));
                $em->persist($publication);
                $flush = $em->flush();
                if ($flush == null) {
                    $status = "la publicacion se ha creado correctamente";
                } else {
                    $estatus = 'error al almacenar en la base de datos';
                }
            } else {
                $status = "la publicacion no se ha creado, no es valido";
            }
            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('home_publications');
            ;
        }

        $publications = $this->getPublications($request);


        return $this->render('AppBundle:Publication:home.html.twig', array(
                    'form' => $form->createView(),
                    'pagination' => $publications
        ));
    }

    public function getPublications($request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $publications_repo = $em->getRepository('BackendBundle:Publication');
        $following_repo = $em->getRepository('BackendBundle:Following');

        $following = $following_repo->findBy(array('user' => $user));

        $following_array = array();

        foreach ($following as $follow) {
            $following_array[] = $follow->getFollowed();
        }

        $query = $publications_repo->createQueryBuilder('p')
                ->where('p.user = (:user_id) OR p.user IN (:following)')
                ->setParameter('user_id', $user->getId())
                ->setParameter('following', $following_array)
                ->orderBy('p.id', 'DESC')
                ->getQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1), 3
        );
        return $pagination;
    }

    public function removePublicationAction(Request $request, $id=null) {

        $em = $this->getDoctrine()->getManager();
        $publication_repo = $em->getRepository('BackendBundle:Publication');
        $publication = $publication_repo->find($id);

        $em->remove($publication);
        $flush = $em->flush();
        
        $user = $this->getUser();

        if($user->getId()== $publication->getUser()->getId()) {

            if ($flush == null) {
                $status = 'la publicacion se borro correctamente';
            } else {
                $status = 'No se pudo borrar la publicacion';
            }
        } else {
            $status = 'No se pudo borrar la publicacion';
        }

        return new Response($status);
    }

}

?>