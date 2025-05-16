<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type'); // Тип отчета: daily, monthly, custom
            $table->date('start_date'); // Начальная дата периода отчета
            $table->date('end_date'); // Конечная дата периода отчета
            $table->integer('total_bookings'); // Общее количество бронирований
            $table->integer('completed_bookings'); // Количество завершенных бронирований
            $table->integer('cancelled_bookings'); // Количество отмененных бронирований
            $table->decimal('total_revenue', 10, 2); // Общий доход (только от завершенных бронирований)
            $table->decimal('average_booking_value', 10, 2)->nullable(); // Средняя стоимость бронирования
            $table->integer('most_booked_room_type_id')->nullable(); // ID наиболее часто бронируемого типа номера
            $table->decimal('room_revenue', 10, 2)->default(0); // Доход от номеров
            $table->decimal('services_revenue', 10, 2)->default(0); // Доход от дополнительных услуг
            $table->string('generated_by'); // Кто сгенерировал отчет
            $table->text('notes')->nullable(); // Примечания к отчету
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
