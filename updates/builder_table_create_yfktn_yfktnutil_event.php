<?php namespace Yfktn\YfktnUtil\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateYfktnYfktnutilEvent extends Migration
{
    public function up()
    {
        Schema::create('yfktn_yfktnutil_event', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('trigger_type', 1024)->nullable()->index();
            $table->integer('trigger_id')->nullable()->unsigned()->index();
            $table->string('why', 1024);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->text('description')->nullable();
            $table->integer('operator_id')->nullable()->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('yfktn_yfktnutil_event');
    }
}
