<?php
class ClassNotFoundException extends Exception
{
    /**
     * full qualified class name of the class that was not found
     *
     * @var  string
     */
    protected $fqClassName;

    /**
     * constructor
     *
     * @param  string  $fqClassName         full qualified class name of the class that was not found
     * @param  bool    $foreignClassLoader  optional  true if thrown in stubForeignClassLoader instance
     */
    public function __construct($fqClassName, $foreignClassLoader = false)
    {
        $this->fqClassName = $fqClassName;
        $caller  = debug_backtrace();
        $file   = ((false == $foreignClassLoader) ? ($caller[1]['file']) : ($caller[2]['file']));
        $line   = ((false == $foreignClassLoader) ? ($caller[1]['line']) : ($caller[2]['line']));
        $message = 'The class \'' . $this->fqClassName . '\' loaded in ' . $file . ' on line ' . $line . ' was not found.';
        parent::__construct($message);
    }

    /**
     * returns the full qualified class name of the class that was not found
     *
     * @return  string
     */
    public function getNotFoundClassName()
    {
        return $this->fqClassName;
    }

    /**
     * returns a string representation of the class
     *
     * The result is a short but informative representation about the class and
     * its values. Per default, this method returns:
     * <code>
     * net.stubbles.stubClassNotFoundException {
     *     message(string): The class example.Foo loaded in bar.php on line 6 was not found.
     *     classname(string): example.Foo
     *     file(string): stubClassLoader.php
     *     line(integer): 179
     *     code(integer): 0
     * }
     * </code>
     *
     * @return  string
     */
    public function __toString()
    {
        $string  = "net.stubbles.stubClassNotFoundException {\n";
        $string .= '    message(string): ' . $this->getMessage() . "\n";
        $string .= '    classname(string): ' . $this->fqClassName . "\n";
        $string .= '    file(string): ' . $this->getFile() . "\n";
        $string .= '    line(integer): ' . $this->getLine() . "\n";
        $string .= '    code(integer): ' . $this->getCode() . "\n";
        $string .= "}\n";
        return $string;
    }
}

class ClassLoader {

	/**
	 * Classes die geladen zijn
	 */
	private static $classNames = array();

	/**
	 * path maar sourcefiles
	 */
	private static $sourcePath;

    public static function load() {
		$classNames = func_get_args();
        if (count($classNames) == 0) {
            return;
        }

        if(self::$sourcePath == null) {
        	self::$sourcePath = Config::getClassPath() . DIRECTORY_SEPARATOR;
        }

        foreach($classNames as $fqClassName) {
        	if(!isset(self::$classNames[$fqClassName])) {
        		self::$classNames[$fqClassName] = self::getNonQualifiedClassName($fqClassName);
		    	$uri = self::$sourcePath .  str_replace('.', DIRECTORY_SEPARATOR, $fqClassName) . '.class.php';
        		if ((include_once $uri) == false) {
                	throw new ClassNotFoundException($fqClassName);
            	}
        	}

        }
    }

    public static function getNonQualifiedClassName($fqClassName) {
        $classNameParts = explode('.', $fqClassName);
        return $classNameParts[count($classNameParts) - 1];
    }

    public static function getNonQualifiedClassNameFromUrl($fqClassName) {
        $classNameParts = explode('.', $fqClassName);
        return $classNameParts[count($classNameParts) - 2];
    }
}

?>