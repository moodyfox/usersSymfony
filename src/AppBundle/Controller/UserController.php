<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17/11/15
 * Time: 19:23
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\userService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UserController extends Controller
{

    /**
     * @Route("/user", name="user")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();
        return $this->render(
            ':default:index2.html.twig',
            [
                'users' => $users,
            ]
        );
    }

    /**
     * @Route("/add", name="app_user_add")
     */
    public function addAction(Request $request)
    {
        return $this->render('user/addUser.html.twig');
    }

    /**
     * @Route("/do-add", name="app_user_doAdd")
     */
    public function doAddAction(Request $request)
    {
        $user = $this->get('app.entity.user');
        $usu = $request->request->get('user');
        $pass = $request->request->get('pass');
        $mail = $request->request->get('mail');
        if ($usu != null && $pass != null && $mail != null){
            $user->setUsername($usu);
            $user->setPassword($pass);
            $user->setEmail($mail);
            $user->setCreatedAt(new \Datetime("now"));
            $user->setUpdatedAt(new \Datetime("now"));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->render(
                ':user:do-addUser.html.twig',
                [
                    'user' => $usu,
                    'title' => 'Usuario añadido',
                ]
            );
        }
    }

    /**
     * @Route("/modify", name="app_user_modify")
     * @Method("GET")
     *
     */

    public function modifyAction()
    {
        return $this->render('user/modifyUser.html.twig');
    }

    /**
     * @Route("/do-modify", name="app_user_doModify")
     */

    public function doModifyAction(Request $request)
    {
        $id = $request->get('id');
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $em = $this->getDoctrine()->getManager();
        $user = $repository->find($id);

        if (!$id){
            throw $this->createNotFoundException('Usuario con este nombre no encontrado');
        }
        $usu = $request->request->get('user');
        $pass = $request->request->get('pass');
        $mail = $request->request->get('mail');

        if ($usu == null){
            $usu = $user->getUsername();
        }

        if ($pass == null){
            $pass = $user->getPassword();
        }

        if ($mail == null){
            $mail = $user->getEmail();
        }
        $user->setUsername($usu);
        $user->setPassword($pass);
        $user->setEmail($mail);
        $user->setUpdatedAt(new \Datetime("now"));
        $em->flush();

        return $this->render(':user:do-modifyUser.html.twig');
    }

    /**
     * @Route("/delete", name="app_user_delete")
     */
    public function deleteAction(Request $request)
    {
        return $this->render('user/deleteUser.html.twig');
    }

    /**
     * @Route("/do-delete", name="app_user_doDelete")
     */
    public function doDeleteAction()
    {
        $id = $_GET['id'];
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $em = $this->getDoctrine()->getManager();
        $user = $repository->find($id);
        if (!$user){
            throw $this->createNotFoundException('Usuario con este nombre no encontrado');
        }
        $em->remove($user);
        $em->flush();

        return $this->render(
            ':user:do-deleteUser.html.twig',
            [
                'title' => 'Usuario eliminado',
            ]
        );
    }
}
