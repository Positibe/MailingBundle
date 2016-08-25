<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MailingBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class TrackingController
 * @package Positibe\Bundle\MailingBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class TrackingController extends Controller
{
    /**
     * @param Request $request
     * @param $token
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function clickedAction(Request $request, $token)
    {
        $this->get('positibe_mailing.statistics_tracker')->clicked($token);

        return $this->render(
            '@PositibeMailing/Tracking/html_redirect.html.twig',
            array(
                'url' => $request->get('url'),
            )
        );
    }

    /**
     * @param Request $request
     * @param $token
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewedAction(Request $request, $token)
    {
        $this->get('positibe_mailing.statistics_tracker')->viewed($token);

        $response = new Response(file_get_contents( __DIR__ . '/../Resources/public/img/tracking_img.jpg'));

        return $response;

    }
} 