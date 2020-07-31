<?php

namespace App\Controllers;

use App\Models\Category;
use App\Utils\BaseTable;
use Core\Auth;
use Core\BaseController;
use Core\Redirect;
use Core\Container;

class CategorysController extends BaseController
{
    private $categorys;

    public function __construct()
    {
        parent::__construct();
        $this->categorys = Container::getModel("Category");
    }

    public function index()
    {
        $this->setPageTitle('Categorias');
        $this->view->categorys = BaseTable::decodeTable($this->categorys->All(), "category");
        return $this->renderView('categorys/index', 'layout');
    }

    public function show($id)
    {
        $this->view->category = $this->categorys->find($id);
        $this->setPageTitle("{$this->view->category->title}");
        return $this->renderView('categorys/show', 'layout');
    }

    public function create()
    {
        $this->setPageTitle('Nova Categoria');
        return $this->renderView('categorys/create', 'layout');
    }

    public function store($request)
    {
        $data = [
            'name' => $request->post->name,
            'code' => $request->post->code
        ];

        try{
            $post = $this->categorys->create($data);
            return Redirect::route('/categorys', [
                'success' => ['Categoria salva com sucesso!']
            ]);
        }catch(\Exception $e){
            return Redirect::route('/categorys', [
                'errors' => [$e->getMessage()]
            ]);
        }
    }

    public function edit($id)
    {
        $this->view->category = $this->categorys->find($id);
        $this->setPageTitle('Editar Categoria - ' . $this->view->category->title);
        return $this->renderView('categorys/edit', 'layout');
    }

    public function update($id, $request)
    {
        $data = [
            'name' => $request->post->name,
            'code' => $request->post->code
        ];

        try{


            if($this->categorys->update($data, $id)){
                return Redirect::route('/categorys', [
                    'success' => ['Categoria atualizado com sucesso!']
                ]);
            }else{
                return Redirect::route('/categorys', [
                    'errors' => ['Erro ao atualzar!']
                ]);
            }

        }catch(\Exception $e){
            return Redirect::route('/categorys', [
                'errors' => [$e->getMessage()]
            ]);
        }

    }

    public function delete($id)
    {
        try{
            //deletar o rgistro na tabela auxiliar antes
            if($this->categorys->delete($id)){
                return Redirect::route('/categorys', [
                    'success' => ['Categoria deleteda!']
                ]);
            }else{
                return Redirect::route('/categorys', [
                    'errors' => ['Erro ao excluir!']
                ]);
            }
        }catch(\Exception $e){
            return Redirect::route('/categorys', [
                'errors' => [$e->getMessage()]
            ]);
        }

    }

}