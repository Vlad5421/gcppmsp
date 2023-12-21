<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;

class CalendarMaker
{
//    /**
//     * @var Request|null $request
//     */
//    private $request;
    /**
     * @var string $date_string
     */
    public $date_string;
    /**
     * @var int $day_of_week
     */
    public $day_of_week;
    /**
     * @var int $start_month_day
     */
    public $start_month_day;
    /**
     * @var int $count_days
     */
    public $count_days;
    /**
     * @var int $count_rows
     */
    public $count_rows;
    /**
     * @var array $dayMatrix
     */
    public $dayMatrix;
    /**
     * @var array $date_in_array
     */
    public $date_in_array;
    /**
     * @var array $dateMatrix
     */
    public $dateMatrix;
    public bool $exluded_date = false;
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {

        $this->params = $params;
    }


    function  create($request): self
    {
        /**
         * @var Request $request
         */

        $this->request = $request;
        $date = new \DateTime('now', new \DateTimeZone('Asia/Omsk'));
//        dd($date);

        $exluded_dates = $this->params->get("exluded_dates");

        $this
            ->setDateStringAndArray($date, $this->request);
        if (in_array($this->date_string, $exluded_dates)){
           $this->exluded_date = true;
           return $this;
        }


        $this
            ->setDayOfWeek()
            ->setStartMonthDay($date)
            ->setCountDays()
            ->setDaysMatrix()
            ->setDateMatrix()
            ->setCountRowsToMonth();


        return $this;
    }

    protected function setDateStringAndArray($date, $request): self
    {


        $date = $date->format('d.m.Y');
        $date_array = explode('.', $date);
        if ($request->get('year'))
            $date_array[2] = $request->get('year');
        if ($request->get('month'))
            $date_array[1] = $request->get('month');
        if ($request->get('day'))
            $date_array[0] = $request->get('day');

        $this->date_in_array = ['year'=>$date_array[2], 'month'=>$date_array[1], 'day'=>$date_array[0]];

        $this->date_string =  implode('.', $date_array);
        return $this;
    }
    protected function setStartMonthDay($date): self
    {
        $date_str = $this->date_in_array['year'].'-'.$this->date_in_array['month'].'-1';
        $day_of_week = date("w", strtotime($date_str));
        $this->start_month_day = $day_of_week == 0 ? 7 : $day_of_week ;
        return $this;
    }


    protected function setCountDays(): self
    {
        $this->count_days = cal_days_in_month(CAL_GREGORIAN, $this->date_in_array['month'], $this->date_in_array['year']);
        return $this;
    }
    protected function setDaysMatrix(): self
    {
        $arr_before = array_fill(0, $this->start_month_day-1, '0');
        $local_dayMatrix = array_merge($arr_before, range(1, $this->count_days, 1) );

        foreach ($local_dayMatrix as $day){
            $this->dayMatrix[] = str_pad($day, 2, '0', STR_PAD_LEFT);
        }

        $arr_after = array_fill(count($this->dayMatrix), 7, '99');
        $this->dayMatrix = array_merge($this->dayMatrix, $arr_after );
        return $this;
    }
    protected function setDateMatrix(): self
    {
        foreach ($this->dayMatrix as $key => $el){
            if ($el == '0' || $el == '99'){
                $this->dateMatrix[$key]['date_full'] = '--';
                $this->dateMatrix[$key]['link'] = '--';
                $this->dateMatrix[$key]['date'] = '--';
            } else {
                $this->dateMatrix[$key]['date_full'] = $el .'.'. $this->date_in_array['month'] .'.'. $this->date_in_array['year'];
                $this->dateMatrix[$key]['date'] = $el .'.'. $this->date_in_array['month'];
                $this->dateMatrix[$key]['link'] = 'year='. $this->date_in_array['year'] .'&month='. $this->date_in_array['month'] .'&day='. $el;
            }

        }
        return $this;
    }

    protected function setCountRowsToMonth(): self
    {
        // Количество дней + количество дней из прошлого месяца в первой строке этого месца
        $count = $this->count_days + $this->start_month_day;
        //Возвращаем нужное количесво строк для отрисовки календаря
        $cel_rows = intdiv($count, 7);

        $this->count_rows = ($count % 7 == 0) ? $cel_rows : $cel_rows + 1;
        return $this;
    }

    private function setDayOfWeek()
    {
        $this->day_of_week = date('w', strtotime($this->date_string));
        return $this;
    }


}
