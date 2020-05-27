<html>
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<?php 

require_once 'vendor/autoload.php';

use \Statickidz\GoogleTranslate;

function showFiles($path){
    $dir = opendir($path);
    $files = array();
    while ($current = readdir($dir)){
        if( $current != "." && $current != "..") {
            if(is_dir($path.$current)) {
                showFiles($path.$current.'/');
            }
            else {
                $files[] = $current;
            }
        }
    }
    echo '<h2>'.$path.'</h2>';
    echo '<ul>';
    for($i=0; $i<count( $files ); $i++){
    	$content = file_get_contents($path."/".$files[$i]);

		$file = strip_tags($content);
		$file = str_replace('	', '_', $file);
		$file = str_replace('    ', '_', $file);

		$file = trim($file,"_");
		$file = explode("_", $file);
		
		$file = json_encode($file);

		$file = str_replace('\r\n', '_', $file);
		$file = str_replace('\n\n', '_', $file);

		$file = str_replace('""', '_', $file);
		$file = trim($file,"_");
		$file = explode("_", $file);

		$txt = "";

		foreach ($file as $index => $vas) {
			if(strpos($vas, 'Dear')){
				$txt = $vas.$file[$index + 1].$file[$index + 2];
				break;
			}
		}

		$txt = str_replace(',"', '_', $txt);
		$txt = str_replace('\n",', '_', $txt);

		$txt = trim($txt,"_");
		$txt = explode("_", $txt);

		
		$source = 'en';
		$target = 'es';
		$text = $txt[0];

		$trans = new GoogleTranslate();
		$result = $trans->translate($source, $target, $text);

		$res = '

<table width="800" border="0" cellspacing="0" cellpadding="10" class="main-message">
	<tr>
		<td valign="top" style="min-width: 550px; max-width: 800px;"><pre style="max-width:800px;white-space: pre-wrap;">'.$result.'</pre></td>
		<td valign="top" style="max-width: 250px">
        <br><br><br>
    		</td>
	</tr>
</table>

		';

    	file_put_contents("salida/".$files[$i], $content.$res);

        echo '<li><a target="_blank" href="salida/'.$files[$i].'"> '.$files[$i]. '</a></li>';
    }
    echo '</ul>';
}

showFiles('./entrada/');

	 ?>
</body>
</html>