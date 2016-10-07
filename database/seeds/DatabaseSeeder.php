<?php

use Illuminate\Database\Seeder;
use App\Model\Cidade;
use App\Model\Bairro;
use App\Model\Contato;
use App\User;
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

      DB::table('users')->delete();

      $usuarios = User::get();

      if($usuarios->count() == 0) {
          User::create(array(
              'email' => 'admin@gmail.com',
              'password' => Hash::make('admin'),
              'name'  => 'Administrador',
              'isAdmin'  => 'S',
              'podeAlterar'  => 'S',
              'podeIncluir'  => 'S',
              //'tipo'  => 'admin'
          ));
      }

      $uberlandia = Cidade::create(array(
        'nome'                  => 'Uberlândia',
        'uf'                    => 'MG',
      ));

      $bh = Cidade::create(array(
        'nome'                  => 'Belo Horizonte',
        'uf'                    => 'MG',
      ));

      $this->command->info('Inserindo o primeiro...');
      Bairro::create(array(
        'nome'                  => 'Jaraguá',
        'id_cidade'             => $uberlandia->id,
      ));

      $this->command->info('Inserindo o segundo...');
      Bairro::create(array(
        'nome'                  => 'Tubalina',
        'id_cidade'             => $uberlandia->id,
      ));

      $this->command->info('Inserindo o terceiro...');
      Bairro::create(array(
        'nome'                  => 'Umuarama',
        'id_cidade'             => $uberlandia->id,
      ));

      $this->command->info('Inserindo o quarto...');
      Bairro::create(array(
        'nome'                  => 'Pampulha',
        'id_cidade'             => $bh->id,
      ));

      $this->command->info('Validando e inserindo o quinto...');
      $new = array(
        'nome'                  => 'Ouro Preto',
        'id_cidade'             => $bh->id,
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
