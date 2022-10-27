<?php

use App\SettingsApp;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Settings extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function (Blueprint $table) {
			$table->string('lang');
			$table->string('dir');
		});

		$newRow = new SettingsApp;
		$newRow->lang = 'fa';
		$newRow->dir = 'rtl';
		$newRow->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('settings');
	}
}
