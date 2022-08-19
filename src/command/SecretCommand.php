<?php


namespace jwt\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;


class SecretCommand extends Command
{
    public function configure()
    {
        $this->setName('jwt:create')
            ->addOption('module', 'm', Option::VALUE_OPTIONAL, 'set configuration generated modules', null)
            ->setDescription('create jwt secret and create config file');
    }

    protected function getModule()
    {
        $module = '';
        if ($this->input->hasOption('module')) {
            $module = $this->input->getOption('module');
        }
        return $module;
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

        $this->createConfig($output);
    }

    public function createConfig($output)
    {
        $configDir = APP_PATH;
        if (!empty($this->getModule())) {
            $moduleConfigDir = $configDir.DIRECTORY_SEPARATOR.$this->getModule();
            if (is_dir($moduleConfigDir)) {
                $configDir = $moduleConfigDir;
            }
        }

        $configDir .= DIRECTORY_SEPARATOR.'extra';
        if (!is_dir($configDir) && !mkdir($configDir, 0755) && !is_dir($configDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $configDir));
        }
        $configFilePath = $configDir.DIRECTORY_SEPARATOR.'jwt.php';

        if (is_file($configFilePath)) {
            $output->writeln('Config file is exist');
            return;
        }

        $res = copy(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR
            .'config.php', $configFilePath);

        if ($res) {
            $output->writeln('Create config file success:'.$configFilePath);
        } else {
            $output->writeln('Create config file error');
        }
    }

}
