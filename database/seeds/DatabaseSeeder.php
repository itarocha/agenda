<?php

use Illuminate\Database\Seeder;
use App\Cidade;
use App\Bairro;
use App\Contato;
use App\ModelValidator;

// php artisan db:seed

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PopulaTabelasSeeder::class);
    }
}

class PopulaTabelasSeeder extends Seeder {

  public function run(){

      DB::table('contatos')->delete();
      DB::table('bairros')->delete();
      DB::table('cidades')->delete();

      $uberlandia = Cidade::create(array(
        'nome'                  => 'Uberlândia',
        'uf'                    => 'MG',
        'id_usuario_inclusao'    => 1,
        'id_usuario_alteracao'  => 1,
      ));

      $bh = Cidade::create(array(
        'nome'                  => 'Belo Horizonte',
        'uf'                    => 'MG',
        'id_usuario_inclusao'    => 1,
        'id_usuario_alteracao'  => 1,
      ));

      $this->command->info('Inserindo o primeiro...');

      Bairro::create(array(
        'nome'                  => 'Jaraguá',
        'id_cidade'             => $uberlandia->id,
        'id_usuario_inclusao'    => 1,
        'id_usuario_alteracao'  => 1,
      ));

      $this->command->info('Inserindo o segundo...');

      Bairro::create(array(
        'nome'                  => 'Tubalina',
        'id_cidade'             => $uberlandia->id,
        'id_usuario_inclusao'    => 1,
        'id_usuario_alteracao'  => 1,
      ));

      Bairro::create(array(
        'nome'                  => 'Umuarama',
        'id_cidade'             => $uberlandia->id,
        'id_usuario_inclusao'    => 1,
        'id_usuario_alteracao'  => 1,
      ));
      Bairro::create(array(
        'nome'                  => 'Pampulha',
        'id_cidade'             => $bh->id,
        'id_usuario_inclusao'    => 1,
        'id_usuario_alteracao'  => 1,
      ));

      $new = array(
        'nome'                  => 'Ouro Preto',
        'id_cidade'             => $bh->id,
        'id_usuario_inclusao'    => 1,
        'id_usuario_alteracao'  => 1,
      );


      $b = new Bairro();
      $v = new ModelValidator();

      if ($v->validate($new, $b->getRules())){
        $b->fill($new);
        $b->save();
      } else {
        dd($v->errors());
      }

      $this->command->info('Comandos realizados com sucesso!');
  }

}
