<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('recipes', function (Blueprint $t) {
            $t->id();
            $t->uuid('uuid')->unique();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('title');
            $t->text('description')->nullable();
            $t->json('ingredients')->nullable();
            $t->json('steps')->nullable();
            $t->integer('prep_time')->default(0);
            $t->string('yield')->nullable();
            $t->json('tags')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('recipes'); }
};
