<?php namespace Yfktn\Yfktnutil\Classes;

trait TraitQueryPeriod
{

    protected function applyPeriodFilter(&$query, $periodField, $startDate, $endDate)
    {
        if(!empty($startDate) && !empty($endDate)) {
            $query->whereBetween($periodField, [$startDate, $endDate]);
        } else if(!empty($startDate)) {
            $query->where($periodField, '>=', $startDate);
        } else if(!empty($endDate)) {
            $query->where($periodField, '<=', $endDate);
        }
    }

    protected function getPeriodFilterSql($periodField, $startDate, $endDate, &$params)
    {
        $querySQLString = '';
        if(!empty($startDate) && !empty($endDate)) {
            $querySQLString .= " $periodField BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
        } else if(!empty($startDate)) {
            $querySQLString .= " $periodField >= ?";
            $params[] = $startDate;
        } else if(!empty($endDate)) {
            $querySQLString .= " $periodField <= ?";
            $params[] = $endDate;
        }
        return $querySQLString;
    }
}