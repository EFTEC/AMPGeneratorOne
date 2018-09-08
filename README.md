# AMPGeneratorOne

It generates Google AMP sites using PHP

This application simplifies (via code) the generation of an AMP site.  It's easiest to program, just a few of lines of code and that's it.

For example, let's say that you want to add a new section
```php 
$secImage= new SectionModel("The Cupcakes","","/image.jpg");
$secImage->buttons[]=new ButtonModel("More Information","#");
$secImage->buttons[]=new ButtonModel("More Information","#","warning");

$amp->sectionImage($secImage,2250,441);
```
It will generate the next visual:

![simple section](doc/simplesection.jpg)

## How it works?

```php 
use eftec\AmpGeneratorOne\AmpGeneratorOne;
use eftec\AmpGeneratorOne\ButtonModel;
use eftec\AmpGeneratorOne\FooterModel;
use eftec\AmpGeneratorOne\HeaderModel;
use eftec\AmpGeneratorOne\HeadModel;
use eftec\AmpGeneratorOne\LinkModel;
use eftec\AmpGeneratorOne\SectionModel;

include "lib/AmpGeneratorOne.php";
$amp=new AmpGeneratorOne("https://www.canonical.com","https://www.canonical.com/amp");
// # 
$amp->startAmp(new HeaderModel("description","title","favicon.ico"));

// # sidebar (optional)
$amp->sidebar($menu); // $menu is an array of /LinkModel()

// #1 head (the top bar)
$amp->head(new HeadModel("",$base."logo.png"),70,70);

// # example section
$amp->sectionFirst(new SectionModel("Title","Description"));

// # footer
$amp->sectionFooter(new FooterModel("Copyright something(c)","See as desktop"));

amp->render(); // you also could generate a file.
```

![result](doc/example1.jpg)

Tablet version

![result](doc/example1-sm.jpg)

Mobile version

![result](doc/example1-sm(open).jpg)

Mobile version (slider open)


> Note: You could change the color. **!**

## Validity

You could validate your amp on [Google search validation](https://search.google.com/test/amp)

![Google Amp Validation](doc/validate.jpg)

## Version

* 1.0 2018-09-08 First non beta version
* 0.3 2018-09-07 Cleaning the house.
* 0.2 2018-09-06 Working version.
* 0.1 2018-08-20 First prototype


## Example demo:
             
[See end result](http://htmlpreview.github.io/?https://raw.githubusercontent.com/EFTEC/AMPGeneratorOne/master/example/example-generated.html)

![Full Project](doc/fullproject.png)