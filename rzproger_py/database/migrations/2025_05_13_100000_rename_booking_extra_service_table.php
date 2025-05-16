<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameBookingExtraServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Переименование таблицы
        if (Schema::hasTable('booking_extra_service') && !Schema::hasTable('booking_extra_services')) {
            Schema::rename('booking_extra_service', 'booking_extra_services');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('booking_extra_services') && !Schema::hasTable('booking_extra_service')) {
            Schema::rename('booking_extra_services', 'booking_extra_service');
        }
    }
}
