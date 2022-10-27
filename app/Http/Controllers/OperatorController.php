<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Queues;
use App\Agent;
use DB;
use App\Helpers;
use App\QueuesDetails;

class OperatorController extends Controller
{

	/** action  */
	public function actions(Request $request)
	{
		$method = $request->method;
		switch ($method) {
			case 'Operator_getData':
				$res  = $this->Operator_getData($request);
				break;
			case 'Operator_getAllReport':
				$res  = $this->Operator_getAllReport($request);
				break;

			default:
				$res = [
					'status' => 500,
					'message' => 'no command found'
				];
		}
		return response()->json($res, $res['status']);
	}

	public function Operator_getData($request)
	{
		try {
			$details = $this->getDetail($request);
			// مدیریت تماس توسط کارشناس
			$disposition = $this->getDisposition($request);
			// جزئیات وقفه
			$agentAvailability = $this->getAgentAvailability($request);


			/** get first and last time */
			$time = $this->getFirstAndLastTime($request);

			return [
				'status' => 200,
				'message' => 'success',
				'disposition' => $disposition,
				'agentAvailability' => $agentAvailability,
				'details' => $details,
				'time' => $time
			];
		} catch (\Throwable $th) {
			return [
				'status' => 500,
				'message' => 'faild',
				'error' => $th->getMessage()
			];
		}
	}
	/** get detail for fit row one in component Operator */
	public function getDetail($request)
	{
		try {
			$detail = DB::table('queue_stats')
				->select('agent')
				->groupBy('agent')
				->whereIn('agent', $request->agents)
				->whereIn('queuename', $request->queues);
			// ->whereIn('event', ['COMPLETEAGENT', 'COMPLETECALLER']);


			/** get by date time */
			if ($request->timeFilter || ($request->fromFilter && $request->toFilter)) {
				$time = calcTime($request);
				$detail = $detail->whereBetween('time', $time);
			}

			return $detail->get();
		} catch (\Throwable $th) {
			return [];
		}
	}

	/** get time first and last */
	public function getFirstAndLastTime($request)
	{
		try {
			$time = DB::table('queue_stats')
				->select(DB::raw('MAX(time) as lastTime'), DB::raw('MIN(time) as firstTime'))
				->whereIn('agent', $request->agents)
				->whereIn('queuename', $request->queues)->first();

			/** get by date time */
			if ($request->timeFilter || ($request->fromFilter && $request->toFilter)) {
				$getTime = calcTime($request);
				$time = ['firstTime' => $getTime[0], 'lastTime' => $getTime[1]];
			}

			return $time;
		} catch (\Throwable $th) {
			return [];
		}
	}
	//--------------- Start  مدیریت تماس توسط کارشناس---------------
	public function getDisposition($request)
	{
		try {
			$completeAgent = $this->CalculationDisposition($request, ['COMPLETEAGENT'], 'CompleteAgent');
			$completeCaller = $this->CalculationDisposition($request, ['COMPLETECALLER'], 'CompleteCaller');
			$transfer = $this->CalculationDisposition($request, ['TRANSFER'], 'Transfer');


			return [
				'completeAgent' => $completeAgent,
				'completeCaller' => $completeCaller,
				'transfer' => $transfer
			];
		} catch (\Throwable $th) {
			return [];
		}
	}

	public function CalculationDisposition($request, $event, $name)
	{
		try {
			$calc = DB::table('queue_stats')
				->select('time', 'agent', DB::raw('DATE(time) as date'), DB::raw("COUNT(agent) as count$name"), DB::raw("ROUND(AVG(data1),2) as data1$name"), DB::raw("ROUND(AVG(data2),2) as data2$name"), DB::raw("SUM(data3) as data3$name"))
				->groupBy('agent')
				->whereIn('agent', $request->agents)
				->whereIn('queuename', $request->queues)
				->whereIn('event', $event);


			/** get by date time */
			if ($request->timeFilter || ($request->fromFilter && $request->toFilter)) {
				$time = calcTime($request);
				$calc = $calc->whereBetween('time', $time);
			}
			return $calc->get();
		} catch (\Throwable $th) {
			return null;
		}
	}
	//--------------- End  مدیریت تماس توسط کارشناس---------------

