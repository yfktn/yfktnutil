<?php namespace Yfktn\YfktnUtil\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateYfktnYfktnutilRevision extends Migration
{
    public function up()
    {
        Schema::create('yfktn_yfktnutil_revision', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('revisionable_type');
            $table->integer('revisionable_id');
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->string('field')->nullable()->index();
            $table->string('cast')->nullable();
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->timestamps();
            $table->integer('data_owner_id')->unsigned()->nullable()->index();
            $table->index(['revisionable_id', 'revisionable_type']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('yfktn_yfktnutil_revision');
    }
}
