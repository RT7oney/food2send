<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		DB::table('stuff')->insert([
			'name' => 'dada_token',
			'content' => 'init',
		]);
	}
}
