<?php

namespace App\Modules\Core\Services;

use App\Modules\Core\Models\Setting;
use ImranDev\UnicodePdf\Facades\UnicodePdf;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class TabularExport
{
    /**
     * @param  list<string>  $headings
     * @param  list<list<string>>  $rows
     */
    public function xlsx(string $title, array $headings, array $rows): string
    {
        $path = tempnam(sys_get_temp_dir(), 'factory-xlsx-');
        $writer = new Writer;
        $writer->openToFile($path);
        $writer->addRow(Row::fromValues($headings));

        foreach ($rows as $row) {
            $writer->addRow(Row::fromValues($row));
        }

        $writer->close();
        $binary = (string) file_get_contents($path);
        @unlink($path);

        return $binary;
    }

    /**
     * @param  list<string>  $headings
     * @param  list<list<string>>  $rows
     */
    public function pdf(string $title, array $headings, array $rows): string
    {
        $html = view('pdf.register', [
            'title' => $title,
            'headings' => $headings,
            'rows' => $rows,
            'company' => Setting::getValue('company', 'name', config('factory.company.name')),
            'companyBn' => Setting::getValue('company', 'name_bn', config('factory.company.name_bn')),
        ])->render();

        return UnicodePdf::loadHtml($html)
            ->preset('bengali')
            ->locale('bn')
            ->setPaper('a4', 'landscape')
            ->output();
    }
}
