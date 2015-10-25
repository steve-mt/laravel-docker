<?php

namespace SteveAzz\Docker;

use FilesystemIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCommand extends Command
{
    /**
     * The base name of the Laravel installation.
     *
     * @var
     */
    protected $base_path;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->base_path = getcwd();

        $this
            ->setName('make')
            ->setDescription('Install Laravel-Docker into the current project');
    }

    /**
     * Execute the command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (!file_exists($this->base_path . '/docker-compose.yml')) {
            copy(__DIR__ . '/stubs/docker-compose.yml', $this->base_path . '/docker-compose.yml');
        }

        if (!file_exists($this->base_path . '/docker-config')) {
            $this->copyDirectory(__DIR__ . '/stubs/docker-config', $this->base_path . '/docker-config');
        }

        if (file_exists($this->base_path . '/storage')) {
            chmod($this->base_path . '/storage', 0777);
        }

        $output->writeln('<info>Docker compose is installed!</info>');
        $output->writeln('<info>Run docker-compose up to get up an running!</info>');
    }

    /**
     * Copy a directory from one location to another.
     *
     * @param string $directory
     * @param string $destination
     * @param int $options
     *
     * @return bool
     */
    public function copyDirectory($directory, $destination, $options = null)
    {
        if (!is_dir($directory)) {
            return false;
        }

        $options = $options ?: FilesystemIterator::SKIP_DOTS;

        // If the destination directory does not actually exist, we will go ahead and
        // create it recursively, which just gets the destination prepared to copy
        // the files over. Once we make the directory we'll proceed the copying.
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        $items = new FilesystemIterator($directory, $options);

        foreach ($items as $item) {
            // As we spin through items, we will check to see if the current file is actually
            // a directory or a file. When it is actually a directory we will need to call
            // back into this function recursively to keep copying these nested folders.
            $target = $destination . '/' . $item->getBasename();

            if ($item->isDir()) {
                $path = $item->getPathname();

                if (!$this->copyDirectory($path, $target, $options)) {
                    return false;
                }
            }

            // If the current items is just a regular file, we will just copy this to the new
            // location and keep looping. If for some reason the copy fails we'll bail out
            // and return false, so the developer is aware that the copy process failed.
            else {
                if (!copy($item->getPathname(), $target)) {
                    return false;
                }
            }
        }

        return true;
    }
}
