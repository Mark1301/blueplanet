<?php

include "./library/simple_html_dom.php";

$html = str_get_html('<a href=\"https://economictimes.indiatimes.com/industry/beer-to-cost-less-in-rajasthan-from-april-1/articleshow/80726722.cms\"><img width=\"100\" height=\"75\" src=\"https://img.etimg.com/photo/80726722.cms\" alt=\"80726722.cms\"></a>The new excise policy announced reduction in additional excise duty and MRP on beer, which will bring down its price by Rs 30-35. Also, there will be no more Covid surcharge on all excise items, except Indian-made foreign liquor (IMFL) and bottled-in-origin (BIO) or imported liquor.');
$ret = $html->find('img', 0);
$src = ltrim($ret->src, '\"');
$src = rtrim($src, '\"');
echo $src;

