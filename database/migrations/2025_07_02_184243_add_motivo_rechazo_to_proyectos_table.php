    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::table('proyecto', function (Blueprint $table) {
                // Añade la columna 'motivo_rechazo' si no existe. Es nullable.
                // La pondremos después de 'objetivo' para consistencia.
                if (!Schema::hasColumn('proyecto', 'motivo_rechazo')) {
                    $table->text('motivo_rechazo')->nullable()->after('objetivo');
                }
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('proyecto', function (Blueprint $table) {
                if (Schema::hasColumn('proyecto', 'motivo_rechazo')) {
                    $table->dropColumn('motivo_rechazo');
                }
            });
        }
    };
    