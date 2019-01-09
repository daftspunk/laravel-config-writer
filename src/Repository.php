<?php

namespace October\Rain\Config;

use Exception;
use October\Rain\Config\DataWriter\FileWriter;
use Illuminate\Config\Repository as RepositoryBase;

class Repository extends RepositoryBase
{
    /**
     * The config rewriter object.
     *
     * @var October\Rain\Config\FileWriter
     */
    protected $writer;

    /**
     * Create a new configuration repository.
     *
     * @param  October\Rain\Config\FileWriter $writer
     * @param  array $items
     * @return void
     */
    public function __construct(FileWriter $writer, array $items = [])
    {
        parent::__construct($items);
        $this->writer = $writer;
    }

    /**
     * Write a given configuration value to file.
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function write(string $key, $value): bool
    {
        list($filename, $item) = $this->parseKey($key);
        $result = $this->writer->write($item, $value, $filename);

        if(!$result) throw new Exception('File could not be written to');

        $this->set($key, $value);

        return $result;
    }

    /**
     * Split key into 2 parts. The first part will be the filename
     * 
     * @param string $key
     * @return array
     */
    private function parseKey(string $key): array
    {
        return preg_split('/\./', $key, 2);
    }
}