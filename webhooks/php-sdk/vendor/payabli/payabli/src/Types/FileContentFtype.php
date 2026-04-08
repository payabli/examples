<?php

namespace Payabli\Types;

enum FileContentFtype: string
{
    case Pdf = "pdf";
    case Doc = "doc";
    case Docx = "docx";
    case Jpg = "jpg";
    case Jpeg = "jpeg";
    case Png = "png";
    case Gif = "gif";
    case Txt = "txt";
}
