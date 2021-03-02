<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableTransactionAddFieldsValidatedAtValidatedByApprovedAtApprovedBy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction', function($table){
            $table->string('is_validated')->default(0)->nullable();
            $table->string('validated_at')->nullable();
            $table->string('approved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction', function($table){
            $table->dropColumn(['validated_at','validated_by','approved_at','approved_by','is_validated']);
        });
    }
}
