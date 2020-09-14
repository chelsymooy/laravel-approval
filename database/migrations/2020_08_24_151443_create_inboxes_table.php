<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInboxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appr_inboxes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();

            $table->bigInteger('ref_id')->nullable();
            $table->string('ref_type')->nullable();

            $table->bigInteger('approved_by')->nullable();
            $table->datetime('approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['org_id', 'approved_at'], 'inboxes_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appr_inboxes');
    }
}
