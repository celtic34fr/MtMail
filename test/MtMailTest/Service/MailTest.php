<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Service;

use MtMail\Service\Composer;
use MtMail\Service\Mail;
use MtMail\Service\Sender;
use MtMail\Service\TemplateManager;
use MtMail\Template\SimpleHtml;
use MtMail\Template\TemplateInterface;
use Zend\Mail\Message;
use Zend\View\Model\ModelInterface;

class MailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mail
     */
    protected $service;

    public function testSendProxiesToSender()
    {
        $message = new Message();
        $sender = $this->getMock(Sender::class, ['send'], [], '', false);
        $sender->expects($this->once())->method('send')->with($message);
        $composer = $this->getMock(Composer::class, [], [], '', false);
        $templateManager = $this->getMock(TemplateManager::class, [], [], '', false);
        $service = new Mail($composer, $sender, $templateManager);
        $service->send($message);
    }

    public function testComposeProxiesToComposer()
    {
        $sender = $this->getMock(Sender::class, [], [], '', false);
        $template = $this->getMock(TemplateInterface::class);
        $composer = $this->getMock(Composer::class, ['compose'], [], '', false);
        $composer->expects($this->once())->method('compose')
            ->with(
                ['to' => 'johndoe@domain.com'],
                $template,
                $this->isInstanceOf(ModelInterface::class)
            );
        $templateManager = $this->getMock(TemplateManager::class, [], [], '', false);
        $service = new Mail($composer, $sender, $templateManager);
        $service->compose(['to' => 'johndoe@domain.com'], $template);
    }

    public function testComposeTriesPullsTemplateFromManager()
    {
        $sender = $this->getMock(Sender::class, [], [], '', false);
        $composer = $this->getMock(Composer::class, [], [], '', false);
        $templateManager = $this->getMock(TemplateManager::class, ['has', 'get'], [], '', false);
        $templateManager->expects($this->once())->method('has')
            ->with('FooTemplate')->will($this->returnValue(true));
        $templateManager->expects($this->once())->method('get')
            ->with('FooTemplate')->will($this->returnValue($this->getMock(TemplateInterface::class)));
        $service = new Mail($composer, $sender, $templateManager);
        $service->compose(['to' => 'johndoe@domain.com'], 'FooTemplate');
    }

    public function testComposeFallsBackToDefaultHtmlTemplate()
    {
        $sender = $this->getMock(Sender::class, [], [], '', false);
        $composer = $this->getMock(Composer::class, ['compose'], [], '', false);
        $composer->expects($this->once())->method('compose')
            ->with(
                ['to' => 'johndoe@domain.com'],
                $this->isInstanceOf(SimpleHtml::class),
                $this->isInstanceOf(ModelInterface::class)
            );
        $templateManager = $this->getMock(TemplateManager::class, ['has'], [], '', false);
        $templateManager->expects($this->once())->method('has')
            ->with('FooTemplate')->will($this->returnValue(false));
        $service = new Mail($composer, $sender, $templateManager);
        $service->compose(['to' => 'johndoe@domain.com'], 'FooTemplate', []);
    }
}
