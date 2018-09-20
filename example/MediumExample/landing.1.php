<?php

use eftec\AmpGeneratorOne\AmpGeneratorOne;
use eftec\AmpGeneratorOne\ButtonModel;
use eftec\AmpGeneratorOne\FooterModel;
use eftec\AmpGeneratorOne\HeaderModel;
use eftec\AmpGeneratorOne\HeadModel;
use eftec\AmpGeneratorOne\LinkModel;
use eftec\AmpGeneratorOne\SectionModel;
use eftec\AmpGeneratorOne\StructureModel;

include "../../lib/AmpGeneratorOne.php";

$canonical="https://www.canonical.com";
$base='http://localhost/currentproject/ampgeneratorone/example/MediumExample';

$amp=new AmpGeneratorOne($canonical,$base);


$amp->startAmp(new HeaderModel("Example","AMP Generator One"));

// sidebar menu
$menu=array();
$menu[]=new LinkModel("Cupcakes", $base."/detail.php?id=cupcake", "");
$menu[]=new LinkModel("Muffins", $base."/detail.php?id=muffin", "");
$amp->setClassTextColor("text-white")
    ->sidebar($menu);



$amp->head(new HeadModel("Cupcakes","/resources/logo/logo.png"),70,70);

$amp->render();