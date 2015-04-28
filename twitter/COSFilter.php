<?php
/**
 * Class that can be used to filter COS data for desired attributes and lists
 * The class also contains some methods to modify the filtered data even more
 */




class COSFilter {


    private $data,      // The raw data received from COS, multidimensional array
            $filters;   // The filters to apply, multidimensional array

    public function __construct($data, $filters) {
        $this->data = $data;
        $this->filters = $filters;
    }


    /**
     * Method to search for a certain key in a multidimensional array,
     * returns corresponding value.
     * Will return first occurrence only.
     *
     * @param $haystack     array   The data to be searched in
     * @param $needle       string  The data to be searched for
     * @return              string  The found value
     */
    public function find ($haystack, $needle) {
        foreach ($haystack as $key => $value ) {
            if (!is_array($value)) {
                if ($key == $needle) {

                    // Key is found
                    $result = $value;
                    break;
                }
            } else {

                // Deepen the search
                $res = $this->find($value, $needle);
                if (!isset($res["Message"])) {

                    // Key is found in deeper search
                    $result = $res;
                    break;
                }
            }
        }

        if (isset($result)) {
            return $result;
        } else {
            return array("Message" => "No result found");
        }
    }

    /**
     * Method to retrieve a subset of data within a larger data set,
     * done by describing the path.
     *
     * @param $data     array   The large data set you start with
     * @param $path     array   The path of the data set you wish to retrieve
     * @param $index    int     Depth of current subset
     * @return          array   Returns acquired subset
     */
    public function router($data, $path, $index) {
        if (count($path) > 0) {
            if (array_key_exists($path[$index], $data)) {
                if (count($path) - 1 > $index) {

                    // Deepen the search
                    $next = $index + 1;
                    $subset = $this->router($data[$path[$index]], $path, $next);
                } else {

                    // Subset is found
                    $subset = $data[$path[$index]];
                }
            }
        } else {
            // If no path is set, return full data set
            $subset = $data;
        }

        if (isset($subset)) {
            return $subset;
        } else {
            return array("Message" => "Path not found");
        }
    }

    /**
     * Method to filter an entire data set based on given filters.
     * Returns the filtered data set.
     *
     * @param $data     array   The large data set to filter
     * @param $filters  array   The filters to apply to the data set
     * @param $path     array   The current path of the filter
     * @return          array   Returns the filtered data
     */
    public function filterAll($data, $filters, $path) {
        foreach ($filters as $key => $value) {
            if (is_int($key) && !is_array($value)) {

                // Use the find() method to retrieve a single value
                $result[$value] = $this->find($this->router($data, $path, 0), $value);

            } else if (is_int($key) && is_array($value)) {

                // Used to get all objects in a list
                $count = count($this->router($data, $path, 0));
                for ($i = 0; $i < $count; $i++) {
                    $path[count($path)] = $i;
                    $result[$i] = $this->filterAll($data, $value, $path);
                    array_pop($path);
                }

            } else if (!is_int($key) && is_array($value)) {

                // Used to get all attributes of object, if no attributes are specified
                if (empty($value)) {
                    $path[count($path)] = $key;
                    $result[$key] = $this->router($data, $path, 0);
                    array_pop($path);

                // If the given value is an indexed array, deepen the filter for a smaller data set
                } else {
                    $path[count($path)] = $key;
                    $result[$key] = $this->filterAll($data, $value, $path);
                    array_pop($path);
                }
            }
        }
        if (isset($result)) {
            return $result;
        } else {
            return array("Message" => "Failed to filter");
        }
    }

    /**
     * Method to find an object in a data set that contains an attribute of a specific value.
     * The path is needed to specify the area of the data set you wish to search.
     * (For example, if you wish to obtain an object, with an attribute 'id' of value '123',
     * you have to specify what kind of object that is.
     * Different object types can have the same attributes and thus the same value.)
     * Returns the found object.
     *
     * @param $data         array   The data set to search
     * @param $path         array   The path to search in
     * @param $needleKey    string  The attribute to search for
     * @param $needleValue  string  The value of the attribute to search for
     * @return              array   Returns the found object
     */
    public function findByAttribute($data, $path, $needleKey, $needleValue) {
        $subData = $this->router($data, $path, 0); // Retrieve sub data set to search in
        if (is_array($subData)) {

            // Loop over all possible objects
            foreach($subData as $index => $candidate) {

                // Loop over all attributes of an object
                foreach ($candidate as $key => $value) {

                    if ($key == $needleKey && $value == $needleValue) {

                        // Object is found
                        $result = $candidate;
                        break;
                    }
                }
            }
        }
        if (isset($result)) {
            return $result;
        } else {
            return array("Message" => "No result found for attributes");
        }
    }

    /**
     * Method to remove attributes from a data set.
     *
     * @param $data         array   The data set you wish to remove an attribute from
     * @param $attributes   array   A list with attributes you wish to remove
     * @return              array   Returns the new data set
     */
    public function removeAttributes($data, $attributes) {
        foreach ($attributes as $index => $attribute) {
            unset($data[$attribute]);
        }
        if (isset($data)) {
            return $data;
        } else {
            return array("Message" => "Failed to remove attributes");
        }

    }

    /**
     * Method to rename attributes while maintaining the order of a data set.
     * Returns the renamed data set
     *
     * @param $data     array   The data set you wish to rename attributes in
     * @param $renames  array   List with attributes to rename
     * @return          array   Returns the renamed data set
     */
    public function renameAttributes($data, $renames) {
        foreach ($renames as $current => $rename) {
            $data = $this->rename($data, $current, $rename);
        }
        return $data;
    }

    /**
     * Method to rename a single attribute in a data set.
     * Returns the renamed data set.
     *
     * @param $data     array   The data set to rename attributes in
     * @param $current  string  The attribute to rename
     * @param $rename   string  The new attribute name
     * @return          array   Returns the renamed data set
     */
    public function rename ($data, $current, $rename) {
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                if ($key == $current) {
                    $keys = array_keys($data);
                    $index = array_search($current, $keys);
                    $keys[$index] = $rename;
                    $data = array_combine($keys, $data);
                }
            } else {
                $data[$key] = $this->rename($value, $current, $rename);
            }
        }
        return $data;
    }

    /**
     * Method to apply all the filters to the raw data set.
     * Returns the filtered data set.
     *
     * @return  array   Returns a filtered data set
     */
    public function filter() {
         return $this->filterAll($this->data, $this->filters, array());
    }
}
