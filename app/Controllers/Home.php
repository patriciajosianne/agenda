<?php

namespace App\Controllers;

use App\Models\ContatoModel;

/**
 * Classe responsável por carregar a estrutura do app
 */
class Home extends BaseController
{
	protected $data;
	protected $modelContato;

	public function __construct()
	{
		$this->modelContato = new ContatoModel();
	}
	
	public function index(): void 
	{
		$this->data['title'] = "Home";
		$this->data['conteudo'] = view('home/index');

		$this->modelo();
	}

	public function contatos(): void
	{
		$this->data['title'] = "Contatos";

		$contatos = $this->modelContato->findAll();
		dd($contatos);

		$this->data['conteudo'] = view('home/contatos');

		$this->modelo();
	}

	public function gravar(): void
	{
		$contato = [
			'nome' 		      => 'M',
			'tipo_contato_id' => 1,
			'telefone'        => '3',
			'celular'         => '389',
			'email' 	      => 'patricia.silva',
		];

		if($this->modelContato->save($contato) === false){
			$erros = $this->modelContato->errors();

			echo "<h1>Erro ao tentar salvar</h1>";
			echo "<br>";
			echo "<br>";
			foreach($erros as $field => $error){
				echo "Campo: {$field}<br>";
				echo "Mensagem: {$error}<br>";
				echo "<br>";
			}
		} else {
			echo "<h1>Salvo com sucesso.</h1>";
		}
	}

	public function contato($slug = null): void
	{
		$this->data['title'] = "Contato {$slug}";
		$this->data['slug'] = $slug;
		$this->data['conteudo'] = view('home/contato', $this->data);

		$this->modelo();
	}

	private function modelo(): void 
	{
		$menu['menus'] = [
			'Home' => base_url(),
			'Contatos' => base_url('contatos'),
			'Contato' => base_url('contato')
		];		
		$this->data['menu'] = view('home/padrao/menu', $menu, ['cache' => 60]);
		
		$this->data['versao'] = "0.0.1";

		echo view('home/padrao/modelo', $this->data);
	}
}
