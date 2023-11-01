<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use \Denisok94\SymfonyExportXlsxBundle\Service\XlsxService;
use Symfony\Component\Routing\Annotation\Route;

class ExportController extends AbstractController
{
    /** @var XlsxService */
    private $export;
    /**
     * @param XlsxService $export
     */
    public function __construct(XlsxService $export)
    {
        $this->export = $export;
    }
    #[Route(path: '/xlsx', name: 'app_xlsx')]
    public function index(): Response
    {
        $fileName = 'my_first_excel.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $this->export->setFile($temp_file)->open();
        $test = [
            ['header1' => 'value1', 'header2' => 'value2', 'header3' => 'value3'],
            ['header1' => 'value4', 'header2' => 'value5', 'header3' => 'value6']
        ];
        foreach ($test as $line) {
            $this->export->write($line);
        }
        $this->export->close();
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}