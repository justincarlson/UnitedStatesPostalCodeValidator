<?php

/**
 * Checks known region assignments for US postal codes and does a 
 * Smartstreets lookup if that fails.
 * 
 * @license MIT
 * @version DIRTY ALPHA 0
 * @author Justin.Carlson@gmail.com
 * 
 */

class UnitedStatesPostalCodeValidator {

    private $_id = null;
    private $_token = null;
    private $_reason = null;
    
    /**
     * Constructor
     * @param string Smartstreets Auth Id
     * @param string Smartstreets Private Token
     */
    public function __construct($id,$token) {
        $this->_id = $id;
        $this->_token = $token;
    }
    
    /**
     * Returns validation failure reason if one was set, or null.
     * @return string reason last validation failed
     */
    public function getReason() {
        return $this->_reason;
    }
    
    public function isValid($country, $state, $zip) {

        if ($country != 'US') {
            $this->_reason = 'Postal validator only works for US addresses.';
            return true; // skip
        }

        if (strlen($zip) < 5) {
            $this->_reason = 'Invalid postal code length.';
            return false;
        }

        $zip = substr($zip, 0, 5);

        if (!preg_match('/[0-9]{5}/', $zip)) {
            $this->_reason = 'Invalid postal code content.';
            return false;
        }

        // do quick check for easy known asignments
        if ($this->isValidRegion($state, $zip)) {
            return true;
        }

        // call webservice to validate
        return self::CloudValidate($state, $zip);
    }

    public function isValidRegion($state, $zip) {

        $region = substr($zip, 0, 3);
        if (!is_numeric($region)) {
            return false;
        }

        // 3 digit regions
        if ($region >= 980 && $region <= 994 && ($state == 'WA')) {
            return true;
        }
        if ($region >= 900 && $region <= 961 && ($state == 'CA')) {
            return true;
        }
        if ($region >= 995 && $region <= 999 && ($state == 'AK')) {
            return true;
        }
        if ($region >= 832 && $region <= 839 && ($state == 'ID')) {
            return true;
        }
        if ($region >= 889 && $region <= 899 && ($state == 'NV')) {
            return true;
        }
        if ($region >= 820 && $region <= 831 && ($state == 'WY')) {
            return true;
        }
        if ($region >= 870 && $region <= 884 && ($state == 'NM')) {
            return true;
        }
        if ($region >= 550 && $region <= 567 && ($state == 'MN')) {
            return true;
        }
        if ($region >= 716 && $region <= 729 && ($state == 'AR')) {
            return true;
        }
        if ($region >= 700 && $region <= 715 && ($state == 'LA')) {
            return true;
        }
        if ($region >= 370 && $region <= 385 && ($state == 'TN')) {
            return true;
        }
        if ($region >= 386 && $region <= 397 && ($state == 'MS')) {
            return true;
        }
        if ($region >= 150 && $region <= 196 && ($state == 'PA')) {
            return true;
        }
        if ($region >= 247 && $region <= 269 && ($state == 'WV')) {
            return true;
        }
        if ($region >= 220 && $region <= 246 && ($state == 'VA')) {
            return true;
        }
        if ($region >= 206 && $region <= 219 && ($state == 'MD')) {
            return true;
        }
        if ($region == 200 && ($state == 'DC')) {
            return true;
        }
        if ($region >= 197 && $region <= 199 && ($state == 'DE')) {
            return true;
        }
        if ($region >= 967 && $region <= 968 && ($state == 'HI')) {
            return true;
        }

        if ($zip == '06390' && $state == 'NY') {
            return true;
        }

        if ($region == '005' && $state == 'NY') {
            return true;
        }

        if ($region == '055' && $state == 'MA') {
            return true;
        }

        if ($region == '201' && $state == 'VA') {
            return true;
        }
        if ($region == '398' && $state == 'GA') {
            return true;
        }
        if ($region == '399' && $state == 'GA') {
            return true;
        }
        if ($region == '569' && $state == 'DC') {
            return true;
        }
        if ($region == '885' && $state == 'TX') {
            return true;
        }

        if (substr($region, 0, 1) == '0') {
            $region = substr($region, 1, 2);
            if ($region >= 28 && $region <= 29 && ($state == 'RI')) {
                return true;
            }
            if ($region >= 10 && $region <= 27 && ($state == 'MA')) {
                return true;
            }
            if ($region >= 30 && $region <= 38 && ($state == 'NH')) {
                return true;
            }
            if ($region >= 39 && $region <= 49 && ($state == 'ME')) {
                return true;
            }
        }


        // two digit regions
        $region = substr($zip, 0, 2);
        if ($region == '07' && ($state == 'NJ')) {
            return true;
        }
        if ($region == '08' && ($state == 'NJ')) {
            return true;
        }
        if ($region == '05' && ($state == 'VT')) {
            return true;
        }
        if ($region == '06' && ($state == 'CT')) {
            return true;
        }

        if ($region == '97' && ($state == 'OR')) {
            return true;
        }
        if ($region == '59' && ($state == 'MT')) {
            return true;
        }
        if ($region == '84' && ($state == 'UT')) {
            return true;
        }
        if ($region == '58' && ($state == 'ND')) {
            return true;
        }
        if ($region == '57' && ($state == 'SD')) {
            return true;
        }

        if ($region >= 85 && $region <= 86 && ($state == 'AZ')) {
            return true;
        }
        if ($region >= 80 && $region <= 81 && ($state == 'CO')) {
            return true;
        }
        if ($region >= 68 && $region <= 69 && ($state == 'NE')) {
            return true;
        }
        if ($region >= 66 && $region <= 67 && ($state == 'KS')) {
            return true;
        }
        if ($region >= 73 && $region <= 74 && ($state == 'OK')) {
            return true;
        }
        if ($region >= 75 && $region <= 79 && ($state == 'TX')) {
            return true;
        }
        if ($region >= 53 && $region <= 54 && ($state == 'WI')) {
            return true;
        }
        if ($region >= 50 && $region <= 52 && ($state == 'IA')) {
            return true;
        }
        if ($region >= 63 && $region <= 65 && ($state == 'MO')) {
            return true;
        }
        if ($region >= 60 && $region <= 62 && ($state == 'IL')) {
            return true;
        }
        if ($region >= 48 && $region <= 49 && ($state == 'MI')) {
            return true;
        }
        if ($region >= 46 && $region <= 47 && ($state == 'IN')) {
            return true;
        }
        if ($region >= 40 && $region <= 42 && ($state == 'KY')) {
            return true;
        }
        if ($region >= 43 && $region <= 45 && ($state == 'OH')) {
            return true;
        }
        if ($region >= 35 && $region <= 36 && ($state == 'AL')) {
            return true;
        }
        if ($region >= 32 && $region <= 34 && ($state == 'FL')) {
            return true;
        }
        if ($region >= 30 && $region <= 31 && ($state == 'GA')) {
            return true;
        }
        if ($region >= 10 && $region <= 14 && ($state == 'NY')) {
            return true;
        }
        if ($region >= 27 && $region <= 28 && ($state == 'NC')) {
            return true;
        }
        if ($region == 29 && ($state == 'SC')) {
            return true;
        }

        return false;
    }

