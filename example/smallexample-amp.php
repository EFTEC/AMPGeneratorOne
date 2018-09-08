<?php

use eftec\AmpGeneratorOne\AmpGeneratorOne;
use eftec\AmpGeneratorOne\FooterModel;
use eftec\AmpGeneratorOne\HeaderModel;
use eftec\AmpGeneratorOne\HeadModel;
use eftec\AmpGeneratorOne\LinkModel;
use eftec\AmpGeneratorOne\SectionModel;
// example
include "../lib/AmpGeneratorOne.php";

$base = dirname('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']); // this operation is not safe, it's only an example

$amp=new AmpGeneratorOne("https://www.canonical.com",$base,"#4040ff", "#197bc6","text-white");

// #header
$amp->startAmp(new HeaderModel("description","title",$base."/resources/favicon.ico"));

// #sidebar

$menu=array();
$links=array("Cupcakes","Cake","Muffin","Coffee");
foreach($links as $link) {
    $menu[] = new LinkModel($link, "https://wwww.canonical.com/id/".$link, "");

}
$amp->sidebar($menu);

// #1 head
$amp->head(new HeadModel("",$base."/resources/logo/logo.png"),70,70);

// #
$amp->setBackgroundColor("rgb(0, 174, 239)")->setClassTextColor("text-white")->setClassTextColor("text-white")->sectionFirst(new SectionModel("Title","description"));

// #5 footer
$amp->sectionFooter(new FooterModel("Copyright something(c)","See as desktop"));

$amp->render(); // you could render to output
$amp->renderToFile("smallexample-generated.html"); // or, you could render to file. PICK ONE