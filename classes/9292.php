<?php
/**
 * 9292 Api Wrapper
 */
class _9292
{
    /**
     * Api Host
     *
     * @var string
     */
    private $host = "api.9292.nl";
    /**
     * Api version
     *
     * @var string
     */
    private $version = "0.1";

    /**
     * Api protocol, both https and http works
     * Using https for increased security
     *
     * @var string
     */
    private $protocol = 'https';

    /**
     * Build the api url
     * @param $resource
     * @param array $options
     * @return string
     */
    private function buildUrl($resource, array $options)
    {
        $query = '?';

        foreach($options as $option => $value)
        {
            // replace spaces with + sign
            $value = preg_replace("/ /","+",$value);
            // concatenate all the options to the url
            $query .= $option . '=' . $value . '&';
        }
        // build the url
        return $this->protocol . '://' . $this->host . '/' . $this->version . '/' . $resource . $query;
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function getLocations(array $options)
    {
        // default query options for locations resource
        $defaultOptions = array(
            'q' => '',
            'lang' => 'nl-NL',
            'includeStation' => 'false',
        );

        // if we have new options replace the options
        if(!empty($options))
        {
            $options = array_replace($defaultOptions, $options);
        }
        else
        {
            // keep the default options
            $options = $defaultOptions;
        }

        // build the resource url
        $url = $this->buildUrl('locations', $options);
        return $this->getUrl($url);
    }

    /**
     * Get all journeys
     *
     * @param array $options
     * @return mixed
     */
    public function getJourneys(array $options)
    {
        // default query options for journeys resource
        $defaultOptions = array(
            'before' => '1',
            'sequence' => '1',
            'byBus' => 'true',
            'byFerry' => 'true',
            'bySubway' => 'true',
            'byTram' => 'true',
            'byTrain' => 'true',
            'lang' => 'nl-NL',
            'from' => 'emmen',
            'to' => 'emmen/van-schaikweg-94',
            'dateTime' => '2013-11-01T1000',
            'searchType' => 'departure',
            'interchangeTime' => 'standard',
            'after' => '5'
        );

        // if we have new options replace the options
        if(!empty($options))
        {
            $options = array_replace($defaultOptions, $options);
        }
        else
        {
            // keep the default options
            $options = $defaultOptions;
        }
        // build the resource url
        $url = $this->buildUrl('journeys', $options);

        return $this->getUrl($url);
    }

    /**
     * Make an cURL request to the given URL
     * Returns the json
     *
     * @param $url
     * @return mixed
     */
    private function getUrl($url)
    {
        // init curl
        $ch = curl_init();
        // set the curl url
        curl_setopt($ch, CURLOPT_URL, $url);
        // don't print the headers
        curl_setopt($ch, CURLOPT_HEADER, false);
        // don't echo out the result
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // save the result
        $result = curl_exec($ch);
        // close the connection
        curl_close($ch);
        // return all objects
        return $result;
    }
}