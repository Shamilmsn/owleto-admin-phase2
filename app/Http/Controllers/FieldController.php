<?php

namespace App\Http\Controllers;

use App\DataTables\FieldDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateFieldRequest;
use App\Http\Requests\UpdateFieldRequest;
use App\Models\Attribute;
use App\Models\Field;
use App\Models\SectorDelliveryTypes;
use App\Repositories\DeliveryTypeRepository;
use App\Repositories\FieldRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
                use App\Repositories\MarketRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class FieldController extends Controller
{
    /** @var  FieldRepository */
    private $fieldRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
private $uploadRepository;/**
  * @var MarketRepository
  */
private $marketRepository;

    public function __construct(FieldRepository $fieldRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo
                , MarketRepository $marketRepo, DeliveryTypeRepository $deliveryTypeRepo)
    {
        parent::__construct();
        $this->fieldRepository = $fieldRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->marketRepository = $marketRepo;
        $this->deliveryTypeRepository = $deliveryTypeRepo;

    }

    /**
     * Display a listing of the Field.
     *
     * @param FieldDataTable $fieldDataTable
     * @return Response
     */
    public function index(FieldDataTable $fieldDataTable)
    {
        return $fieldDataTable->render('fields.index');
    }

    /**
     * Show the form for creating a new Field.
     *
     * @return Response
     */
    public function create()
    {
//        $market = $this->marketRepository->pluck('name','id');
//        $marketsSelected = [];
        $deliveryTypes = $this->deliveryTypeRepository->pluck('name','id');
        $deliveryTypesSelected = [];
        $hasCustomField = in_array($this->fieldRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->fieldRepository->model());
                $html = generateCustomField($customFields);
            }

        $field = null;
        return view('fields.create')->with("customFields", isset($html) ? $html : false)
            ->with('field', $field)
//            ->with("market",$market)
//            ->with("marketsSelected",$marketsSelected)
            ->with( "deliveryTypes", $deliveryTypes)
            ->with("deliveryTypesSelected",$deliveryTypesSelected );
    }

    /**
     * Store a newly created Field in storage.
     *
     * @param CreateFieldRequest $request
     *
     * @return Response
     */
    public function store(CreateFieldRequest $request)
    {
        $input = $request->all();

        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->fieldRepository->model());
        try {

            $field = $this->fieldRepository->create($input);

            $field->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
            if(isset($input['image']) && $input['image']){
            $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
            $mediaItem = $cacheUpload->getMedia('image')->first();
            $mediaItem->copy($field, 'image');

            if (!empty($request->deliveryTypes)) {
                if($request->deliveryTypes){
                    foreach($request->deliveryTypes as $deliveryType){
                        $field->delivery_types()->attach($field->id, ['field_id' => $field->id, 'delivery_type_id' => $deliveryType]);
                    }
                }
            }
}
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.field')]));

        return redirect(route('fields.index'));
    }

    /**
     * Display the specified Field.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $field = $this->fieldRepository->findWithoutFail($id);

        if (empty($field)) {
            Flash::error('Field not found');

            return redirect(route('fields.index'));
        }

        return view('fields.show')->with('field', $field);
    }

    /**
     * Show the form for editing the specified Field.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $field = $this->fieldRepository->findWithoutFail($id);
//        $market = $this->marketRepository->pluck('name','id');
//        $marketsSelected = $field->markets()->pluck('markets.id')->toArray();

        $deliveryTypes = $this->deliveryTypeRepository->pluck('name','id');

        if($id != 9) {
            $deliveryTypesSelected = $field->delivery_types()->pluck('delivery_types.id')->toArray();
        }else{
            $deliveryTypesSelected = [];
        }

        if (empty($field)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.field')]));

            return redirect(route('fields.index'));
        }
        $customFieldsValues = $field->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->fieldRepository->model());
        $hasCustomField = in_array($this->fieldRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('fields.edit')->with('field', $field)->with("customFields", isset($html) ? $html : false)
//            ->with("market",$market)
//            ->with("marketsSelected",$marketsSelected)
            ->with( "deliveryTypes", $deliveryTypes)
            ->with("deliveryTypesSelected",$deliveryTypesSelected );
    }

    /**
     * Update the specified Field in storage.
     *
     * @param  int              $id
     * @param UpdateFieldRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFieldRequest $request)
    {
        $field = $this->fieldRepository->findWithoutFail($id);


        if (empty($field)) {
            Flash::error('Field not found');
            return redirect(route('fields.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->fieldRepository->model());
        try {
            $field = $this->fieldRepository->update($input, $id);
            $input['markets'] = isset($input['markets']) ? $input['markets'] : [];
            if(isset($input['image']) && $input['image']){
            $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
            $mediaItem = $cacheUpload->getMedia('image')->first();
            $mediaItem->copy($field, 'image');

        }
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $field->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
            $field->delivery_types()->detach($field->deliveryTypes);
            if($field->id != 9) {
                if(empty($request->deliveryTypes)){
                    Flash::error('Delivery Type is Required');
                    return redirect()->back()->withInput();
                }

            }

            if (!empty($request->deliveryTypes)) {
                foreach ($request->deliveryTypes as $deliveryType) {
                    $field->delivery_types()->attach($field->id, ['field_id' => $field->id, 'delivery_type_id' => $deliveryType]);
                }
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.field')]));

        return redirect(route('fields.index'));
    }

    /**
     * Remove the specified Field from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $field = $this->fieldRepository->findWithoutFail($id);

        if (empty($field)) {
            Flash::error('Field not found');

            return redirect(route('fields.index'));
        }

        $field->delivery_types()->detach();
        $sectorAttributes = Attribute::where('sector_id', $id)->delete();

        $this->fieldRepository->delete($id);


        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.field')]));

        return redirect(route('fields.index'));
    }

        /**
     * Remove Media of Field
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $field = $this->fieldRepository->findWithoutFail($input['id']);
        try {
            if($field->hasMedia($input['collection'])){
                $field->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
