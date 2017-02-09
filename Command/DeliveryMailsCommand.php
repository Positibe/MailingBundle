<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MailingBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class DeliveryMailsCommand
 * @package Positibe\Bundle\MailingBundle\Command
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class DeliveryMailsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('positibe:mailing:delivery')
            ->setDescription('Envía los boletines listos para enviar');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $context = $this->getContainer()->get('router')->getContext();
            list($schema, $host) = explode('://',$this->getContainer()->getParameter('base_url'));
            $context->setHost($host);
            $context->setScheme($schema);
            $deliveries = $this->getContainer()->get('positibe_mailing.mail_delivery')->deliver();
            $output->writeln(sprintf('%s boletines enviados', count($deliveries)));
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }


    }

} 