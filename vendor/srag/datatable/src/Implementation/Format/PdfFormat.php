<?php

namespace srag\DataTableUI\SrSelfDeclaration\Implementation\Format;

use ilHtmlToPdfTransformerFactory;
use srag\DataTableUI\SrSelfDeclaration\Component\Table;

/**
 * Class PdfFormat
 *
 * @package srag\DataTableUI\SrSelfDeclaration\Implementation\Format
 */
class PdfFormat extends HtmlFormat
{

    /**
     * @inheritDoc
     */
    public function getFormatId() : string
    {
        return self::FORMAT_PDF;
    }


    /**
     * @inheritDoc
     */
    protected function getFileExtension() : string
    {
        return "pdf";
    }


    /**
     * @inheritDoc
     */
    protected function renderTemplate(Table $component) : string
    {
        $html = parent::renderTemplate($component);

        $pdf = new ilHtmlToPdfTransformerFactory();

        $tmp_file = $pdf->deliverPDFFromHTMLString($html, "", ilHtmlToPdfTransformerFactory::PDF_OUTPUT_FILE, self::class, $component->getTableId());

        $data = file_get_contents($tmp_file);

        unlink($tmp_file);

        return $data;
    }
}
