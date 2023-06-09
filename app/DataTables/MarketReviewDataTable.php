<?php
/**
 * File name: MarketReviewDataTable.php
 * Last modified: 2020.05.04 at 09:04:19
 * Author: Pixbit Solutions - https://pixbitsolutions.com
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;

use App\Criteria\MarketReviews\MarketReviewsOfUserCriteria;
use App\Criteria\MarketReviews\OrderMarketReviewsOfUserCriteria;
use App\Models\CustomField;
use App\Models\Market;
use App\Models\MarketReview;
use App\Repositories\MarketReviewRepository;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

/**
 * Class MarketReviewDataTable
 * @package App\DataTables
 */
class MarketReviewDataTable extends DataTable
{
    /**
     * custom fields columns
     * @var array
     */
    public static $customFields = [];

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        $dataTable = $dataTable
            ->editColumn('updated_at', function ($market_review) {
                return getDateColumn($market_review, 'updated_at');
            })
            ->addColumn('action', function ($market_review) {
                return view('market_reviews.datatables_actions', compact('market_review'));
            })
            ->rawColumns(array_merge($columns));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\MarketReview $model
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function query(MarketReview $model)
    {
        if (auth()->user()->hasRole('admin')) {

            return $model->with("user")
                ->with("market")
                ->select("market_reviews.*");
        } else if (auth()->user()->hasRole('vendor_owner')){

            $userMarketIds = Market::whereHas('users', function ($query){
                $query->where('user_id', Auth::id());
            })->pluck('id');

            return $model->with("user")
                ->with("market")
                ->join("markets", "markets.id", "=", "market_reviews.market_id")
                ->whereIn('markets.id', $userMarketIds)
                ->select("market_reviews.*");
        }

    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['title'=>trans('lang.actions'),'width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
            ->parameters([
                'searching' => true,
                'ordering' => false,
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            [
                'data' => 'review',
                'title' => trans('lang.market_review_review'),

            ],
            [
                'data' => 'rate',
                'title' => trans('lang.market_review_rate'),

            ],
            [
                'data' => 'user.name',
                'title' => trans('lang.market_review_user_id'),

            ],
            [
                'data' => 'market.name',
                'title' => trans('lang.market_review_market_id'),

            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.market_review_updated_at'),
                'searchable' => false,
            ]
        ];

        $hasCustomField = in_array(MarketReview::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', MarketReview::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.market_review_' . $field->name),
                    'orderable' => false,
                    'searchable' => false,
                ]]);
            }
        }
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'market_reviewsdatatable_' . time();
    }

    /**
     * Export PDF using DOMPDF
     * @return mixed
     */
    public function pdf()
    {
        $data = $this->getDataForPrint();
        $pdf = PDF::loadView($this->printPreview, compact('data'));
        return $pdf->download($this->filename() . '.pdf');
    }
}
