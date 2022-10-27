<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class QueueStat extends Model
{

	protected $table = 'queue_stats';

	public function agent()
	{
		return $this->hasMany('Agent', 'qagent', 'agent_id');
	}
}
