<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Queues;
use App\Agent;
use App\SettingsApp;
use DB;
use Illuminate\Support\Facades\Session;



class AllFunctionController extends Controller
{

	/** action  */
	public function actions(Request $request)
	{
		$method = $request->method;
		switch ($method) {
			case 'Dashboard_getData':
				$res  = $this->Dashboard_getData();
				break;
			case 'setLanguage':
				$res  = $this->setLanguage($request);
				break;
			case 'getLanguage':
				$res  = $this->getLanguage('settingPage');
				break;
			default:
				$res = [
					'status' => 500,
					'message' => 'no command found'
				];
		}
		return response()->json($res, $res['status']);
	}


	/** --------------------------------- start dashboard component ---------------------------------- */
	/** get data component dashboarf */
	public function Dashboard_getData()
	{
		try {
			$agents = DB::table('queue_stats')
				->Join('qagent', 'queue_stats.qagent', '=', 'qagent.agent_id')
				->Join('qname', 'queue_stats.qname', '=', 'qname.qname_id')
				->select('qname.qname_id', 'qname.queue', 'qagent.agent', DB::raw('SUM(queue_stats.data2) as data2'), DB::raw('MAX(queue_stats.datetime) as lastCall'))
				->groupBy('queue_stats.qname')
				->groupBy('queue_stats.qagent');

			$queues = Queues::all();

			return [
				'status' => 200,
				'message' => 'success',
				'agents' => $agents->get(),
				'queues' => $queues,

			];
		} catch (\Throwable $th) {
			return [
				'status' => 500,
				'message' => 'faild',
				'error' => $th->getMessage()
			];
		}
	}
	/** --------------------------------- End dashboard component ---------------------------------- */


	/** detect language for page realTime */
	public function getLanguage($type='realTime')
	{
		try {
			$setting = SettingsApp::first();
			$res = [
				'status' => 200,
				'message' => 'success',
				'lang' =>  $setting->lang,
				'dir' =>    $setting->dir,
			];

			if ($type == 'settingPage')
				return $res;

			return	json_encode($res);
		} catch (\Throwable $th) {
			$res = [
				'status' => 200,
				'message' => 'Settings table not found',
				'lang' =>  'fa',
				'dir' =>   'rtl',
			];

			return	json_encode($res);
		}
	}
	/** set language for page realTime */
	public function setLanguage($request)
	{

		try {
			$setting = SettingsApp::first();

			// return $setting;
			$setting->lang = $request->lang;
			$setting->dir = $request->dir;
			$setting->save();

			$res = [
				'status' => 200,
				'message' => 'success',
				'lang' =>  $setting->lang,
				'dir' =>    $setting->dir,
			];

			return	$res;

			//code...
		} catch (\Throwable $th) {
			return $res = [
				'status' => 500,
				'message' => 'unSuccess',
			];
		}
	}
}
