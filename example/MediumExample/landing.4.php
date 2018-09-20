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



$amp->head(new HeadModel("Cupcakes","/resources/logo/logo.png"),60,60);

// first section
$amp->setBgImage("resources/pexels-photo-585581.jpeg")
    ->setClassTextColor("text-black")
    ->sectionFirst(new SectionModel("Cupcakes"));

//second buttons
$sec2=new SectionModel();
$sec2->buttons[]=new ButtonModel("Button1","#");
$sec2->buttons[]=new ButtonModel("Button2","#","danger");
$amp->setBackgroundColor("white")
    ->sectionButtons($sec2);
// section text
$sec3=new SectionModel("Cupcakes","The earliest extant description of what is now often called a cupcake was in 1796");

$amp->setBackgroundColor("white")
    ->setClassTextColor("text-black")
    ->sectionText($sec3);

// section images
$images=[];
$images[]=new SectionModel("Cupcake","The earliest extant description of what is now often called a cupcake was in 1796","/resources/cupcake_square.jpg");
$images[]=new SectionModel("Cupcake","The earliest extant description of what is now often called a cupcake was in 1796","/resources/cupcake_square.jpg");
$images[]=new SectionModel("Cupcake","The earliest extant description of what is now often called a cupcake was in 1796","/resources/cupcake_square.jpg");
$amp->setBackgroundColor("white")
    ->setClassTextColor("text-black")
    ->sectionColImage($images,400,400,3);

// # navigation
$sbnav=new SectionModel("Cupcakes","The earliest extant description of what is now often called a cupcake was in 1796");
$sbnav->buttons[]=new ButtonModel("primary","#","primary");
$navCol1=[];
$navCol1[]=new LinkModel("Cupcakes","#","",true);
$navCol1[]=new LinkModel("Easy Little Pandas","#");
$navCol1[]=new LinkModel("Apple Pie Cupcakes","#");
$navCol1[]=new LinkModel("Chocolate","#");

$navCol2=[];
$navCol2[]=new LinkModel("Muffins","#","",true);
$navCol2[]=new LinkModel("Apple-Cinnamon","#");
$navCol2[]=new LinkModel("Banana","#");

$amp->setBackgroundColor("black")->setClassTextColor("text-white")->sectionNavigation($sbnav,"",0,0,$navCol1,$navCol2);


// #5 footer
$amp->setPadding(0,0)
    ->sectionFooter(new FooterModel("Copyright something(c)","See as desktop"));

$amp->render();