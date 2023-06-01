<?php
namespace App\Http\Controllers;

use App\Criteria\Products\ProductsOfUserCriteria;
use App\DataTables\ProductApprovalDataTable;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Response;

class ProductApprovalController extends Controller
{

    /** @var  ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepo)
    {
        parent::__construct();
        $this->productRepository = $productRepo;
    }
    /**
     * Display a listing of the Product.
     *
     * @param ProductApprovalDataTable $productDataTable
     * @return Response
     */
    public function index(ProductApprovalDataTable $productDataTable)
    {
        return $productDataTable->render('products-approvals.index');
    }

    public function show($id)
    {
        $this->productRepository->pushCriteria(new ProductsOfUserCriteria(auth()->id()));
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            Flash::error('Product not found');

            return redirect(route('products-approvals.index'));
        }

        return view('products-approvals.show')->with('product', $product);
    }
}

