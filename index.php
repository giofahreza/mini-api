<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');

    if (!function_exists('uri')) {
        /**
         * Helper get array of uri
         * @param string $args
         * @return array|int|string
         */


        function uri($args=''){    
            $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $uri_segments = explode('/', $uri_path);

            $start_uri=EXPLODE('/', $_SERVER['PHP_SELF']);
            foreach($start_uri as $key=>$value){
                if($value=='index.php'){
                    $start_uri=$key;
                }
            }

            foreach($uri_segments as $key=>$value){
                if($key>=$start_uri){
                    $uri[]=$value;
                }
            }

            $find_index = array_search('index.php', $uri);
            if($find_index!=''){
                // Remove index.php if exists
                unset($uri[$find_index]);
                // Reset uri array key
                $uri = array_values($uri);
            }
            
            if(($args=='' || !$args) && gettype($args)!='integer'){
                return $uri;
            }elseif(gettype($args)=='integer' && isset($uri[$args])){
                return $uri[$args];
            }

            return false;
        }
    }

    if (!function_exists('full_url')) {
        /**
         * Helper get full url with param or not
         * @param bool $get
         * @return string
         */

        function full_url($get=TRUE){
            if($get){
                $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            }else{
                $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]".strtok($_SERVER["REQUEST_URI"],'?');
            }
            return $url;
        }
    }

    if (!function_exists('print_debug')) {
        /**
         * Helper get decimal value if needed.
         * @param string $data
         * @param bool $die
         * @return void
         */

        function print_debug($data='No data found',$die=true){
            echo '<pre>';
            print_r($data);
            echo '</pre>';

            if($die) die();
        }
    }

    if (!function_exists('check_included_file')) {
        /**
         * Helper get decimal value if needed.
         * @param string $filename
         * @return bool
         */

        function check_included_file($filename){
            $includeds = get_included_files();
            foreach($includeds as $key=>$included){
                $includeds[$key] = basename($included);
            }

            return !empty(in_array($filename, $includeds))?TRUE:FALSE;
        }
    }

    if (!function_exists('return_data')) {
        /**
         * Helper get decimal value if needed.
         * @param int $code
         * @param array $data
         * @return void
         */

        function return_data($code = 404, $data=["code" => 404, "message" => "404 not found"]){
            // Common response code
            // 200 – This is the standard “OK” status code for a successful HTTP request. The response that is returned is dependent on the request. For example, for a GET request, the response will be included in the message body. For a PUT/POST request, the response will include the resource that contains the result of the action.
            // 201 – This is the status code that confirms that the request was successful and, as a result, a new resource was created. Typically, this is the status code that is sent after a POST/PUT request.
            // 204 – This status code confirms that the server has fulfilled the request but does not need to return information. Examples of this status code include delete requests or if a request was sent via a form and the response should not cause the form to be refreshed or for a new page to load.
            // 304 – The is status code used for browser caching. If the response has not been modified, the client/user can continue to use the same response/cached version. For example, a browser can request if a resource has been modified since a specific time. If it hasn’t, the status code 304 is sent. If it has been modified, a status code 200 is sent, along with the resource.
            // 400 – The server cannot understand and process a request due to a client error. Missing data, domain validation, and invalid formatting are some examples that cause the status code 400 to be sent.
            // 401 – This status code request occurs when authentication is required but has failed or not been provided.
            // 403 – Very similar to status code 401, a status code 403 happens when a valid request was sent, but the server refuses to accept it. This happens if a client/user requires the necessary permission or they may need an account to access the resource. Unlike a status code 401, authentication will not apply here.
            // 404 – The most common status code the average user will see. A status code 404 occurs when the request is valid, but the resource cannot be found on the server. Even though these are grouped in the Client Errors “bucket,” they are often due to improper URL redirection.
            // 409 – A status code 409 is sent when a request conflicts with the current state of the resource. This is usually an issue with simultaneous updates, or versions, that conflict with one another.
            // 410 – Resource requested is no longer available and will not be available again. Learn about network error 410.
            // 500 – Another one of the more commonly seen status codes by users, the 500 series codes are similar to the 400 series codes in that they are true error codes. The status code 500 happens when the server cannot fulfill a request due to an unexpected issue. Web developers typically have to comb through the server logs to determine where the exact issue is coming from.
        
            // Remove unrelate response data
            if(isset($data['code'])) unset($data['code']);

            // Check if data is string
            if(is_string($data)) $data = ["message" => $data];

            // Set content type
            header("Content-Type: application/json");
            // Set response code
            http_response_code($code);
            // Return data
            echo json_encode($data);
        }
    }

    include('main.php');

    $method = $_SERVER["REQUEST_METHOD"];
    $uri = uri();

    $map_endpoint = array(
        "Don't remove. And ignore this value",

        "GET main/notfound",
        "GET main/test",
        "POST main/test-id/v",
        "GET main/test-get",
        "POST main/test-post",
        "POST main/get-auth-token",

        "GET indonesia/province",
        "GET indonesia/province/v",
    );

    $data = notfound();

    // BASE_URL/file_or_feature_name/function_name/data1/data2/data...
    // Check if feature and function name was inputed in url
    if(empty($uri[0]) || empty($uri[1])) return_data();

    // Build endpoint mapping ($map_search) value to match with endpoint mapping ($map_endpoint)
    $map_search = "$method $uri[0]/$uri[1]";
    for($i=1; $i<=count($uri)-2; $i++){
        $map_search .= "/v";
    }

    // Check endpoint exist
    $map_found = array_search($map_search, $map_endpoint);
    if(!empty($map_found)){
        // Replace dash to underscore
        $filename = str_replace('-', '_', $uri[0]);
        $functionname = str_replace('-', '_', $uri[1]);

        // Check if file exist
        if(!file_exists("$filename.php")) return_data(500, "We're sory. Unfortunately your destination can't be reach. Please try again later.");

        // Include the mini api destination if file not included yet
        if(!check_included_file("$filename.php")){
            include("$filename.php");
        }

        // Check if function exist
        if(!function_exists($functionname)) return_data(500, "We're sory. Unfortunately your destination can't be reach. Please try again later.");

        // Call api function
        $map_function = '$data'." = $functionname(";
        $i=2;
        while(true){
            if(!empty($uri[$i])){
                if($i>2) $map_function .= ", ";
                $map_function .= "$uri[$i]";
                $i++;
                continue;
            }
            $map_function .= ");";
            break;
        }
        eval($map_function);
    }

    return_data($data['code'], $data);
?>