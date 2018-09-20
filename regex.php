<?php 
echo '<pre>';
$string = '
<text:p text:style-name="P10"/>
<text:p text:style-name="P9">
    &lt;@133 - endereco @&gt;

    Digite o nome do Proprietário
    &lt;@133 - Proprietário - 
        <text:span> 
            <text:s text:style-name="T2" />
            <text:s> CPF/CNPJ </text:s> 
            <text:u>
            33 @&gt;
            </text:u> 
        </text:span>
    Aqui é depois do nome do proprietario

    Digite a data do sistema &lt;@ data-do-sistema <text:u>@&gt; </text:u> que vai ser
</text:p>
<text:p text:style-name="P9">
<text:span text:style-name="T4">&lt;@PE DATA DA INATIVAÇÃO - </text:span><text:span text:style-name="T6">DESOCUPAÇÃO</text:span><text:span text:style-name="T4"> PELO LOCATARIO @&gt;
</text:p>';

echo $string;

echo "\n\n ######################################## \n\n";

preg_match_all('/\&lt;@([^@]+)@\&gt;/', $string, $matches);

foreach ( $matches[1] as $variavel) {

    $striptag = preg_replace("/<text:([^>]+)\>([^<]+)<\/text:([^>]+)>/",'$2', $variavel);
    $string = str_replace($variavel, $striptag, $string);

    $closetag = preg_replace("/<text:([^\/|^<]+)\/\>/", '', $striptag);
    $string = str_replace($striptag, $closetag, $string);

}
echo "\n\n ######################################## \n\n";


preg_match_all('/\&lt;@([^@]+)@\&gt;/', $string, $matches);

$variavel = null;

foreach ( $matches[1] as $variavel) {
    $replaceopentags = [];
    $replaceclosetags = [];

    preg_match_all("/<text:([^>]+)>/",$variavel, $matcs);
    foreach ($matcs[1] as $tag) {
        $replaceopentags[] = "<text:{$tag}>";
    }
    $openvariavel = str_replace($replaceopentags, '', $variavel);
    $openvariavel = str_replace(['-','\/'],['_','_'], $openvariavel);
    $openvariavel = preg_replace('/\s\s+/', '_', $openvariavel);
    $closetags  = implode('',$replaceopentags);

    preg_match_all("/<\/text:([^>]+)>/",$openvariavel, $matcs);
    foreach ($matcs[1] as $tag) {
        $replaceclosetags[] = "</text:{$tag}>";
    }
    $closevariavel = str_replace($replaceclosetags, '', $openvariavel);
    $opentags  = implode('',$replaceclosetags);

    $string = str_replace($variavel, $opentags."#%".$closevariavel."%#".$closetags, $string);
}


$string = str_replace("&lt;@",'',$string);
$string = str_replace("@&gt;",'',$string);

$string = str_replace("#%","&lt;@",$string);
$string = str_replace("%#","@&gt;",$string);

echo $string;