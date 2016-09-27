<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// php artisan make:migration create_tabelas --create=cidades
// *** php artisan migrate
// php artisan make:model Cidade
// *** php artisan db:seed

class CreateTabelas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('cidades', function (Blueprint $table) {
          $table->increments('id');
          $table->string('nome',64);
          $table->string('uf',2);
          $table->integer('id_usuario_inclusao')->nullable();
          $table->integer('id_usuario_alteracao')->nullable();
          $table->timestamps();

          $table->index('nome', 'cidades_idx01');
      });

      Schema::create('bairros', function (Blueprint $table) {
          $table->increments('id');
          $table->string('nome',64);
          $table->integer('id_cidade')->unsigned();
          $table->integer('id_usuario_inclusao')->nullable();
          $table->integer('id_usuario_alteracao')->nullable();
          $table->timestamps();

          $table->index('nome', 'bairros_idx01');
          $table->foreign('id_cidade')->references('id')->on('cidades');
      });

      Schema::create('contatos', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('id_bairro')->unsigned();
          $table->string('nome',64);
          $table->date('data_nascimento');
          $table->string('cpf',11);
          $table->string('titulo',32)->nullable();
          $table->string('secao',8)->nullable();
          $table->string('zona',8)->nullable();
          $table->string('endereco',64);
          $table->string('numero',8);
          $table->string('complemento',32)->nullable();
          $table->string('cep',8)->nullable();
          $table->string('telefone1',16)->nullable();
          $table->string('telefone2',16)->nullable();
          $table->string('telefone3',16)->nullable();
          $table->string('telefone4',16)->nullable();
          $table->string('telefone5',16)->nullable();
          $table->string('ligou',1);
          $table->integer('id_usuario_ligou')->nullable();
          $table->timestamp('data_hora_ligou')->nullable();;
          $table->integer('id_usuario_inclusao')->nullable();
          $table->integer('id_usuario_alteracao')->nullable();
          $table->timestamps();

          $table->index('nome', 'contatos_idx01');
          $table->index('data_nascimento', 'contatos_idx02');
          $table->index('id_bairro', 'contatos_idx03');
          $table->index('data_hora_ligou', 'contatos_idx04');
          $table->unique('cpf', 'contatos_un01');
          $table->foreign('id_bairro')->references('id')->on('bairros');
      });

      //alter table `bairros` add constraint `bairros_fk01` foreign key (`id_cidade`) references `cidades`(`id`);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('contatos');
      Schema::dropIfExists('bairros');
      Schema::dropIfExists('cidades');
    }
}
