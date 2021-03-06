<?php
/**
 * Avatar API
 *
 * @author Fact Maven
 * @link https://api.factmaven.com/avatar
 * @version 1.2.1
 */

# Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
ini_set("user_agent","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36");

# Special properties
$link = (isset($_SERVER['HTTPS'])?"https":"http")."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

if (count($_GET)) {
    # Convert email into MD5 hash
    if (isset($_GET['email'])) {
        $email = $_GET['email'];
        $hash = md5(strtolower($email));
    }

    $gravatar = @file_get_contents("https://gravatar.com/".$hash.".json");
    if ($gravatar === FALSE) {
        $gravatar = FALSE;
    } else {
        $gravatar = json_decode($gravatar);
    }

    # Structure API
    $api = [
        "@link" => $link,
        "email" => $email,
        "hash" => $hash,
        "@sources" => [
            "gravatar" => [
                "avatar" => "https://gravatar.com/avatar/".$hash,
                "api" => $gravatar,
            ],
            "robohash" => [
                "avatar" => "https://robohash.org/".$hash.".png",
                "links" => [
                    "https://robohash.org/".$hash.".png?gravatar=hashed",
                    "https://robohash.org/".$hash.".png?set=set2",
                    "https://robohash.org/".$hash.".png?set=set3",
                    "https://robohash.org/".$hash.".png?set=set4",
                    "https://robohash.org/".$hash.".png?set=set5",
                ],
            ],
        ],
    ];
} else {
    $api = [
        "errors" => [
            "id" => "404",
            "title" => "Missing Parameter",
            "detail" => "Please start with adding '?email=name@example.com' at the end.",
        ],
        "meta" => [
            "version" => "1.2.1",
            "copyright" => "Copyright 2011-".date("Y")." Fact Maven",
            "link" => "https://factmaven.com/",
            "authors" => [
                "Ethan O'Sullivan",
            ]
        ],
    ];
}
# Output JSON
print_r(json_encode(array_filter($api)));