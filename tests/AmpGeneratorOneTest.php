<?php

namespace eftec\test;

use eftec\AmpGeneratorOne\AmpGeneratorOne;
use eftec\AmpGeneratorOne\FooterModel;
use eftec\AmpGeneratorOne\HeadModel;
use eftec\AmpGeneratorOne\SectionModel;
use PHPUnit\Framework\TestCase;


class AmpGeneratorOneTest extends TestCase
{
    var $ampGeneratorOne;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        //you could change it.
        $this->ampGeneratorOne = new AmpGeneratorOne("http://www.canon.dom","http://amp.canon.dom");
    }


    public function test___construct()
    {

    }

    public function test_fixRelativeUrl()
    {
        $this->assertEquals('http://amp.canon.dom/some/url/image.jpg',$this->ampGeneratorOne->fixRelativeUrl('some/url/image.jpg'));
    }

    public function test_startAmp()
    {

    }

    public function test_sidebar()
    {
        $this->ampGeneratorOne->sidebar([]);
    }

    public function test_head()
    {
        $this->ampGeneratorOne->head(new HeadModel(),0,0);   
    }

    public function test_sectionFooter()
    {
        $this->ampGeneratorOne->sectionFooter(new FooterModel('copyright'));
    }

    public function test_sectionAnalytics()
    {
        
        $this->ampGeneratorOne->sectionAnalytics('UA-XXXXX-Y');
    }

    public function test_sectionFirst()
    {
        $this->ampGeneratorOne->sectionFirst(new SectionModel());
    }

    public function test_sectionImageButton()
    {
        $this->ampGeneratorOne->sectionImageButton(new SectionModel(),false);
    }

    public function test_sectionText()
    {
        $this->ampGeneratorOne->sectionText(new SectionModel());
    }

    public function test_sectionTextQuote()
    {
        $this->ampGeneratorOne->sectionTextQuote(new SectionModel());
    }

    public function test_sectionUL()
    {
        $this->ampGeneratorOne->sectionUL([]);
    }

    public function test_sectionOL()
    {
        $this->ampGeneratorOne->sectionOL([]);
    }

    public function test_sectionButtons()
    {
        $this->ampGeneratorOne->sectionButtons(new SectionModel());
    }

    public function test_sectionCols()
    {
        $this->ampGeneratorOne->sectionCols([]);
    }

    public function test_sectionHeaderCentral()
    {
        $this->ampGeneratorOne->sectionHeaderCentral(new SectionModel());
    }

    public function test_sectionRaw()
    {

    }

    public function test_sectionImageContent()
    {
        $this->ampGeneratorOne->sectionImageContent(new SectionModel(),0,0);
    }

    public function test_sectionImageContentLeft()
    {
        $this->ampGeneratorOne->sectionImageContentLeft(new SectionModel(),0,0);
    }

    public function test_sectionTable()
    {
        $this->ampGeneratorOne->sectionTable([]);
    }

    public function test_sectionImage()
    {
        $this->ampGeneratorOne->sectionImage(new SectionModel(),0,0);
    }

    public function test_sectionGMapFull()
    {
        $this->ampGeneratorOne->sectionGMapFull(new SectionModel(),'mapurl','loading..');
    }

    public function test_sectionGMapBoxed()
    {
        $this->ampGeneratorOne->sectionGMapBoxed(new SectionModel(),'mapurl');
    }

    public function test_sectionNavigation()
    {
        $this->ampGeneratorOne->sectionNavigation(new SectionModel());
    }

    public function test_sectionColImage()
    {
        $this->ampGeneratorOne->sectionColImage([],0,0,0);
    }

    public function test_setBackgroundColor()
    {
        $this->assertInstanceOf(AmpGeneratorOne::class,$this->ampGeneratorOne->setBackgroundColor('#ffffff'));
    }

    public function test_setBgImage()
    {
        $this->assertInstanceOf(AmpGeneratorOne::class,$this->ampGeneratorOne->setBgImage('img.jpg'));
    }

    public function test_setClassTextColor()
    {
        $this->assertInstanceOf(AmpGeneratorOne::class,$this->ampGeneratorOne->setClassTextColor('text-primary'));
    }

    public function test_setPadding()
    {
        $this->assertInstanceOf(AmpGeneratorOne::class,$this->ampGeneratorOne->setPadding(0,0));
    }

    public function test_setDefault()
    {

    }

    public function test_render()
    {

    }

    public function test_renderToFile()
    {

    }
}