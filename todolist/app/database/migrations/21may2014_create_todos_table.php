<?php
public function up()
{
	Schema::create('todos', function(Blueprint $table)
	{
		$table->increments('id');
		$table->integer('user_id');
		$table->string('todo_list_topic');
		$table->string('description');
		$table->timestamps();
	});
}
?>