    /**
     * Lookup zip code and then check list for state provided.
     * https://us-zipcode.api.smartystreets.com/lookup?auth-id=YOUR+AUTH-ID+HERE&auth-token=YOUR+AUTH-TOKEN+HERE&city=mountain+view&state=CA&zipcode=94035
     */
    public function CloudValidate($state, $zip) {

        $states = array();
        $response = self::fetch('https://us-zipcode.api.smartystreets.com/lookup?auth-id='.$this->_id.'&auth-token='.$this->_token.'&zipcode=' . $zip);
        if (!empty($response)) {
            if (stristr($response, 'invalid_zipcode')) {
                $this->_reason = 'Invalid Postal Code.';
                return false;
            }
            $result = json_decode($response);
            if (!empty($result) && is_array($result)) {
                foreach ($result as $record) {
                    if (isset($record->city_states) && is_array($record->city_states)) {
                        foreach ($record->city_states as $city) {
                            if (!empty($city->state_abbreviation) && $city->state_abbreviation == $state) {
                                return true;
                            } elseif (!empty($city->state_abbreviation)) {
                                array_push($states,$city->state_abbreviation);
                            }
                        }
                        $states = array_unique($states);
                        $this->_reason = 'The postal code '.$zip.' is valid for the following states ('.implode(',',$states).'),  not what you chose ('.$state.').';
                        return false;
                    }
                }
            }
        }
        $this->_reason = 'Smartstreets lookup failed.';
        return false;
    }

    /**
     * Silly cURL wrapper. How many times have we written these.
     * @param string $url
     * @return mixed response from url
     */
    private static function fetch($url) {
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}
