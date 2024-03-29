<?php

use App\Models\AnneeScolaire;
use App\Models\Classe;
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
        Schema::create('classe_annees', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Classe::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(AnneeScolaire::class)->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('classe_annees');
    }
};
