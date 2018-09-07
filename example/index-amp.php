<?php

use eftec\AmpGeneratorOne\AmpGeneratorOne;
use eftec\AmpGeneratorOne\ButtonModel;
use eftec\AmpGeneratorOne\FooterModel;
use eftec\AmpGeneratorOne\HeaderModel;
use eftec\AmpGeneratorOne\HeadModel;
use eftec\AmpGeneratorOne\LinkModel;
use eftec\AmpGeneratorOne\SectionModel;

// resources
$ampDesc="While this example could run in a regular browser but it must be tested under an AMP-compatible browser (firefox has a plugin)";
$cupcakes_long_desc="The earliest extant description of what is now often called a cupcake was in 1796, when a recipe for \"a light cake to bake in small cups\" was written in American Cookery by Amelia Simmons. The earliest extant documentation of the term cupcake itself was in \"Seventy-five Receipts for Pastry, Cakes, and Sweetmeats\" in 1828 in Eliza Leslie's Receipts cookbook.<br>
In the early 19th century, there were two different uses for the term cup cake or cupcake. In previous centuries, before muffin tins were widely available, the cakes were often baked in individual pottery cups, ramekins, or molds and took their name from the cups they were baked in. This is the use of the name that has remained, and the name of \"cupcake\" is now given to any small, round cake that is about the size of a teacup. While English fairy cakes vary in size more than American cupcake, they are traditionally smaller and are rarely topped with elaborate icing.<br>
The other kind of \"cup cake\" referred to a cake whose ingredients were measured by volume, using a standard-sized cup, instead of being weighed. Recipes whose ingredients were measured using a standard-sized cup could also be baked in cups; however, they were more commonly baked in tins as layers or loaves. In later years, when the use of volume measurements was firmly established in home kitchens, these recipes became known as 1234 cakes or quarter cakes, so called because they are made up of four ingredients: one cup of butter, two cups of sugar, three cups of flour, and four eggs.[5][6] They are plain yellow cakes, somewhat less rich and less expensive than pound cake, due to using about half as much butter and eggs compared to pound cake.<br>";

$cupcakes_med_desc="The earliest extant description of what is now often called a cupcake was in 1796, when a recipe for \"a light cake to bake in small cups\" was written in American Cookery by Amelia Simmons. The earliest extant documentation of the term cupcake itself was in \"Seventy-five Receipts for Pastry, Cakes, and Sweetmeats\" in 1828 in Eliza Leslie's Receipts cookbook.<br>
In the early 19th century, there were two different uses for the term cup cake or cupcake. In previous centuries, before muffin tins were widely available, the cakes were often baked in individual pottery cups, ramekins, or molds and took their name from the cups they were baked in. This is the use of the name that has remained, and the name of \"cupcake\" is now given to any small, round cake that is about the size of a teacup. While English fairy cakes vary in size more than American cupcake, they are traditionally smaller and are rarely topped with elaborate icing.<br>";

$cupcakes_short_desc="The earliest extant description of what is now often called a cupcake was in 1796, when a recipe for \"a light cake to bake in small cups\" was written in American Cookery by Amelia Simmons. The earliest extant documentation of the term cupcake itself was in \"Seventy-five Receipts for Pastry, Cakes, and Sweetmeats\" ";

$links=array("Cupcakes","Cake","Muffin","Coffee");

// example
include "../lib/AmpGeneratorOne.php";

$base = dirname('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']); // this operation is not safe, it's only an example


$amp=new AmpGeneratorOne("https://www.canonical.com",$base,"#4040ff","#197bc6","text-white");
$amp->setDefault("white","text-black");
// #header
$amp->header(new HeaderModel($ampDesc,"AMP Generator One",$base."/resources/favicon.ico"));
// #analitics
$amp->sectionAnalytics("UA-xxxxxx-1");
// #sidebera
$menu=array();
foreach($links as $link) {
    $menu[] = new LinkModel($link, "https://wwww.canonical.com/id/".$link, "");

}
$amp->sidebar($menu);
// #1 head
$amp->backgroundColor("white")->head(new HeadModel("",$base."/resources/logo/logo.png"),70,70);
$main=new SectionModel("AMP Generator One",$ampDesc,$base."/resources/pexels-photo-532126.jpeg");
$main->buttons[]=new ButtonModel("More Information","#");
$main->buttons[]=new ButtonModel("More Information","#","danger");
$amp->backgroundColor("rgb(0, 174, 239)")->classTextColor("text-white")
    ->sectionHeaderCentral($main);
