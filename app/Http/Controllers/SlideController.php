<?php
/**
 * File name: SlideController.php
 * Last modified: 2020.09.12 at 20:01:58
 * Author: Pixbit Solutions - https://pixbitsolutions.com
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers;

use App\DataTables\SlideDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateSlideRequest;
use App\Http\Requests\UpdateSlideRequest;
use App\Models\Product;
use App\Repositories\SlideRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use App\Repositories\ProductRepository;
use App\Repositories\MarketRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class SlideController extends Controller
{
    /** @var  SlideRepository */
    private $slideRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var MarketRepository
     */
    private $marketRepository;

    public function __construct(SlideRepository $slideRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo
        , ProductRepository $productRepo
        , MarketRepository $marketRepo)
    {
        parent::__construct();
        $this->slideRepository = $slideRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->productRepository = $productRepo;
        $this->marketRepository = $marketRepo;
    }

    /**
     * Display a listing of the Slide.
     *
     * @param SlideDataTable $slideDataTable
     * @return Response
     */
    public function index(SlideDataTable $slideDataTable)
    {
        return $slideDataTable->render('slides.index');
    }

    /**
     * Show the form for creating a new Slide.
     *
     * @return Response
     */
    public function create()
    {
        $products = Product::get();
        $slide = null;
        $market = $this->marketRepository->pluck('name', 'id');

        $hasCustomField = in_array($this->slideRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->slideRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('slides.create')
            ->with("customFields", isset($html) ? $html : false)
            ->with("products", $products)
            ->with("market", $market)
            ->with('slide', $slide);
    }

    /**
     * Store a newly created Slide in storage.
     *
     * @param CreateSlideRequest $request
     *
     * @return Response
     */
    public function store(CreateSlideRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->slideRepository->model());

        try {
            $slide = $this->slideRepository->create($input);
            $slide->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($slide, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.slide')]));

        return redirect(route('slides.index'));
    }

    /**
     * Display the specified Slide.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $slide = $this->slideRepository->findWithoutFail($id);

        if (empty($slide)) {
            Flash::error('Slide not found');

            return redirect(route('slides.index'));
        }

        return view('slides.show')->with('slide', $slide);
    }

    /**
     * Show the form for editing the specified Slide.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $slide = $this->slideRepository->findWithoutFail($id);
        $products = Product::get();
        $market = $this->marketRepository->pluck('name', 'id')->toArray();
        $market = array('' => trans('lang.slide_market_id_placeholder')) + $market;


        if (empty($slide)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.slide')]));

            return redirect(route('slides.index'));
        }
        $customFieldsValues = $slide->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->slideRepository->model());
        $hasCustomField = in_array($this->slideRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('slides.edit')->with('slide', $slide)
            ->with("customFields", isset($html) ? $html : false)
            ->with("products", $products)
            ->with("market", $market);
    }

    /**
     * Update the specified Slide in storage.
     *
     * @param int $id
     * @param UpdateSlideRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSlideRequest $request)
    {

        $slide = $this->slideRepository->findWithoutFail($id);

        if (empty($slide)) {
            Flash::error('Slide not found');
            return redirect(route('slides.index'));
        }
        $input = $request->all();

        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->slideRepository->model());

        try {
            if(!isset($input['product_id'])){
                $input['product_id'] = null;
            }
            if(!isset($input['market_id'])){
                $input['market_id'] = null;
            }
            $slide = $this->slideRepository->update($input, $id);

            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($slide, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $slide->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.slide')]));

        return redirect(route('slides.index'));
    }

    /**
     * Remove the specified Slide from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $slide = $this->slideRepository->findWithoutFail($id);

        if (empty($slide)) {
            Flash::error('Slide not found');

            return redirect(route('slides.index'));
        }

        $this->slideRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.slide')]));

        return redirect(route('slides.index'));
    }

    /**
     * Remove Media of Slide
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $slide = $this->slideRepository->findWithoutFail($input['id']);
        try {
            if ($slide->hasMedia($input['collection'])) {
                $slide->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
