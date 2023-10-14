<?php

use App\Models\Salle;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->date("date");
            $table->time("heure_debut");
            $table->time("heure_fin");
            $table->enum("type",["en ligne","presentiel"]);
            $table->foreignIdFor(Salle::class)->constrained()->cascadeOnDelete();
            $table->enum("annulation",["en cours","accepter","refuser"])->nullable();
            $table->boolean("validation")->default(false);
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
        Schema::dropIfExists('sessions');
    }
};
