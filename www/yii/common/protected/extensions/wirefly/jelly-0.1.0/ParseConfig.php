<?php
class ParseConfig
{

	//public $DEBUG = true;
	public $DEBUG = false;

	/**
	 * Source lifted from http://php.net/manual/en/function.parse-ini-file.php
	 */
 
	private function parse_ini ( $filepath ) {
	    $ini = $this->preprocess_file( $filepath );
	    if ( count( $ini ) == 0 ) { return array(); }
	    $sections = array();
	    $values = array();
	    $globals = array();
		$sectionHasItems = false;		// Allows empty sections
	    $i = 0;
	    foreach( $ini as $line ){
	        $line = trim( $line );
	        // Comments
	        if ( $line == '' || $line{0} == ';' || $line{0} == '#')
	        	continue;
	        // Includes
	        $incl = explode('=', $line, 2);
	        if (trim($incl[0]) == 'include') {
	        	$fileToInclude = dirname($filepath) . '/' . trim($incl[1]);
	        	//die($fileToInclude);
	        	array_merge($globals, parse_ini($fileToInclude));
	        }
	        // Sections
	        if ( $line{0} == '[' ) { 
	            $sections[] = substr( $line, 1, -1 );
				if (($i != 0) && ($sectionHasItems==false)) $values[ $i - 1 ][] = '';       // Guarantee at least one item in each section
	            $i++;
				$sectionHasItems = false;
	            continue;
	        }
	        // Key-value pair
	        list( $key, $value ) = explode( '=', $line, 2 );
	        $key = trim( $key );
	        $value = trim( $value );
	        if ( $i == 0 ) {
	            // Array values
	            if ( substr( $line, -1, 2 ) == '[]' ) {
	                $globals[ $key ][] = $value;
	            } else {
	                $globals[ $key ] = $value;
	            }
	        } else {
				$sectionHasItems = true;
	            // Array values
	            if ( substr( $line, -1, 2 ) == '[]' ) {
	                $values[ $i - 1 ][ $key ][] = $value;
	            } else {
					if (!isset($values[ $i - 1 ][ $key ]))
	                	$values[ $i - 1 ][ $key ] = $value;
					else
						$values[ $i - 1 ][ $key . '___' . 2 ] = $value;
	            }
	        }
	    }
	    for( $j=0; $j<$i; $j++ ) {
	    	if ($values[$j])
	        $result[ $sections[ $j ] ] = $values[ $j ];
	    }
	    return $result + $globals;
	}

	/**
	 * Preprocess an ini file
	 * 1) Expand includes
	 * 2) ...
	 */

	private function preprocess_file($filepath)
	{
		$ini = file( $filepath );
		return $ini;
		//die('xxx');
	}

    /**
     * Loads in the ini file specified in filename, and returns the settings in
     * it as an associative multi-dimensional array
     *
     * @param string $filename          The filename of the ini file being parsed
     * @param boolean $process_sections By setting the process_sections parameter to TRUE,
     *                                  you get a multidimensional array, with the section
     *                                  names and settings included. The default for
     *                                  process_sections is FALSE
     * @param string $section_name      Specific section name to extract upon processing
     * @return array
     * @throws Exception
     */

public $multiInclude = array();

    public /*static*/ function parse($filename, $process_sections = true, $section = null)
    {

		$ini = $this->parse_ini($filename);
//$ini = parse_ini_file($filename, $process_sections = true, $section = null);

        if ($ini === false)
            throw new Exception('Unable to parse ini file.');

		$this->logMsg("... Raw array after parsing ...\n\n");
		if ($this->DEBUG) var_dump($ini);

        if (!$process_sections && $section)
		{
            $values = $process_sections ? $ini[$section] : $ini;
            $result = self::_processSection($values);
        }
		else
		{
            $result = array();
            foreach ($ini as $section => $values)
			{
                if (!is_array($values))
                    continue;
                unset($ini[$section]);
                $expand = explode(':', $section);
                if (count($expand) == 2)
				{
                    $section = trim($expand[0]);
                    $source = trim($expand[1]);

					// Store any global includes for later merging
					if ($section == '*')
					{
						$this->multiInclude[$section] = $source;
						continue;
					}

                    if (!isset($result[$source]))
                        throw new Exception("Unable to expand $section from $source");
                    $sectionResult = self::_processSection($values);
                    $result[$section] = self::_mergeRecursive($result[$source], $sectionResult);
                }
				else
                    $result[$section] = self::_processSection($values, $result);
            }
            $result += $ini;
        }

		$this->logMsg("... Depandancy-merged array ...\n\n");
		if ($this->DEBUG) var_dump($result);

        return $result;
    }

    /**
     * Process a single section with values.
     * @param array $values
     * @return array With the result.
     */
    private /*static*/ function _processSection($values, $topResult)
    {
        $result = array();
        foreach ($values as $key => $value) {
            $keys = explode('.', $key);
            $result = self::_recurseValue($result, $keys, $value);
        }

		// Merge in any global includes
		if (array_key_exists('*', $this->multiInclude))
		    $result = self::_mergeRecursive($topResult[$this->multiInclude['*']], $result);  

        return $result;
    }

    /**
     * Create the values recursively.
     * @param array $array
     * @param array $keys
     * @param mixed $value
     * @return array The original array, with changes.
     */
    private /*static*/ function _recurseValue($array, $keys, $value)
    {
        $key = array_shift($keys);
        if (count($keys) > 0) {
            if (!isset($array[$key])) {
                $array[$key] = array();
            }
            $array[$key] = self::_recurseValue($array[$key], $keys, $value);
        } else {
            $array = self::_mergeValue($array, $key, $value);
        }
        return $array;
    }

    /**
     * Merge a value with the previous value.
     * @param array $array
     * @param string $key
     * @param mixed $value
     * @return array The original array, with changes.
     */
    private /*static*/ function _mergeValue($array, $key, $value)
    {
        if (!isset($array[$key])) {
            $array[$key] = $value;
        } else {
            if (is_array($value)) {
                $array[$key] += $value;
            } else {
                $array[$key][] = $value;
            }
        }
        return $array;
    }

    /**
     * Recursively merge arrays, as the PHP function does not overwrite values.
     * @param type $left
     * @param type $right
     */
    private /*static*/ function _mergeRecursive($left, $right) {
        // merge arrays if both variables are arrays
        if (is_array($left) && is_array($right)) {
            // loop through each right array's entry and merge it into $a
            foreach ($right as $key => $value) {
                if (isset($left[$key])) {
                    $left[$key] = self::_mergeRecursive($left[$key], $value);
                } else {
                    $left[$key] = $value;
                }
            }
        } else {
            // one of values is not an array
            $left = $right;
        }
        return $left;
    }

	private function logMsg($msg, $indentLevel=0)
	{
		if ($this->DEBUG)
		{
			$indent = "";
			while ($indentLevel--)
				$indent .= "&nbsp&nbsp&nbsp&nbsp";
			echo  nl2br($indent . $msg);
		}
	}

}
?>
