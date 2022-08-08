<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOriginAndSenderToArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->string('origin')->nullable()->after('document_type_id');
            $table->string('sender')->nullable()->after('origin');
            $table->foreignId('position_id')->nullable()->after('sender')->constrained('positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->dropColumn('origin');
            $table->dropColumn('sender');
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');
        });
    }
}
