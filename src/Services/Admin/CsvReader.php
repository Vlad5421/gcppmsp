<?php

namespace App\Services\Admin;

use Symfony\Component\HttpKernel\KernelInterface;

class CsvReader
{
    private KernelInterface $appKernel;
    private array $strings;

    public function __construct(KernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
    }

    /**
     * @return array
     */
    public function getStrings(): array
    {
        return $this->strings;
    }


    public function setStrings(string $file)
    {
        $this->strings = [];
        $strs = @fopen($this->appKernel->getProjectDir(). $file, "r");

        if ($strs) {
            while (($buffer = fgets($strs)) !== false) {
                $buffer = explode(";", trim($buffer, " \n\r\t\v\x00"));
                $this->strings[] = $buffer;
            }
            if (!feof($strs)) {
                echo "Ошибка: fgets() неожиданно потерпел неудачу\n";
            }
            fclose($strs);
        }
        return $this;
    }

    public function getUsers()
    {
        foreach ($this->strings as $string){
            $element = [];
            $element["FIO"] = trim($string[0], ", \n\r\t\v\x00");
            $element["filial"] = intval($string[1]);
            for ($i = 1; $i < 8; $i++){
                if (isset($string[$i+1]) && $string[$i+1] != ""){
                    $element[$i] = $this->getTimeSegments($string[$i+1]);
                }
            }
//            dump($element);
            $users[] = $element;
        }

        return $users;
    }

    private function getTimeSegments(string $string): array
    {
        $work_segs = explode(" ", trim($string, " \n\r\t\v\x00"));
        $segments = [];

        foreach ($work_segs as $work_seg) {
            if (!$work_seg == ""){
                $segs = explode("-", trim($work_seg, " \n\r\t\v\x00"));
                $work_seg = $this->timeModular($segs[0]) . "-" . $this->timeModular($segs[1]);
                $segments[] = $work_seg;
            }
        }
        return $segments;
    }

    private function timeModular(string $time, string $separator = "."): int
    {
        $times = explode($separator, trim($time, " \n\r\t\v\x00"));
        return intval($times[0]) * 60 + intval($times[1]);
    }

    public function getFilials(): array
    {
        $filials = [];
        foreach ($this->strings as $string){
            $filials[$string[0]] = [
                $string[1],
                $string[2]
            ];
        }

        return $filials;
    }

}