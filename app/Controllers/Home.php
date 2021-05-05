<?php

namespace App\Controllers;

use App\Models\ContatoModel;
use App\Models\TipoContatoModel;

/**
 * Classe responsável por carregar a estrutura do app
 */
class Home extends BaseController
{
	protected $data;
	protected $modelContato;
	protected $modelTipo;

	public function __construct()
	{
		$this->modelContato = new ContatoModel();
		$this->modelTipo = new TipoContatoModel();

		$this->data['title'] = 'Agenda';
		$this->data['tipos'] = $this->modelTipo->findAll();
		$this->data['msg'] = session()->getFlashdata('msg');
		$this->data['erros'] = session()->getFlashdata('erros');
	}
	
	public function index(): void 
	{
		$this->data['title'] = "Home";
		$this->data['conteudo'] = view('home/index');

		$this->modelo();
	}

	public function contatos($excluido = false): void
	{
		if($excluido) {
			$this->data['title'] = "Lixeira";
			$data['title'] = "Lixeira | Contatos Excluídos";
		} else {
			$this->data['title'] = "Contatos";
			$data['title'] = "Listagem de contatos";
		}

		$data['contatos'] =  $this->modelContato->getContatos(null, $excluido);

		$this->data['conteudo'] = view('home/contatos', $data);

		$this->modelo();
	}

	public function excluidos()
	{
		$this->contatos(true);
	}

	public function contato($id = null): void
	{
		$data['contato'] = $this->modelContato->getContatos($id);
		$this->data['title'] = "Contato {$data['contato']['nome']}";
		$this->data['conteudo'] = view('home/contato', $data);

		$this->modelo();
	}

	public function formulario($id = null)
	{
		$this->data['title'] = "Novo Contato";
		
		$contatoPost = session()->getFlashdata('contato');
		if(!empty($contatoPost)){
			// se existir POST captura os dados enviados pelo formulario
			$this->data['contato'] = $contatoPost;
		}else if($id){
			// se existir ID, realiza a busca e carrega os dados
			$this->data['contato'] = $this->modelContato->find($id);
		} else {
			// se nada for passado, cria o array 
			$this->data['contato'] = ['id' => null, 'nome' => null, 'tipo_contato_id' => null, 'celular' => null, 'telefone' => null, 'email' => null];
		}
		// renderiza o conteudo
		$this->data['conteudo'] = view('home/novo', $this->data);
		// carrega o template
		$this->modelo();
	}

	public function gravar()
	{
		$this->data['contato'] = $this->request->getPost();
		
		if($this->modelContato->save($this->data['contato']) === false){
			session()->setFlashdata('erros', $this->modelContato->errors());
			//captura dados enviados via post
			session()->setFlashdata('contato', $this->data['contato']);
			// carrega o ID
			$id = $this->request->getPost('id');
			// verifica se ID esta preenchido
			if($id){
				return redirect()->to("editar/{$id}");
			} else {
				return redirect()->to('novo');
			}
		} else {
			session()->setFlashdata('msg', "Gravado com Sucesso!");
			return redirect()->to('contatos'); 
		}
	}

	public function excluir($id)
	{
		if($this->modelContato->delete($id)){
			session()->setFlashdata('msg', 'Exclusão realizada com sucesso!');
		} else {
			session()->setFlashdata('msg', 'Erro ao tentar realizar a operação!');
		}
		return redirect()->back();
	}

	public function desfazer($id)
	{
		$data = [
			'apagado_em' => null,
		];

		if($this->modelContato->update($id, $data)){
			session()->setFlashdata('msg', 'Exclusão desfeita.');
		} else {
			session()->setFlashdata('msg', 'Erro ao tentar realizar a operação!');
		}
		return redirect()->back();
	}

	private function modelo(): void 
	{
		// itens do menu
		$menu['menus'] = [
			'Home' => base_url(),
			'Contatos' => base_url('contatos'),
			'Novo' => base_url('novo'),
			'Lixeira' => base_url('lixeira'),
		];
		// carrega o menu
		$this->data['menu'] = view('home/padrao/menu', $menu);
		
		$this->data['versao'] = "0.0.1";
		// renderiza a view
		echo view('home/padrao/modelo', $this->data);
	}
}
