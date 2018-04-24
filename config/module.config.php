<?php

/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek 2017-2018 Gilbert ARMENGAUD
 * @license   BSD 2-Clause
 */

use MtMail\ComposerPlugin\DefaultHeaders;
use MtMail\ComposerPlugin\Layout;
use MtMail\ComposerPlugin\MessageEncoding;
use MtMail\ComposerPlugin\PlaintextMessage;
use MtMail\ComposerPlugin\EmbeddingImages;
use MtMail\Controller\Plugin\MtMail;
use MtMail\Factory\DefaultHeadersPluginFactory;
use MtMail\Factory\LayoutPluginFactory;
use MtMail\Factory\MessageEncodingPluginFactory;
use MtMail\Renderer\ZendView;
use Zend\Mail\Transport\Sendmail;
use Zend\ServiceManager\Factory\InvokableFactory;

use MtMail\Service\Mail;
use MtMail\Factory\ZendViewRendererFactory;
use MtMail\Factory\ComposerServiceFactory;
use MtMail\Factory\SenderServiceFactory;
use MtMail\Factory\MailServiceFactory;
use MtMail\Factory\ComposerPluginManagerFactory;
use MtMail\Factory\SenderPluginManagerFactory;
use MtMail\Factory\TemplateManagerFactory;
use MtMail\Factory\SmtpTransportFactory;
use MtMail\Factory\FileTransportFactory;
use MtMail\Factory\MtMailPlugin;
use MtMail\Service\Composer;
use MtMail\Service\Sender;
use MtMail\Service\ComposerPluginManager;
use MtMail\Service\SenderPluginManager;
use MtMail\Service\TemplateManager;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\File;

return [
    'mt_mail' => [
        'renderer' => ZendView::class,
        'composer_plugin_manager' => [
            'aliases' => [
                'PlaintextMessage' => PlaintextMessage::class,
                'plaintextMessage' => PlaintextMessage::class,
                'plaintextmessage' => PlaintextMessage::class,
                'Layout'           => Layout::class,
                'layout'           => Layout::class,
                'DefaultHeaders'   => DefaultHeaders::class,
                'defaultHeaders'   => DefaultHeaders::class,
                'defaultheaders'   => DefaultHeaders::class,
                'MessageEncoding'  => MessageEncoding::class,
                'messageEncoding'  => MessageEncoding::class,
                'messageencoding'  => MessageEncoding::class,
                'embeddingimages'  => EmbeddingImages::class,
                'embeddingImages'  => EmbeddingImages::class,
                'EmbeddingImages'  => EmbeddingImages::class,
            ],
            'factories' => [
                PlaintextMessage::class => InvokableFactory::class,
                Layout::class           => LayoutPluginFactory::class,
                DefaultHeaders::class   => DefaultHeadersPluginFactory::class,
                MessageEncoding::class  => MessageEncodingPluginFactory::class,
                EmbeddingImages::class  => InvokableFactory::class,
            ],
        ],
        'composer_plugins' => [
            'DefaultHeaders'
        ],
        'default_headers' => [],
        'transport' => Sendmail::class,
    ],
    'service_manager' => [
        'invokables' => [
            Sendmail::class => Sendmail::class,
        ],
        'aliases' => [
            'mtmail.services'       => Mail::class
        ],
        'factories' => [
            ZendView::class              => ZendViewRendererFactory::class,
            Composer::class              => ComposerServiceFactory::class,
            Sender::class                => SenderServiceFactory::class,
            Mail::class                  => MailServiceFactory::class,
            ComposerPluginManager::class => ComposerPluginManagerFactory::class,
            SenderPluginManager::class   => SenderPluginManagerFactory::class,
            TemplateManager::class       => TemplateManagerFactory::class,
            Smtp::class                  => SmtpTransportFactory::class,
            File::class                  => FileTransportFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            MtMail::class => MtMailPlugin::class,
        ],
        'aliases' => [
            'mtMail'      => MtMail::class,
        ]
    ],
];
