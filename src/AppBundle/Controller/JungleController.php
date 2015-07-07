<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Jungle Controller
 * 
 * @Route("/jungle")
 */
class JungleController extends Controller
{
    /**
     * @Route("/")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('jungle/index.html.twig');
    }
    
    /**
     * @Route("/preguntas-frecuentes")
     * @Method("GET")
     */
    public function faqAction()
    {
        return $this->render('jungle/faq.html.twig');
    }
    
    /**
     * @Route("/formas-de-pago")
     * @Method("GET")
     */
    public function paymentAction()
    {
        return $this->render('jungle/payment.html.twig');
    }
    
    /**
     * @Route("/contacto")
     * @Method("GET")
     */
    public function contactAction()
    {
        return $this->render('jungle/contact.html.twig');
    }
    
    /**
     * @Route("/privacidad")
     * @Method("GET")
     */
    public function privacyAction()
    {
        return $this->render('jungle/privacy.html.twig');
    }
    
    /**
     * @Route("/process")
     * @Method("GET")
     */
    public function processAction()
    {
        $subjectPrefix = 'Help Jungle -';
        $emailTo = 'equipo@menta.pe';
        $errors = array(); // array to hold validation errors
        $data = array(); // array to pass back data
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = stripslashes(trim($_POST['name']));
            $email = stripslashes(trim($_POST['email']));
            $subject = stripslashes(trim($_POST['subject']));
            $message = stripslashes(trim($_POST['message']));
            if (empty($name)) {
                $errors['name'] = 'Name is required.';
            }
            if (!preg_match('/^[^0-9][A-z0-9._%+-]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', $email)) {
                $errors['email'] = 'Email is invalid.';
            }
            if (empty($subject)) {
                $errors['subject'] = 'Subject is required.';
            }
            if (empty($message)) {
                $errors['message'] = 'Message is required.';
            }
        // if there are any errors in our errors array, return a success boolean or false
            if (!empty($errors)) {
                $data['success'] = false;
                $data['errors'] = $errors;
            } else {
                $subject = "$subjectPrefix $subject";
                $body = '
        <strong>Visitante: </strong>' . $name . '<br />
        <strong>Email: </strong>' . $email . '<br />
        <strong>Nos dejo este mensaje: </strong>' . nl2br($message) . '<br />
        ';
                $headers = 'MIME-Version: 1.1' . PHP_EOL;
                $headers .= 'Content-type: text/html; charset=UTF-8' . PHP_EOL;
                $headers .= "From: $name <$email>" . PHP_EOL;
                $headers .= "Return-Path: $emailTo" . PHP_EOL;
                $headers .= "Reply-To: $email" . PHP_EOL;
                $headers .= "X-Mailer: PHP/" . phpversion() . PHP_EOL;
                mail($emailTo, $subject, $body, $headers);
                $data['success'] = true;
                $data['message'] = 'Tu mensaje ha sido enviado exitosamente.';
            }
        // return all our data to an AJAX call
            echo json_encode($data);
        }
    }
}
