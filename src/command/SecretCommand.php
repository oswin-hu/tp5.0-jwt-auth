<?php


namespace jwt\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;


class SecretCommand extends Command
{
    public function configure()
    {
        $this->setName('jwt:create')
            ->setDescription('create jwt secret and create config file');
    }

    public function execute(Input $input, Output $output)
    {
        $key  = md5(uniqid().time().rand(0, 60));
        $path = APP_PATH.'..'.DIRECTORY_SEPARATOR.'.env';

        if (file_exists($path)
            && strpos(file_get_contents($path), '[JWT]')
        ) {
            $output->writeln('JWT_SECRET is exists');
        } else {
            file_put_contents(
                $path,
                PHP_EOL."[JWT]".PHP_EOL."SECRET=$key".PHP_EOL,
                FILE_APPEND
            );
            $output->writeln('JWT_SECRET has created');
        }
    }

}
