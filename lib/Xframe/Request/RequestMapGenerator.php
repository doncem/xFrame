<?php

namespace Xframe\Request;

use Exception;
use Minime\Annotations\AnnotationsBag;
use Minime\Annotations\Cache\FileCache;
use Minime\Annotations\Parser;
use Minime\Annotations\Reader;
use ReflectionAnnotatedMethod;
use ReflectionClass;
use ReflectionMethod;
use Xframe\Core\DependencyInjectionContainer;

/**
 * This class analyses class annotations to create request map files for each controller.
 *
 * @package request
 */
class RequestMapGenerator
{
    /**
     * @var array
     */
    private $includeDirs;

    /**
     * @var DependencyInjectionContainer
     */
    private $dic;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param DependencyInjectionContainer $dic
     */
    public function __construct(DependencyInjectionContainer $dic)
    {
        $this->dic = $dic;

        $this->includeDirs = [
            $dic->root . 'src' . DIRECTORY_SEPARATOR,
            $dic->root . 'lib' . DIRECTORY_SEPARATOR
        ];

        // make sure we can write to the tmp directory or use the sys tmp
        if (!\is_writable($this->dic->tmp)) {
            $this->dic->tmp = \sys_get_temp_dir() . DIRECTORY_SEPARATOR;
        }
    }

    /**
     * @return CachedReader
     */
    private function getAnnotationReader()
    {
        if (null === $this->reader) {
            $this->reader = new Reader(
                new Parser(),
                new FileCache($this->dic->tmp)
            );
            $this->reader->getParser()->registerConcreteNamespaceLookup(['Xframe\\Request\\Annotation\\']);
        }

        return $this->reader;
    }

    /**
     * This method recursively looks through the given dir for controllers using
     * annotations and generates a request map file.
     *
     * @param string $dir
     */
    public function scan($dir)
    {
        if (!\is_dir($dir) || false === ($dh = \opendir($dir))) {
            return;
        }

        //for each file in the directory
        while (false !== ($file = \readdir($dh))) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            //if it is something we want to ignore...
            if ('.' === $file || '..' === $file) {
                continue;
            } elseif (\is_dir($path)) {
                $this->scan($path);
            } elseif ('.php' === \mb_substr($path, -4)) {
                $class = \str_replace($this->includeDirs, '', $path);
                $class = \str_replace(DIRECTORY_SEPARATOR, '\\', $class);
                $class = \pathinfo($class, PATHINFO_FILENAME);

                $this->analyseClass($class);
            }
        }
    }

    /**
     * This method uses reflection to see if the given class uses annotations
     * to define a request handler. It returns a string that contains the
     * serialised Resource.
     *
     * @param string $class
     *
     * @return string
     */
    private function analyseClass($class)
    {
        try {
            $reflection = new ReflectionClass($class);
        } catch (Exception $ex) {
            if (PHP_SAPI === 'cli') {
                die($ex->getMessage() . PHP_EOL);
            }

            return;
        }

        if ($reflection->isSubclassOf('Xframe\\Request\\Controller')) {
            $annotationReader = $this->getAnnotationReader();
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

            /* @var $method ReflectionMethod */
            foreach ($methods as $method) {
                $methodAnnotations = $annotationReader->getMethodAnnotations($reflection->name, $method->name);
                if (null !== $methodAnnotations && $methodAnnotations->has('Request')) {
                    $this->processRequest($method, $methodAnnotations);
                }
            }
        }
    }

    /**
     * Create the cache file for the request.
     *
     * @param ReflectionMethod $method
     * @param array            $annotations
     */
    private function processRequest(ReflectionMethod $method, AnnotationsBag $annotations)
    {
        $request = $annotations->get('Request');
        $params = $annotations->get('Parameter', []);

        if (!\is_array($params)) {
            $params = [$params];
        }

        $cacheLength = $annotations->get('CacheLength', false);
        $view = $annotations->get('View', $this->dic->registry->get('DEFAULT_VIEW'));
        $template = $annotations->get('Template', $request);

        $prefilters = $annotations->get('Prefilter', []);

        if (!\is_array($prefilters)) {
            $prefilters = [$prefilters];
        }

        foreach ($prefilters as $key => $prefilter) {
            if (!\class_exists($prefilter)) {
                unset($key);
            }
        }

        $customParameters = $annotations->get('CustomParam', []);
        $customParams = [];

        if (!\is_array($customParameters)) {
            $customParameters = [$customParameters];
        }

        /* @var $customParameter Annotation\CustomParam */
        foreach ($customParameters as $customParameter) {
            $customParams[$customParameter->name] = $customParameter->value;
        }

        $newLine = PHP_EOL . '    ';
        $fileContents = '<?php' . PHP_EOL . PHP_EOL;
        $fileContents .= '// Automatically generated code, do not edit.' . PHP_EOL;

        if (\count($customParams) > 0) {
            $fileContents .= '$request->addParameters(' . \var_export($customParams, true) . ');' . PHP_EOL;
        }

        $fileContents .= "return new {$method->class}({$newLine}";
        $fileContents .= "\$this->dic,{$newLine}";
        $fileContents .= "\$request,{$newLine}";
        $fileContents .= \var_export($method->name, true) . ",{$newLine}";
        $fileContents .= "new {$view}(\$this->dic->registry, \$this->dic->root, \$this->dic->tmp, ";
        $fileContents .= \var_export($template, true) . ", \$request->debug),{$newLine}";
        $fileContents .= "[{$newLine}";

        /* @var $param Annotation\Parameter */
        foreach ($params as $param) {
            $fileContents .= 'new Xframe\Request\Parameter(\'' . $param->name . '\',' . $newLine;
            $fileContents .= $param->validator ? 'new ' . $param->validator . ',' . $newLine : "null,{$newLine}";
            $fileContents .= \var_export($param->required, true) . ",{$newLine}";
            $fileContents .= \var_export($param->default, true) . '),';
        }

        $fileContents .= "],{$newLine}";
        $fileContents .= '[';

        foreach ($prefilters as $filter) {
            $fileContents .= "new {$filter}(\$this->dic), ";
        }

        $fileContents .= "],{$newLine}";
        $fileContents .= \var_export($cacheLength, true) . PHP_EOL . ');';

        $filename = $this->dic->tmp . $request . '.php';

        try {
            \file_put_contents($filename, $fileContents);
        } catch (Exception $e) {
            throw new Exception('Could not create request cache file: ' . $filename, 0, $e);
        }
    }

    /**
     * Return the given parameter if it exists or the $default if not.
     *
     * @param ReflectionAnnotatedMethod $annotation
     * @param string                    $param
     * @param mixed                     $default
     *
     * @return mixed
     */
    private function getOrReturn(ReflectionAnnotatedMethod $annotation, $param, $default)
    {
        return $annotation->hasAnnotation($param) ? $annotation->getAnnotation($param)->value : $default;
    }
}
