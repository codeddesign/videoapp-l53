<?php

namespace App\Http\Requests\Api\Admin;

use App\Http\Requests\Request;
use Carbon\Carbon;

class StoreReportRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'            => 'required',
            'date_range'       => 'required',
            'start_date'       => 'required_if:date_range,custom|date|nullable',
            'end_date'         => 'required_if:date_range,custom|date|after_or_equal:start_date|nullable',
            'sort_by'          => '',
            'combine_by'       => 'required',
            'included_metrics' => '',
            'recipient'        => 'required|emails',
            'schedule'         => 'required|in:once,daily,weekly,monthly',
            'schedule_every'   => 'required_if:schedule,monthly,weekly',
        ];
    }

    public function transform()
    {
        return [
            'title'            => $this->get('title'),
            'recipient'        => $this->get('recipient'),
            'schedule'         => $this->get('schedule'),
            'schedule_every'   => $this->get('schedule_every'),
            'filter'           => $this->get('filter'),
            'included_metrics' => $this->get('included_metrics'),
            'combine_by'       => $this->get('combine_by'),
            'sort_by'          => $this->get('sort_by') ?: $this->get('combine_by'),
            'date_range'       => $this->get('date_range'),
            'start_date'       => $this->get('start_date') ? Carbon::parse($this->get('start_date')) : null,
            'end_date'         => $this->get('end_date') ? Carbon::parse($this->get('end_date')) : null,
        ];
    }
}
