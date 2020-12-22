<?php   // fin.php
// バッファ終了
ob_end_flush();

// 出力
echo $twig->render($template_file_name, $context);