	//--------------- Start جزئیات وقفه---------------
	public function getAgentAvailability($request)
	{
		try {
			/** CalculationAgentAvailability param 
			 * @param 1 => _REQUEST
			 * @param 2 => array of event
			 * @param 3 => string name . use for name of feild
			 * @param 4 => array ['key','value] for where. default false
			 * @param 5 => arry name of feilds for groupBy. default 'agent'
			 */
			$completeAgent = $this->CalculationAgentAvailability($request, ['COMPLETEAGENT', 'COMPLETECALLER'], 'Answered');
			$ringUnAnswered = $this->CalculationAgentAvailability($request, ['RINGNOANSWER'], 'RingUnAnswered', ['data1', 'RingUnAnswered']);
			$reject = $this->CalculationAgentAvailability($request, ['RINGNOANSWER'], 'Reject', ['data1', 'reject']);
			$session = $this->CalculationAgentAvailability($request, ['ADDMEMBER', 'REMOVEMEMBER'], 'Session', ['callid', 'MANAGER'], false);
			$puse = $this->CalculationAgentAvailability($request, ['PAUSE', 'UNPAUSE', 'PAUSEALL', 'UNPAUSEALL'], 'Puse', false, false);

			return [
				'completeAgent' => $completeAgent,
				'ringUnAnswered' => $ringUnAnswered,
				'reject' => $reject,
				'session' => $session,
				'puse' => $puse,
			];
		} catch (\Throwable $th) {
			return [];
		}
	}
	/** method calc puse detail 
	 * @param 1 => _REQUEST
	 * @param 2 => array of event
	 * @param 3 => string name . use for name of feild
	 * @param 4 => array ['key','value] for where. default false
	 * @param 5 => string name of feild for groupBy. default 'agent'
	 */
	public function CalculationAgentAvailability($request, $event, $name, $where = false, $groupBy = 'agent')
	{
		try {
			if ($groupBy) {
				$data = DB::table('queue_stats')
					->select('time', 'agent', DB::raw('DATE(time) as date'), DB::raw("COUNT(agent) as count$name"), DB::raw("SUM((CAST(data1  AS UNSIGNED)))  as data1$name"), DB::raw("SUM((CAST(data2  AS UNSIGNED))) as data2$name"))
					->whereIn('agent', $request->agents)
					->whereIn('queuename', $request->queues)
					->whereIn('event', $event)
					->groupBy($groupBy);;
			} else {
				$data = DB::table('queue_stats')
					->select('event', 'queuename', 'time', 'agent', DB::raw('concat(DATE(time),agent) as mergeField'))
					->whereIn('agent', $request->agents)
					->whereIn('queuename', $request->queues)
					->whereIn('event', $event)
					->orderBy('time');
			}


			/** where
			 * if where == reject then get all time reject for any queue and use
			 * else use time < 15000 for get data
			 *  */
			if ($where) {
				if ($where[1] == 'reject') {
					$query = QueuesDetails::where('keyword', 'timeout')
						->whereIn('id', $request->queues)
						->groupBy('data')
						->get(['data']);
					$whereField = [];
					foreach ($query as $item) {
						// whereField Cnvert To Secend
						$whereField[] = $item->data * 1000;
					}

					$data = $data->whereIn('data1', $whereField);
				} else if ($where[1] == 'RingUnAnswered')
					$data = $data->where(DB::raw('CAST(data1 AS int)'), '<', 15000);
				else
					$data = $data->where($where[0], $where[1]);
			}

			/** get by date time */
			if ($request->timeFilter || ($request->fromFilter && $request->toFilter)) {
				$time = calcTime($request);
				$data = $data->whereBetween('time', $time);
			}
			return $data->get();
		} catch (\Throwable $th) {
			return null;
		}
	}
	//--------------- End جزئیات وقفه---------------


	/**----------- Start گزارش کامل کارشناس ----------*/

	public function Operator_getAllReport($request)
	{
		try {
			$report = DB::table('queue_stats')
				->select('time', 'queuename', 'agent', "data1", "data2", 'event', 'callid')
				->whereIn('agent', $request->agents)
				->whereIn('event', ['CONNECT', 'COMPLETECALLER', 'COMPLETEAGENT'])
				->whereIn('queuename', $request->queues);


			/** get by date time */
			if ($request->timeFilter || ($request->fromFilter && $request->toFilter)) {
				$time = calcTime($request);
				$report = $report->whereBetween('time', $time);
			}

			$report = $report->get();



			/** get mobile via callid */
			$callid = [];
			foreach ($report as $item) {
				$callid[] = $item->callid;
			}
			$mobile = DB::table('queue_stats')
				->select(DB::raw("data2 as phone"), 'callid')
				->whereIn('callid', $callid)
				->where('event', 'ENTERQUEUE');

			return [
				'status' => 200,
				'message' => 'success',
				'data' => $report,
				'mobile' => $mobile->get()

			];
		} catch (\Throwable $th) {
			return null;
		}
	}
	/**----------- End گزارش کامل کارشناس ----------*/
}
