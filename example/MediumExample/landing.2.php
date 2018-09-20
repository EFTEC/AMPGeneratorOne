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

$amp=new AmpGeneratorOne($canonical,$base,"#ECE9C7","white","text-black");
$amp->setDefault("#FC9D9A","text-white");

$amp->startAmp(new HeaderModel("Example","AMP Generator One"));

// sidebar menu
$menu=array();
$menu[]=new LinkModel("Cupcakes", $base."/detail.php?id=cupcake", "");
$menu[]=new LinkModel("Muffins", $base."/detail.php?id=muffin", "");
$amp->setClassTextColor("text-white")
    ->sidebar($menu);



$amp->head(new HeadModel("Cupcakes","/resources/logo/logo.png"),60,70);

$amp->setBackgroundColor("#F9CDAD")
    ->sectionFirst(new SectionModel("Cupcakes"));

$amp->setBgImage("resources/pexels-photo-585581.jpeg")
    ->setClassTextColor("text-black")
    ->sectionFirst(new SectionModel("Cupcakes"));


$amp->render();