<?php

    // configuration
    require("../includes/config.php");
    
    // ensure proper usage
    if (isset($_GET["sheet"]) && isset($_GET["partner"]) && $_GET["partner"] !== "" && isset($_GET["logo"]) && $_GET["logo"] !== "")
    {
        // create array for json file names and bool to test for match
        $sheetnames = [];

        // open json directory
        if ($handle = opendir('../json')) 
        {
            // grab .json filenames from directory -- http://php.net/manual/en/function.readdir.php
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") 
                {
                    // remove file extension and add filenames to array -- http://stackoverflow.com/questions/2395882/how-to-remove-extension-from-string-only-real-extension
                    $sheetnames[] = preg_replace('/\\.[^.\\s]{4}$/', '', $entry);
                }
            }
            closedir($handle);
        }
        
        // ensure sheet exists
        if (!in_array($_GET["sheet"], $sheetnames))
        {
            http_response_code(400);
            exit;
        }
    }
    
    else
    {
        http_response_code(400);
        exit;
    }

    // retrieve json string from requested sheet
    $json = file_get_contents("../json/{$_GET["sheet"]}.json");

    // decode json string into associative array
    $items = json_decode($json, TRUE);

    // remove first object (contains sheet info) from array
    $sheetinfo = array_shift($items);

    // render list view and referral form for partners
    render("list.php", ["partner" => $_GET["partner"], "sheetinfo" => $sheetinfo, "items" => $items, "logo" => $_GET["logo"]]);
?>