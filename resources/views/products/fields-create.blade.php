@if($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<style>
    .error {
        color: #f44336 !important;
        text-decoration: none;
        font-weight: normal !important;
    }
</style>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="form column">
    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('base_name', 'Name*', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('base_name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_name_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.product_name_help") }}
            </div>
        </div>
    </div>

    <!-- Image Field -->
    <div class="form-group row">
        {!! Form::label('image', trans("lang.product_image"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            <div style="width: 100%" class="dropzone image" id="image" data-field="image">
            </div>
            <a href="#loadMediaModal" data-dropzone="image" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color','primary')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
            <div class="form-text text-muted w-50">
                {{ trans("lang.product_image_help") }}
            </div>
        </div>
    </div>
    @prepend('scripts')
        <script type="text/javascript">
            var var15671147171873255749ble = [];
            @if(isset($product) && $product->hasMedia('image'))
            @forEach($product->getMedia('image') as $media)
            var15671147171873255749ble.push({
                name: "{!! $media->name !!}",
                size: "{!! $media->size !!}",
                type: "{!! $media->mime_type !!}",
                uuid: "{!! $media->getCustomProperty('uuid'); !!}",
                thumb: "{!! $media->getUrl('thumb'); !!}",
                collection_name: "{!! $media->collection_name !!}"
            });
                    @endforeach
                    @endif
            var dz_var15671147171873255749ble = $(".dropzone.image").dropzone({
                    url: "{!!url('uploads/store')!!}",
                    addRemoveLinks: true,
                    maxFiles: 5 - var15671147171873255749ble.length,
                    init: function () {
                        @if(isset($product) && $product->hasMedia('image'))
                        var15671147171873255749ble.forEach(media => {
                            dzInit(this, media, media.thumb);
                        });
                        @endif
                    },
                    accept: function (file, done) {
                        dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
                    },
                    sending: function (file, xhr, formData) {
                        dzSendingMultiple(this, file, formData, '{!! csrf_token() !!}');
                    },
                    complete: function (file) {
                        dzCompleteMultiple(this, file);

                        dz_var15671147171873255749ble[0].mockFile = file;
                    },
                    removedfile: function (file) {
                        //this.removeFile(file);
                        dzRemoveFileMultiple(
                            file, var15671147171873255749ble, '{!! url("products/remove-media") !!}',
                            'image', '{!! isset($product) ? $product->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                        );
                    }
                });
            dz_var15671147171873255749ble[0].mockFile = var15671147171873255749ble;
            dropzoneFields['image'] = dz_var15671147171873255749ble;
        </script>
@endprepend

<!-- Price Field -->
    <div class="form-group row ">
        {!! Form::label('price', 'Price*', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('price', null,  ['class' => 'form-control', 'id' => 'price', 'placeholder'=>  trans("lang.product_price_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.product_price_help") }}
            </div>
        </div>
    </div>

    <!-- Discount Price Field -->
    <div class="form-group row ">
        {!! Form::label('discount_price', trans("lang.product_discount_price"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('discount_price', null,  ['class' => 'form-control','id' => 'discount_price', 'placeholder'=>  trans("lang.product_discount_price_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.product_discount_price_help") }}
            </div>
            <span class="text-black xs price-validation">Discount Price should be less than price</span>
        </div>
    </div>

    <!-- Tax Field -->
    <div class="form-group row ">
        {!! Form::label('tax', 'Tax*', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('tax', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_tax_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.product_tax_help") }}
            </div>
        </div>
    </div>

    <!-- Description Field -->
    <div class="form-group row ">
        {!! Form::label('description', trans("lang.product_description"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
             trans("lang.product_description_placeholder")  ]) !!}
            <div class="form-text text-muted">{{ trans("lang.product_description_help") }}</div>
        </div>
    </div>
    <div class="form-group row ">
        {!! Form::label('add_on', trans("lang.product_add_on"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            <div class="add-on-container">

            </div>
        </div>
    </div>
</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

    <!-- Capacity Field -->
    <div class="form-group row ">
        {!! Form::label('capacity', trans("lang.product_capacity"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('capacity', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_capacity_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.product_capacity_help") }}
            </div>
        </div>
    </div>

{{--    <!-- unit Field -->--}}
{{--    <div class="form-group row ">--}}
{{--        {!! Form::label('unit', trans("lang.product_unit"), ['class' => 'col-3 control-label text-right']) !!}--}}
{{--        <div class="col-9">--}}
{{--            {!! Form::text('unit', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_unit_placeholder")]) !!}--}}
{{--            <div class="form-text text-muted">--}}
{{--                {{ trans("lang.product_unit_help") }}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <!-- package_items_count Field -->--}}
{{--    <div class="form-group row ">--}}
{{--        {!! Form::label('package_items_count', trans("lang.product_package_items_count"), ['class' => 'col-3 control-label text-right']) !!}--}}
{{--        <div class="col-9">--}}
{{--            {!! Form::number('package_items_count', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_package_items_count_placeholder"),'step'=>"any", 'min'=>"0"]) !!}--}}
{{--            <div class="form-text text-muted">--}}
{{--                {{ trans("lang.product_package_items_count_help") }}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    <!-- Market Id Field -->
    <div class="form-group row ">
        {!! Form::label('market_id', 'Vendor*',['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('market_id', $market, null, ['class' => 'select2 form-control','id' => 'market_id']) !!}
            <div class="form-text text-muted">{{ trans("lang.product_market_id_help") }}</div>
        </div>
    </div>

    <!-- Market Id Field -->
    <div class="form-group row ">
        {!! Form::label('sector_id', 'Layout*',['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            <select name="sector_id" id="sector_id" class=" form-control">
            </select>
            <div class="form-text text-muted">{{ trans("lang.product_sector_id_help") }}</div>
        </div>
    </div>

    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('category_id', 'Category*',['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            <select name="category_id" id="category_id" class="form-control">
            </select>
{{--            {!! Form::select('category_id', $category, null, ['class' => 'select2 form-control','id' => 'category_id']) !!}--}}
            <div class="form-text text-muted">Select Category</div>
        </div>
    </div>

    <!-- Sub category Id Field -->
    <div class="form-group row ">
        {!! Form::label('sub_category_id', 'Sub Category',['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            <select name="sub_category_id" id="sub_category_id" class="form-control">
            </select>
            <div class="form-text text-muted">Select Sub Category</div>
        </div>
    </div>

    <!-- owleto_commission_percentage Field -->
    <div class="form-group row ">
        {!! Form::label('owleto_commission_percentage', 'Owleto Commission Percentage*', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('owleto_commission_percentage', null,  ['class' => 'form-control','placeholder'=>  trans("lang.owleto_commission_percentage_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.owleto_commission_percentage_help") }}
            </div>
        </div>
    </div>
    <div class="form-group row " id="food-type-div">
        {!! Form::label('food_type', trans("lang.food_type"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('food_type',
            [
            'VEG' => trans('lang.food_type_veg'),
            'NON_VEG' => trans('lang.food_type_non_veg'),
            ],
             null, ['class' => 'select2 form-control']) !!}
            <div class="form-text text-muted">{{ trans("lang.food_type_help") }}</div>
        </div>
    </div>
    <div class="form-group row " id="minimum-orders">
        {!! Form::label('minimum_orders', trans("lang.minimum_orders"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('minimum_orders', null,  ['class' => 'form-control','placeholder'=>  trans("lang.minimum_orders_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.minimum_orders_help") }}
            </div>
        </div>
    </div>

    <div class="form-group row ">
        {!! Form::label('deliverable', trans("lang.product_deliverable"),['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('deliverable', 0) !!}
                {!! Form::checkbox('deliverable', 1, null) !!}
            </label>
        </div>
    </div>

    <div class="form-group row ">
        {!! Form::label('is_enabled', trans("lang.is_enabled"),['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('is_enabled', 0) !!}
                {!! Form::checkbox('is_enabled', 1, null, ['checked']) !!}
            </label>
        </div>
    </div>

    <div class="form-group row ">
        {!! Form::label('is_refund_or_replace','Is Return?',['class' => 'col-3 control-label text-right ']) !!}
        <div class="pl-2">
            <input name="is_refund_or_replace" type="hidden" value="0" id="is_refund_or_replace">
            <input class="is_refund_or_replace icheckbox_flat-blue" name="is_refund_or_replace" type="checkbox" value="1" id="is_refund_or_replace" >
        </div>
    </div>

    <div class="form-group row" id="return-days">
        {!! Form::label('return_days', 'Return Days', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('return_days', null,  ['class' => 'form-control','placeholder'=>  'Enter the return days']) !!}
            <div class="form-text text-muted">
                Enter the return days
            </div>
        </div>
    </div>

    <div id="scheduled_delivery-section">
        <div class="form-group row ">
            {!! Form::label('scheduled_delivery', trans("lang.product_scheduled_delivery"),['class' => 'col-3 control-label text-right ']) !!}
            <div class="pl-2">
                {{--            <label class="col-9 ml-2 form-check-inline">--}}
                <input name="scheduled_delivery" type="hidden" value="0" id="scheduled_delivery">
                <input class="scheduled_delivery icheckbox_flat-blue" name="scheduled_delivery" type="checkbox" value="1" id="scheduled_delivery" >
                {{--            </label>--}}
            </div>

        </div>

        <div class="scheduled-delivery-details d-none">
            <div class="form-group row ">
                {!! Form::label('order_start_time', trans("lang.product_order_start_time"),['class' => 'col-3 control-label text-right ']) !!}
                <div class="col-3 ">
                    {!! Form::time('order_start_time', null, ['class' => 'form-control']) !!}
                </div>

                {!! Form::label('order_end_time', trans("lang.product_order_end_time"),['class' => 'col-3 control-label text-right ']) !!}
                <div class="col-3 ">
                    {!! Form::time('order_end_time', null, ['class' => 'form-control']) !!}
                </div>
            </div>

            {{--Delivery Time--}}
            <div class="form-group row ">
                {!! Form::label('delivery_time_id', trans("lang.package_delivery_time"),['class' => 'col-3 control-label text-right']) !!}
                <div class="col-9">
                    {!! Form::select('delivery_time_id', $deliveryTime, null, ['class' => 'select2 form-control delivery_time']) !!}
                    <div class="form-text text-muted">{{ trans("lang.package_delivery_time_help") }}</div>
                </div>
            </div>

            <!-- Markets Field -->
            <div class="form-group row ">
                {!! Form::label('days[]', trans("lang.days"),['class' => 'col-3 control-label text-right']) !!}
                <div class="col-9">
                    {!! Form::select('days[]', $days, $daysSelected, ['class' => 'select2 form-control' , 'multiple'=>'multiple']) !!}
                    {{--            <div class="form-text text-muted">{{ trans("lang.field_markets_help") }}</div>--}}
                </div>
            </div>
        </div>
    </div>
{{--    @if($product->product_type !== \App\Models\Product::VARIANT_BASE_PRODUCT)--}}
            <div id="flash-sale-section">
        <div class="form-group row ">
            {!! Form::label('flash_sale', trans("lang.product_flash_sale"),['class' => 'col-3 control-label text-right ']) !!}
            <div class="pl-2">
                <input name="is_flash_sale" type="hidden" value="0" id="flash_sale">
                <input class="flash_sale icheckbox_flat-blue" name="is_flash_sale" type="checkbox" value="1" id="flash_sale" >
            </div>
        </div>

        <div class="flash-sale-details d-none">
            <!-- 'Flash Sale Time  Field' -->
            <div class="form-group row ">
                <div class="input-group mb-3">
                    {!! Form::label('flash_sale_start_time', trans("lang.product_flash_sale_start_time"),['class' => 'col-3 control-label text-right ']) !!}
                    <div class="col-6">
                        <input type="datetime-local" name="flash_sale_start_time"  class="form-control @error('flash_sale_start_time')
                                is-invalid @enderror" id="flash_sale_start_time" value="{{old('flash_sale_start_time')}}">
                    </div>
                </div>

                <div class="input-group mb-3">
                    {!! Form::label('flash_sale_end_time', trans("lang.product_flash_sale_end_time"),['class' => 'col-3 control-label text-right ']) !!}
                    <div class="col-6">
                        <input type="datetime-local" name="flash_sale_end_time"  class="form-control @error('flash_sale_end_time')
                                is-invalid @enderror" id="flash_sale_end_time" value="{{old('flash_sale_end_time')}}">
                    </div>
                </div>
                <!-- Flash Sale Price Field -->
                <div class="input-group row ">
                    {!! Form::label('flash_sale_price', trans("lang.product_flash_sale_price"),['class' => 'col-3 control-label text-right ']) !!}
                    <div class="col-6 ml-2">
                        {!! Form::number('flash_sale_price', null, ['class' => 'form-control','id' => 'flash_sale_price']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--    @endif--}}


</div>
@if($customFields)
    <div class="clearfix"></div>
    <div class="col-12 custom-field-container">
        <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
        {!! $customFields !!}
    </div>
@endif

<div class="variant-product" style="border-top: 1px solid #eee;padding-top: 10px; width: 100%">
    <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
        <!-- 'Boolean scheduled delivery Field' -->
        <div class="form-group row " >
            {!! Form::label('variant_product', trans("lang.is_variant_product_available"),['class' => 'col-3 control-label ']) !!}
            <div class="pl-2">
                <input name="variant_product" type="hidden" value="0" id="variant_product">
                <input class="variant_product icheckbox_flat-blue" name="variant_product" type="checkbox" value="1" id="variant_product" >
            </div>
        </div>
    </div>

    <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column variant-product-details d-none">

        <!-- Markets Field -->
        <div class="form-group row " >
            {!! Form::label('variant_product', 'Is don\'t display product?',['class' => 'col-3 control-label ']) !!}

            <div class="pl-2">
                <input name="is_variant_display_product" type="hidden" value="0" id="is_variant_display_product">
                <input class="is_variant_display_product icheckbox_flat-blue" name="is_variant_display_product" type="checkbox" value="1" >
            </div>
        </div>
        <div class="form-group row ">
            {!! Form::label('attributes[]', trans("lang.attribute_plural"),['class' => 'col-3 control-label text-right']) !!}
            <div class="col" style="display: inline-flex">
                {!! Form::select('attributes[]', $attributes, $attributesSelected, ['class' => 'select2 form-control attributes' , 'multiple'=>'multiple', 'id' => 'attributeId' ]) !!}
                {{--            <div class="form-text text-muted">{{ trans("lang.field_markets_help") }}</div>--}}
                <button type="button" class="btn btn-attributes-apply btn-{{setting('theme_color')}} ml-1"><i class="fa fa-save"></i> {{trans('lang.apply')}}</button>
{{--                <button type="button" class="btn btn-attributes-clear btn-danger ml-1"><i class="fa fa-undo"></i> {{trans('lang.clear-all')}}</button>--}}
            </div>
{{--            <input name="attribute_ids[]" id="attribute_ids" type="hidden">--}}
        </div>
    </div>

    <div style="flex: 100%;max-width: 100%;padding: 0 4px;" class="column variant-product-details d-none">
        <div class="keywords-container"></div>
    </div>

</div>


<!-- Submit Field -->
<div class="form-group col-12 text-right">
    <button type="submit" class="btn btn-{{setting('theme_color')}} " id="button-submit"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.product')}}</button>
    <a href="{!! route('products.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>

<div class="product-add-on-template d-none">
    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label for="state">Name</label>
                <input class="form-control addon_name char-limit" id="addon_name_{i}" name="addon_name[]" placeholder="Please Enter Add-on Name" maxlength="250" >
                <small class="char-limits text-danger"></small>
            </div>
        </div>

        <div class="col-4">
            <div class="form-group">
                <label for="state">Price</label>
                <div class="input-group">
                    <input class="form-control char-limit" id="addon_price_{i}" name="add_on_price[]" placeholder="Please Enter Add-on Price" maxlength="250" >
                    <small class="char-limits text-danger"></small>
                    <div class="input-group-append">
                        {add-on-button}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts_lib')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"> </script>
    <script>


        $(document).ready(function() {

            $('#order_start_time').val(null);
            $('#order_end_time').val(null);
            $('#delivery_time_id').val(null);
            $('.scheduled_delivery').on('click', function () {
                if ($(this).is(":checked")) {
                    $('.scheduled-delivery-details').removeClass('d-none');
                } else {
                    $('.scheduled-delivery-details').addClass('d-none');
                    $('#order_start_time').val(null);
                    $('#order_end_time').val(null);
                    $('#delivery_time_id').val(null);
                    $('#days').val(null);
                }
            });

            $('.is_refund_or_replace').on('click', function () {
                if ($(this).is(":checked")) {
                    $("#return-days").removeClass('d-none');
                } else {
                    $("#return-days").addClass('d-none');
                }
            });

            $("#return-days").addClass('d-none');

            $('.flash_sale').on('click', function () {
                if ($(this).is(":checked")) {

                    $('.flash-sale-details').removeClass('d-none');
                } else {

                    $('.flash-sale-details').addClass('d-none');
                    $('#flash_sale_start_time').val(null);
                    $('#flash_sale_end_time').val(null);
                    $('#flash_sale_price').val(null);

                }
            });

            $('.variant_product').on('click', function () {
                if ($(this).is(":checked")) {

                    $('.variant-product-details').removeClass('d-none');
                } else {
                    $('#variant_product').val('0');
                    $('#variant_product_price').val('0');
                    let value = $('#variant_product').val();
                    let price = $('#variant_product_price').val();
                    $('.variant-product-details').addClass('d-none');
                }
            });


            $('#flash-sale-start-time-btn').click(function (e) {
                e.preventDefault();
                $('#flash_sale_start_time').val('');

            });

            $('.btn-attributes-apply').on('click', function () {
                var attributes = $(".attributes").val();
                // $('#attribute_ids').val(attributes);
                $(".attributes").prop("disabled", true);

                var price = $('#price').val();

                $.ajax({
                    url: "{{url('products/get-attribute-options-by-attributes')}}",
                    data: {
                        attribute_ids: attributes,
                        _token: '{{csrf_token()}}'
                    },
                    // dataType : 'json',
                    success: function (data) {
                        $('.btn-attributes-apply').attr('disabled', true);
                        $('.variant-product-template').html(data);
                        //clone start
                        let descriptionIndex = 1;

                        function cloneDescription() {
                            let template = $('.variant-product-template').clone();

                            if (descriptionIndex === 1) {
                                template = $(template.html().replaceAll('{button}', '<button class="btn btn-success button-add-description" type="button"><i class="fa fa-plus"></i></button>'));
                            } else {
                                template = $(template.html().replaceAll('{button}', '<button class="btn btn-danger button-remove-description" type="button"><i class="fa fa-remove"></i></button>'));
                            }

                            template.removeClass('variant-product-template');
                            template.removeClass('d-none');
                            $('.keywords-container').append(template);
                            descriptionIndex++;

                            template.find('.variant_product_price').each(function () {
                                $(this).prop('required', true);
                            });
                        }

                        cloneDescription();

                        $(document).on('click', '.button-add-description', function () {
                            cloneDescription();
                        });

                        $(document).on('click', '.button-remove-description', function () {
                            $(this).closest('.row').remove();
                        });

                        $('.multi-select-validation').on('change', function () {
                            $(this).siblings('.select-error').hide();
                        });

                    }
                });
            });

            $("form").submit(function () {
                $(".attributes").prop("disabled", false);
            });

            let addOnIndex = 1;

            function cloneAddon() {
                let addOnTemplate = $('.product-add-on-template').clone();

                addOnTemplate = addOnTemplate.html();
                addOnTemplate = addOnTemplate.replaceAll("{i}", addOnIndex);

                if (addOnIndex === 1) {
                    addOnTemplate = $(addOnTemplate.replaceAll('{add-on-button}', '<button class="btn btn-success button-add-on" type="button"><i class="fa fa-plus"></i></button>'));
                } else {
                    addOnTemplate = $(addOnTemplate.replaceAll('{add-on-button}', '<button class="btn btn-danger button-remove-add-on" type="button"><i class="fa fa-remove"></i></button>'));
                }

                addOnTemplate = $(addOnTemplate)
                $('.add-on-container').append(addOnTemplate);
                addOnIndex++;

                // addOnTemplate.removeClass('d-none');

            }

            cloneAddon();

            $(document).on('click', '.button-add-on', function () {
                cloneAddon();
            });

            $(document).on('click', '.button-remove-add-on', function () {
                $(this).closest('.row').remove();
            });

            var MarketID = $('#market_id').val();

            MarketSectors(MarketID)

            $("#market_id").change(function () {
                var MarketID = $('#market_id').val();
                MarketSectors(MarketID)
            });

            var SectorID = null;

            function MarketSectors(MarketID) {
                if (MarketID) {
                    $.ajax({
                        url: '/product/market_sectors/ajax/' + MarketID,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            // $('#product_id').select2('val','');
                            $('select[name="sector_id"]').empty();
                            $.each(data, function (key, value) {
                                $('#sector_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                                SectorID = $('#sector_id').val();
                                getcategories(SectorID)
                                if (SectorID == 1) {
                                    $('#scheduled_delivery-section').show();
                                    $('#food-type-div').show()
                                    $('#minimum-orders').show();
                                } else if (SectorID == 2) {
                                    $('#food-type-div').show();
                                    $('#scheduled_delivery-section').hide();
                                    $('#minimum-orders').hide();
                                } else {
                                    $('#scheduled_delivery-section').hide();
                                    $('#minimum-orders').hide();
                                    $('#food-type-div').hide()
                                }
                            });

                        }
                    });
                }
            }

            $("#sector_id").change(function () {
                var SectorID = $(this).val();
                getcategories(SectorID)
                if (SectorID == 1) {
                    $('#scheduled_delivery-section').show();
                    $('#minimum-orders').show();
                    $('#food-type-div').show();

                } else if (SectorID == 2) {
                    $('#food-type-div').show();
                    $('#scheduled_delivery-section').hide();
                    $('#minimum-orders').hide();

                } else {
                    $('#scheduled_delivery-section').hide();
                    $('#minimum-orders').hide();
                    $('#food-type-div').hide();
                }

            });

            $("#category_id").change(function () {
                var categoryId = $(this).val();
                subCategories(categoryId);
            });

            function getcategories(SectorID) {
                if (SectorID) {
                    $('#category_id').empty();
                    $.ajax({
                        url: '/product/get-categories/ajax/' + SectorID,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('select[name="category_id"]').empty();
                            $.each(data, function (key, value) {
                                $('#category_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                            var categoryId = $('#category_id').val();
                            subCategories(categoryId)
                        }
                    });
                }
            }

            function subCategories(categoryId) {
                if (categoryId) {
                    $.ajax({
                        url: '/product/sub_categories/ajax/' + categoryId,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('select[name="sub_category_id"]').empty();
                            $.each(data, function (key, value) {
                                $('#sub_category_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                }
            }



            $.validator.addMethod('positiveNumber', function (value, element) {
                return Number(value) >= 0
            }, 'Negative Number is not allowed.');


            $('#form-create').validate({
                rules: {

                    'owleto_commission_percentage': {
                        required: false,
                        positiveNumber: true,
                    },
                    'minimum_orders': {
                        required: false,
                        positiveNumber: true,
                    }
                },
            });
        });

    </script>
@endpush
