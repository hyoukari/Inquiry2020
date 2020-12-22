<?php   // page_num.php

function p($count)
{
    if (0 === $count) {
        $page = 0;
    } else {
        $page = ceil($count / 20);
    }
    echo "{$count} -> {$page} <br>\n";
}

//
p(0);
p(10);
p(20);
p(21);
p(30);
p(40);
p(41);