// section text
$secText=new SectionModel("The Cupcake",$cupcakes_short_desc);
$amp->sectionText($secText);
// # section image
$secImage= new SectionModel("The Cupcakes",$cupcakes_short_desc,$base."/resources/banner_2250x441.jpg");
$secImage->buttons[]=new ButtonModel("More Information","#");
$secImage->buttons[]=new ButtonModel("More Information","#","warning");
$amp->setPadding(0,0)->sectionImage($secImage,2250,441);

// # Image and content
$secImgCont=new SectionModel("The Cupcakes",$cupcakes_med_desc,$base."/resources/pexels-photo-1073767.jpeg");
$secImgCont->buttons[]=new ButtonModel("More Information","#"); // only the first button is displayed.
$amp->sectionImageContent($secImgCont,300,300);
$secImgCont=new SectionModel("The Cupcakes",$cupcakes_med_desc,$base."/resources/pexels-photo-1073767.jpeg");
$secImgCont->buttons[]=new ButtonModel("More Information","#"); // only the first button is displayed.
$amp->sectionImageContentLeft($secImgCont,300,300);
// # table
$prod=array("Product"=>"Cupcake","Price"=>200);
$products=array($prod,$prod,$prod); // it also works with list of objects
$amp->sectionTable($products);
// # raw
$amp->sectionRaw("<pre>Note:Google map musn't be at the top of the page. It's an amp restriction</pre>");
// # map boxed
$map=new SectionModel("Title","Desc");
$amp->sectionGMapBoxed($map,"https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d13320.170086624597!2d-70.60383335!3d-33.422135749999995!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2scl!4v1536022894740");
// # map full
$map=new SectionModel("Title","Desc");
$amp->sectionGMapFull($map,"https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d13320.170086624597!2d-70.60383335!3d-33.422135749999995!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2scl!4v1536022894740");
// # images
$sectImages=[];
$sectImages[]=new SectionModel("Cupcake",$cupcakes_short_desc,"/resources/cupcake_square.jpg");

$sectImages[]=new SectionModel("Cupcake",$cupcakes_short_desc,"/resources/cupcake_square.jpg");
$sectImages[]=new SectionModel("Cupcake",$cupcakes_short_desc,"/resources/cupcake_square.jpg");
$sectImages[]=new SectionModel("Cupcake",$cupcakes_short_desc,"/resources/cupcake_square.jpg");
$sectImages[]=new SectionModel("Cupcake",$cupcakes_short_desc,"/resources/cupcake_square.jpg");
$sectImages[]=new SectionModel("Cupcake",$cupcakes_short_desc,"/resources/cupcake_square.jpg");
$sectImages[]=new SectionModel("Cupcake",$cupcakes_short_desc,"/resources/cupcake_square.jpg");
$si=new SectionModel("Cupcake Link",$cupcakes_short_desc,"/resources/cupcake_square.jpg");
$si->url[]=new LinkModel("link","#");
$sectImages[]=$si;
$amp->sectionColImage($sectImages,400,400);
// #
$contents=[];
$col1=new SectionModel("title",$cupcakes_short_desc);
$col1->buttons[]=new ButtonModel("link","#");
$contents[]=$col1;
$contents[]=new SectionModel("title",$cupcakes_short_desc);
$amp->backgroundColor("#909090")->classTextColor("text-white")->sectionCols($contents);

// #5 footer
$amp->setPadding(0,0)
    ->sectionFooter(new FooterModel("Copyright something(c)","See as desktop"));

$amp->render(); // you could render to output
$amp->renderToFile("example-generated.html"); // or, you could render to file. PICK ONE