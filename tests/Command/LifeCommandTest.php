<?php

declare(strict_types=1);

namespace App\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;


class LifeServiceTest extends TestCase
{

    public function testLifeCommand(): void
    {
        $application = new Application(\dirname(__DIR__, 3));
        $application->add(new LifeCommand());

        $command = $application->find('app:life');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Life is done!', $output);
    }
}
