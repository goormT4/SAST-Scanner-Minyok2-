<?php

namespace WappoVendor\Illuminate\Http;

use WappoVendor\Symfony\Component\HttpFoundation\File\File as SymfonyFile;
class File extends SymfonyFile
{
    use FileHelpers;
}
