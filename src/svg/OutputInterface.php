<?php
namespace nstdio\svg;


interface OutputInterface
{
    public function asSVG();

    public function asSVGZ();

    public function asFile($name);

    public function asForceDownload();

    public function asObject();

    public function asEmbed();

    public function asIFrame();

    public function asDataUriUTF8();


}