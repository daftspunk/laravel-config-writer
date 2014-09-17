<?php namespace October\Rain\Config;

use Illuminate\Config\LoaderInterface;

class FileWriter
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The loader implementation.
     *
     * @var \Illuminate\Config\LoaderInterface
     */
    protected $loader;

    /**
     * The default configuration path.
     *
     * @var string
     */
    protected $defaultPath;

    /**
     * The config rewriter object.
     *
     * @var \October\Rain\Config\Rewrite
     */
    protected $rewriter;

    /**
     * Create a new file configuration loader.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  string  $defaultPath
     * @return void
     */
    public function __construct(LoaderInterface $loader, $defaultPath)
    {
        $this->loader = $loader;
        $this->files = $loader->getFilesystem();
        $this->defaultPath = $defaultPath;
        $this->rewriter = new Rewrite;
    }

    public function write($item, $value, $environment, $group, $namespace = null)
    {
        $path = $this->getPath($environment, $group, $item, $namespace);
        if (!$path)
            return false;

        $contents = $this->files->get($path);
        $contents = $this->rewriter->toContent($contents, [$item => $value]);

        return !($this->files->put($path, $contents) === false);
    }

    private function getPath($environment, $group, $item, $namespace = null)
    {
        $hints = $this->loader->getNamespaces();

        $path = null;
        if (is_null($namespace)) {
            $path = $this->defaultPath;
        }
        elseif (isset($this->hints[$namespace])) {
            $path = $this->hints[$namespace];
        }

        if (is_null($path))
            return null;

        $file = "{$path}/{$environment}/{$group}.php";
        if ( $this->files->exists($file) &&
             $this->hasKey($file, $item)
        )
            return $file;

        $file = "{$path}/{$group}.php";
        if ($this->files->exists($file))
            return $file;

        return null;
    }
    
    private function hasKey($path, $key)
    {
        $contents = file_get_contents($path);
        $vars = eval('?>'.$contents);

        $keys = explode('.', $key);

        $isset = false;
        while ($key = array_shift($keys)) {
            $isset = isset($vars[$key]);
        }

        return $isset;
    }
}
