<?php

namespace App\Controllers;

use App\Models\Category;
use App\Utils\BaseTable;
use Core\Auth;
use Core\BaseController;
use Core\Redirect;
use Core\Container;

class ProductsController extends BaseController
{
    private $product;
    private $categorys;

    public function __construct()
    {
        parent::__construct();
        $this->product = Container::getModel("Product");
        $this->categorys = Container::getModel("Category");
        $this->product_categorys = Container::getModel("Product_Categorys");
    }

    public function index()
    {
        $this->setPageTitle('Produtos');
        $this->view->products = BaseTable::decodeTable($this->product->All(), "product");
        return $this->renderView('products/index', 'layout');
    }

    public function show($id)
    {
        $this->view->product = $this->product->find($id);
        $this->setPageTitle("{$this->view->product->title}");
        return $this->renderView('products/show', 'layout');
    }

    public function create()
    {
        $this->setPageTitle('Novo Produto');
        $this->view->categories = $this->categorys->All();
        return $this->renderView('products/create', 'layout');
    }

    public function store($request)
    {
        $data = [
            'name' => $request->post->name,
            'code' => $request->post->code,
            'price' => $request->post->price,
            'qtd' => $request->post->qtd,
            'description' => $request->post->description,
        ];


        try{
            if ($this->product->create($data)) {
                $product = $this->product->findBy($data);

                foreach ($request->post->category_id as $category_id) {
                    $data = [
                        'category_id' => $category_id,
                        'product_id' => $product->id
                    ];

                    if($category_id){
                        $this->product_categorys->create($data);
                    }
                }
                return Redirect::route('/products', [
                    'success' => ['Produto salvo com sucesso']
                ]);
            }
        }catch(\Exception $e){
            return Redirect::route('/products', [
                'errors' => [$e->getMessage()]
            ]);
        }
    }

    public function edit($id)
    {
        $this->view->product = $this->product->find($id);
        $this->view->categories = $this->categorys->All();
        $this->view->product_category = $this->product_categorys->findBy(
            ['product_id' => $id], 'category_id', true);
        $this->setPageTitle('Editar Produto - ' . $this->view->product->title);
        return $this->renderView('products/edit', 'layout');
    }

    public function update($id, $request)
    {
        $data = [
            'name' => $request->post->name,
            'code' => $request->post->code,
            'price' => $request->post->price,
            'qtd' => $request->post->qtd,
            'description' => $request->post->description
        ];

        try{
            if (isset($request->post->category_id) && 0 < count($request->post->category_id)) {

                $categorys_product = $this->product_categorys->findBy(['product_id' => $id], 'category_id', true);
                
                foreach ($categorys_product as $category) {
                    if (!in_array($category->category_id,$request->post->category_id)) {
                        $this->product_categorys->deleteBy(
                            ['product_id' => $id,
                            'category_id' => $category->category_id]);
                    }

                    $product_categorys[] = $category->category_id;
                }

                foreach ($request->post->category_id as $reqCategory) {
                    if (!in_array($reqCategory, $product_categorys)) {
                        $this->product_categorys->create(['product_id' => $id,'category_id' => $reqCategory]);
                    }
                }
            }

            if($this->product->update($data, $id)){
                return Redirect::route('/products', [
                    'success' => ['Produto atualizado com sucesso!']
                ]);
            }else{
                return Redirect::route('/products', [
                    'errors' => ['Erro ao atualzar!']
                ]);
            }

        }catch(\Exception $e){
            return Redirect::route('/products', [
                'errors' => [$e->getMessage()]
            ]);
        }
    }

    public function delete($id)
    {
        try{
            if($this->product_categorys->deleteBy(['product_id' => $id]) && $this->product->delete($id)){
                return Redirect::route('/products', [
                    'success' => ['Produto deletado!']
                ]);
            }else{
                return Redirect::route('/products', [
                    'errors' => ['Erro ao excluir!']
                ]);
            }
        }catch(\Exception $e){
            return Redirect::route('/products', [
                'errors' => [$e->getMessage()]
            ]);
        }

    }

}