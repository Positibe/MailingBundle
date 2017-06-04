<?php
/**
 * This file was generated by PcabreusGeneratorBundle
 */

namespace Positibe\Bundle\MailingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class MailFormType
 * @package Positibe\Bundle\MailingBundle\Form\Type
 */
class MailFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'subject',
            null,
            array(
                'label' => 'form.positibe_mail.subject_label',
            )
        )->add(
            'body',
            null,
            array(
                'label' => 'form.positibe_mail.body_label',
            )
        )->add(
            'fromName',
            null,
            array(
                'label' => 'form.positibe_mail.fromName_label',
            )
        )->add(
            'responseTo',
            null,
            array(
                'label' => 'form.positibe_mail.responseTo_label',
            )
        )->add(
            'code',
            null,
            array(
                'label' => 'form.positibe_mail.code_label',
            )
        )->add(
            'createdAt',
            null,
            array(
                'label' => 'form.positibe_mail.createdAt_label',
            )
        )->add(
            'sendAt',
            null,
            array(
                'label' => 'form.positibe_mail.sendAt_label',
            )
        )->add(
            'sentAt',
            null,
            array(
                'label' => 'form.positibe_mail.sentAt_label',
            )
        )->add(
            'state',
            null,
            array(
                'label' => 'form.positibe_mail.state_label',
            )
        )->add(
            'receivers',
            null,
            array(
                'label' => 'form.positibe_mail.receivers_label',
            )
        )->add(
            'sentCount',
            null,
            array(
                'label' => 'form.positibe_mail.sentCount_label',
            )
        )->add(
            'failuresCount',
            null,
            array(
                'label' => 'form.positibe_mail.failuresCount_label',
            )
        )->add(
            'receivedCount',
            null,
            array(
                'label' => 'form.positibe_mail.receivedCount_label',
            )
        )->add(
            'openedCount',
            null,
            array(
                'label' => 'form.positibe_mail.openedCount_label',
            )
        )->add(
            'repliedCount',
            null,
            array(
                'label' => 'form.positibe_mail.repliedCount_label',
            )
        )->add(
            'clicksCount',
            null,
            array(
                'label' => 'form.positibe_mail.clicksCount_label',
            )
        )->add(
            'bouncedCount',
            null,
            array(
                'label' => 'form.positibe_mail.bouncedCount_label',
            )
        )->add(
            'tracking',
            null,
            array(
                'label' => 'form.positibe_mail.tracking_label',
            )
        )->add(
            'failures',
            null,
            array(
                'label' => 'form.positibe_mail.failures_label',
            )
        )->add(
            'token',
            null,
            array(
                'label' => 'form.positibe_mail.token_label',
            )
        )->add(
            'variables',
            null,
            array(
                'label' => 'form.positibe_mail.variables_label',
            )
        )->add(
            'swiftMailerMessage',
            null,
            array(
                'label' => 'form.positibe_mail.swiftMailerMessage_label',
            )
        )->add(
            'subjectHtml',
            null,
            array(
                'label' => 'form.positibe_mail.subjectHtml_label',
            )
        )->add(
            'bodyHtml',
            null,
            array(
                'label' => 'form.positibe_mail.bodyHtml_label',
            )
        )->add(
            'messageError',
            null,
            array(
                'label' => 'form.positibe_mail.messageError_label',
            )
        )->add(
            'attachments',
            null,
            array(
                'label' => 'form.positibe_mail.attachments_label',
            )
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\MailingBundle\Entity\Mail',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'positibe_mail';
    }
}
