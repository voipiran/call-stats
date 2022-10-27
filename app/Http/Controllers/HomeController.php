<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Queues;
use App\Agent;
use App\QueuesDetails;
use App\SettingsApp;
use DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\App;


class HomeController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// $this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		$lang = SettingsApp::first();
		if ($lang->lang == 'fa') {
			App::setLocale('fa');
		} else {
			App::setLocale('en');
		}

		$aboutMe = Lang::get('aboutMe');
		// return $aboutMe['title'];
		return view('home', ['aboutMe' => $aboutMe]);
	}




	/** --------------------------------- Start Home component ---------------------------------- */
	/** action  */
	public function actions(Request $request)
	{
		$method = $request->method;
		switch ($method) {

			case 'Home_getData':
				$res  = $this->Home_getData($request);
				break;
			case 'Home_calcDateTime':
				$res  = $this->Home_calcDateTime($request);
				break;
			default:
				$res = [
					'status' => 500,
					'message' => 'no command found'
				];
		}
		return response()->json($res, $res['status']);
	}

	/** get data select-option from table Agent and ... */
	public function Home_getData($request)
	{
		try {

			// start get agent list
			if ($request->showAllAgent) {
				$agent = DB::table('queue_stats')
					->select('agent as name', 'agent as extension')
					->groupBy('agent')->get();
			} else {
				$query = QueuesDetails::where('keyword', 'member')->groupBy('data')->get(['data']);
				$agents = [];
				foreach ($query as $item) {
					$remove = str_replace('Local/', '', $item->data);
					$remove = str_replace('@from-queue/n,0', '', $remove);
					$agents[] = $remove;
				}
				$agent = Agent::whereIn('extension', $agents)->get(['name', 'extension']);
			}


			$queue = Queues::get(['descr', 'extension']);
			return [
				'status' => 200,
				'message' => 'success',
				'queue' => $queue,
				'agent' => $agent
			];
		} catch (\Throwable $th) {
			return [
				'status' => 500,
				'message' => 'faild',
				'error' => $th->getMessage()
			];
		}
	}

	/** submit Home.vue for get detail answered component  */
	public function Home_calcDateTime($request)
	{
		try {
			return [
				'status' => 200,
				'message' => 'success',
				'timeFilter' => calcTime($request)

			];
		} catch (\Throwable $th) {
			return [
				'status' => 500,
				'message' => 'faild',
				'error' => $th->getMessage()
			];
		}
	}
	/** --------------------------------- End Home component ---------------------------------- */
}
