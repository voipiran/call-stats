<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Queues;
use App\Agent;
use DB;
use App\Helpers;
use App\QueuesDetails;

class UnAnsweredController extends Controller
{
	/** action  */
	public function actions(Request $request)
	{
		$method = $request->method;
		switch ($method) {
			case 'unAnswered_getData':
				$res  = $this->unAnswered_getData($request);
				break;
			default:
				$res = [
					'status' => 500,
					'message' => 'no command found'
				];
		}
		return response()->json($res, $res['status']);
	}

	public function unAnswered_getData($request)
	{
		try {
			/** جزئیات*/
			$details = $this->getDetail($request);

			// <!-- تماس های پاسخ داده نده در صف -->
			$qUnAnswered = $this->getQueueUnAnswered($request);

			// دلیل قطع شدن مکالمه
			$hangUp = $this->getHangUpState($request);

			return [
				'status' => 200,
				'message' => 'success',
				'details' => $details,
				'hangUp' => $hangUp,
				'qUnAnswered' => $qUnAnswered

			];
		} catch (\Throwable $th) {
			return [
				'status' => 500,
				'message' => 'faild',
				'error' => $th->getMessage()
			];
		}
	}

	/** get detail for fit row one in component answered */
	public function getDetail($request)
	{
		try {
			$detail = DB::table('queue_stats')
				->select(DB::raw('COUNT(agent) as count'), DB::raw('SUM(data1) as time'), DB::raw('SUM(data3) as delay'))
				->whereIn('agent', $request->agents)
				->whereIn('queuename', $request->queues)
				/** 1 and 13 is event ABANDON */
				->whereIn('event', ['RINGCANCELED', 'EXITWITHTIMEOUT', 'EXITWITHKEY']);

			/** get by date time */
			if ($request->timeFilter || ($request->fromFilter && $request->toFilter)) {
				$time = calcTime($request);
				$detail = $detail->whereBetween('time', $time);
			}


			/** شروع محاسبه میانگین نوبت هنگام ورود به صف */
			$getCallId = DB::table('queue_stats')
				->select('callid')
				->whereIn('agent', $request->agents)
				->whereIn('queuename', $request->queues)
				/** 1 and 13 is event ABANDON */
				->whereIn('event', ['RINGCANCELED', 'EXITWITHTIMEOUT', 'EXITWITHKEY']);

			/** get by date time */
			if ($request->timeFilter || ($request->fromFilter && $request->toFilter)) {
				$getCallId = $getCallId->whereBetween('time', $time);
			}


			$getCallId = $getCallId->get();
			$callId = [];
			foreach ($getCallId as $item) {
				$callId[] = $item->callid;
			}


			$numInQueue = DB::table('queue_stats')
				->select(DB::raw('AVG(data3) as numInQueue'))
				->whereIn('callid', $callId)
				->whereIn('event', ['ENTERQUEUE']);

			/** get by date time */
			if ($request->timeFilter || ($request->fromFilter && $request->toFilter)) {

				$numInQueue = $numInQueue->whereBetween('time', $time);
			}



			/** پایان محاسبه میانگین نوبت هنگام ورود به صف */

			return [
				'numInQueue' => $numInQueue->first(),
				'detail' => $detail->first()
			];
		} catch (\Throwable $th) {
			return null;
		}
	}


	// <!-- تماس های بدون پاسخ در صف -->
	public function getQueueUnAnswered($request)
	{
		try {
			$qAnswered = DB::table('queue_stats')
				->select('queuename as lable', DB::raw('COUNT(data2) as count'))
				->groupBy('queuename')
				->whereIn('queuename', $request->queues)
				->whereIn('agent', $request->agents)
				/** 1 and 13 is event ABANDON */
				->whereIn('event', ['RINGCANCELED', 'EXITWITHTIMEOUT', 'EXITWITHKEY']);

			/** get by date time */
			if ($request->timeFilter || ($request->fromFilter && $request->toFilter)) {
				$time = calcTime($request);
				$qAnswered = $qAnswered->whereBetween('time', $time);
			}

			return $qAnswered->get();
		} catch (\Throwable $th) {
			return [];
		}
	}
	// دلیل قطع شدن مکالمه
	public function getHangUpState($request)
	{
		try {
			/** hang uo by RINGCANCELED */
			$RINGCANCELED = DB::table('queue_stats')
				->select(DB::raw('COUNT(data2) as count'))
				->whereIn('queuename', $request->queues)
				->whereIn('agent', $request->agents)
				/** 1 and 13 is event ABANDON */
				->where('event', 'RINGCANCELED');


			/** get by date time */
			if ($request->timeFilter || ($request->fromFilter && $request->toFilter)) {
				$time = calcTime($request);
				$RINGCANCELED = $RINGCANCELED->whereBetween('time', $time);
			}



			/** hang uo by EXITWITHTIMEOUT */
			$EXITWITHTIMEOUT = DB::table('queue_stats')
				->select(DB::raw('COUNT(*) as count'))
				->whereIn('queuename', $request->queues)
				->whereIn('agent', $request->agents)
				/** 1 and 13 is event ABANDON */
				->where('event', 'EXITWITHTIMEOUT');

			/** get by date time */
			if ($request->timeFilter || ($request->fromFilter && $request->toFilter)) {
				$time = calcTime($request);
				$EXITWITHTIMEOUT = $EXITWITHTIMEOUT->whereBetween('time', $time);
			}


			/** hang uo by EXITWITHKEY */
			$EXITWITHKEY = DB::table('queue_stats')
				->select(DB::raw('COUNT(*) as count'))
				->whereIn('queuename', $request->queues)
				->whereIn('agent', $request->agents)
				/** 1 and 13 is event ABANDON */
				->where('event', 'EXITWITHKEY');

			/** get by date time */
			if ($request->timeFilter || ($request->fromFilter && $request->toFilter)) {
				$time = calcTime($request);
				$EXITWITHKEY = $EXITWITHKEY->whereBetween('time', $time);
			}


			$filed1 = $RINGCANCELED->first();
			$filed2 = $EXITWITHTIMEOUT->first();
			$filed3 = $EXITWITHKEY->first();

			return [
				['lable' => 'RINGCANCELED', 'count' => $filed1->count],
				['lable' => 'EXITWITHTIMEOUT', 'count' => $filed2->count],
				['lable' => 'EXITWITHKEY', 'count' => $filed3->count],
			];
		} catch (\Throwable $th) {
			return [];
		}
	}
}
