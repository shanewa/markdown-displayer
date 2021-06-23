<?php
    // Open php debug log
    // ini_set("display_errors","On");
    // error_reporting(E_ALL);

    // /var/www/cgi-bin/mdd.php
    // /var/www/html/markdown-displayer
    //     ├── css
    //     │   └── github-markdown.css
    //     ├── demo
    //     │   ├── demo.html
    //     │   └── demo.md
    //     ├── js
    //     │   └── showdown.min.js
    //     ├── php
    //     │   └── mdd.php
    //     └── README.md    

    // e.g. http://$_SERVER[HTTP_HOST]/cgi-bin/mdd.php?md=/var/www/html/markdown-displayer/demo/demo.mdmd=/var/www/html/markdown-displayer/demo/demo.md

    $file_content = file_get_contents($_GET["md"]);
    // Replace all ` with \` as it will be treated as the key word in JS.
    $file_content = preg_replace('/`/', '\`', $file_content);
    $file_content = str_replace('$', '\$', $file_content);

    $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Markdown-displayer</title>
    <link rel="stylesheet" type="text/css" media="screen" href="http://$_SERVER[HTTP_HOST]/markdown-displayer/css/github-markdown.css">
    <!-- https://cdnjs.cloudflare.com/ajax/libs/showdown/1.9.0/showdown.min.js -->
    <script src="http://$_SERVER[HTTP_HOST]/markdown-displayer/js/showdown.min.js"></script>
</head>
<body>
    <div id="show-area" class="clearfix"></div>
    <script>
        function translateMarkdown(mdValue) {
            console.log(mdValue);
            var converter = new showdown.Converter();
            var html = converter.makeHtml(mdValue);
            document.getElementById("show-area").innerHTML = html;
        }
HTML;

    $html .= "        window.onload = function () {\n            translateMarkdown(`";
    $html .= $file_content;
    $html .= "`);\n        }\n    </script>\n</body>\n</html>";

    echo "$html";
?>
