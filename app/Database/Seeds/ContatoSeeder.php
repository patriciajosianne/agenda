<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ContatoSeeder extends Seeder
{
	public function run()
	{
		$model = model('ContatoModel');

		$data = [
			'nome' 		      => 'Patrícia Josianne',
			'tipo_contato_id' => 1,
			'telefone'        => '3822118802',
			'celular'         => '38991492862',
			'email' 	      => 'patricia.silva@montesclaros.mg.gov.br',
		];
		$model->insert($data);
	}
}
