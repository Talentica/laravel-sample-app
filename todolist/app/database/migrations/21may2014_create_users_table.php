<?php
public function up()
{
	Schema::create('users', function(Blueprint $table)
	{
		$table->increments('id');
		$table->string('username')->unique();
		$table->string('password');
		$table->timestamps();
	});
}
?>