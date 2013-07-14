<?php
class ParseConfig
{

	/**
	 * Source lifted from http://php.net/manual/en/function.parse-ini-file.php
	 */
 
	private function parse_ini ( $filepath ) {
	    $ini = file( $filepath );
	    if ( count( $ini ) == 0 ) { return array(); }
	    $sections = array();
	    $values = array();
	    $globals = array();
	    $i = 0;
	    foreach( $ini as $line ){
	        $line = trim( $line );
	        // Comments
	        if ( $line == '' || $line{0} == ';' ) { continue; }
	        // Sections
	        if ( $line{0} == '[' ) {
	            $sections[] = substr( $line, 1, -1 );
	            $i++;
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
	            // Array values
	            if ( substr( $line, -1, 2 ) == '[]' ) {
	                $values[ $i - 1 ][ $key ][] = $value;
	            } else {
	                $values[ $i - 1 ][ $key ] = $value;
	            }
	        }
	    }
	    for( $j=0; $j<$i; $j++ ) {
	        $result[ $sections[ $j ] ] = $values[ $j ];
	    }
	    return $result + $globals;
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
echo "------------------------FIRST START-------------------------->\n";
var_dump($ini);
echo "<------------------------FIRST END----------------------------\n";
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
}
?>
