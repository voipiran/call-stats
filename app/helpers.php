<?php // Code within app\Helpers\Helper.php

/** general function for calc range date */
function calcTime($request)
{
    try {
        /** if select range date */
        if ($request->fromFilter && $request->toFilter) {
            return [$request->fromFilter, $request->toFilter];
        }
        /** if select shortcut */
        switch ($request->timeFilter['code']) {
            case 'today':
                /**today */
                $from = date('Y-m-d 00:00:00', time());
                $to = date('Y-m-d H:i:s', time());
                break;
            case 'yesterday':
                /**yesterday */
                $from = date('Y-m-d 00:00:00', strtotime('-1 days'));
                $to = date('Y-m-d 23:59:59', strtotime('-1 days'));
                break;
            case 'lastWeek':
                $from = date("Y-m-d 00:00:00", strtotime("-2 week saturday"));
                if (!date('w') || date('w') == 6) {
                    $from = date("Y-m-d 00:00:00", strtotime("-1 week saturday"));
                }
                $to = date("Y-m-d 23:59:59", strtotime("-1 week friday")); // // }

                break;
            case 'currentWeek':
                /**in week */
                $from = date("Y-m-d 00:00:00", strtotime("last saturday"));
                if (date('w') == 6) {
                    $from = date('Y-m-d 00:00:00', time());
                }
                $to = date("Y-m-d 23:59:59", strtotime('next friday'));
                break;
            case 'currentMonth':
                /**in month */
                $from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                $to = date('Y-m-d 23:59:59', time());
                break;
            case 'last3Month':
                $from = date("Y-m-d 00:00:00", strtotime("-3 month"));
                $to = date('Y-m-d 23:59:59', time());
                break;
            default:
                $from = null;
                $to = null;
                break;
        }

        return [$from, $to];
    } catch (\Throwable $th) {
        return null;
    }
}