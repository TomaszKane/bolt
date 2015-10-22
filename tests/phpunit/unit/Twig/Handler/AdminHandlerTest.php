<?php

namespace Bolt\Tests\Twig;

use Bolt\Tests\BoltUnitTest;
use Bolt\Twig\Handler\AdminHandler;

/**
 * Class to test Bolt\Twig\Handler\AdminHandler
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class AdminHandlerTest extends BoltUnitTest
{
    public function testAddDataEmpty()
    {
        $app = $this->getApp();
        $handler = new AdminHandler($app);

        $handler->addData('', '');
        $this->assertEmpty($app['jsdata']);
    }

    public function testAddDataValid()
    {
        $app = $this->getApp();
        $handler = new AdminHandler($app);

        $handler->addData('drop.bear', 'Johno');
        $this->assertArrayHasKey('drop', $app['jsdata']);
        $this->assertArrayHasKey('bear', $app['jsdata']['drop']);
        $this->assertSame('Johno', $app['jsdata']['drop']['bear']);
    }

    public function testIsChangelogEnabled()
    {
        $app = $this->getApp();
        $handler = new AdminHandler($app);

        $app['config']->set('general/changelog/enabled', false);
        $result = $handler->isChangelogEnabled();
        $this->assertFalse($result);

        $app['config']->set('general/changelog/enabled', true);
        $result = $handler->isChangelogEnabled();
        $this->assertTrue($result);
    }

    public function testStackedIsOnStack()
    {
        $app = $this->getApp();
        $stack = $this->getMock('Bolt\Stack', ['isOnStack', 'isStackable'], [$app]);
        $stack
            ->expects($this->atLeastOnce())
            ->method('isOnStack')
            ->will($this->returnValue(true))
        ;
        $stack
            ->expects($this->any())
            ->method('isStackable')
            ->will($this->returnValue(false))
        ;
        $app['stack'] = $stack;

        $handler = new AdminHandler($app);

        $result = $handler->stacked('koala.jpg');
        $this->assertTrue($result);
    }

    public function testStackedIsOnStackable()
    {
        $app = $this->getApp();
        $stack = $this->getMock('Bolt\Stack', ['isOnStack', 'isStackable'], [$app]);
        $stack
            ->expects($this->atLeastOnce())
            ->method('isOnStack')
            ->will($this->returnValue(false))
        ;
        $stack
            ->expects($this->atLeastOnce())
            ->method('isStackable')
            ->will($this->returnValue(false))
        ;
        $app['stack'] = $stack;

        $handler = new AdminHandler($app);

        $result = $handler->stacked('koala.jpg');
        $this->assertTrue($result);
    }

    public function testStackedNotIsOnStack()
    {
        $app = $this->getApp();
        $stack = $this->getMock('Bolt\Stack', ['isOnStack', 'isStackable'], [$app]);
        $stack
            ->expects($this->atLeastOnce())
            ->method('isOnStack')
            ->will($this->returnValue(false))
        ;
        $stack
            ->expects($this->any())
            ->method('isStackable')
            ->will($this->returnValue(true))
        ;
        $app['stack'] = $stack;

        $handler = new AdminHandler($app);

        $result = $handler->stacked('koala.jpg');
        $this->assertFalse($result);
    }

    public function testStackedNotAnything()
    {
        $app = $this->getApp();
        $stack = $this->getMock('Bolt\Stack', ['isOnStack', 'isStackable'], [$app]);
        $stack
            ->expects($this->atLeastOnce())
            ->method('isOnStack')
            ->will($this->returnValue(false))
        ;
        $stack
            ->expects($this->atLeastOnce())
            ->method('isStackable')
            ->will($this->returnValue(false))
        ;
        $app['stack'] = $stack;

        $handler = new AdminHandler($app);

        $result = $handler->stacked('koala.jpg');
        $this->assertTrue($result);
    }

    public function testStackItems()
    {
        $app = $this->getApp();

        $stack = $this->getMock('Bolt\Stack', ['listitems'], [$app]);
        $stack
            ->expects($this->atLeastOnce())
            ->method('listitems')
            ->will($this->returnValue(['koala.jpg', 'clippy.png']))
        ;
        $app['stack'] = $stack;

        $handler = new AdminHandler($app);

        $result = $handler->stackItems();
        $this->assertSame(['koala.jpg', 'clippy.png'], $result);
    }
}
