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
            'title'      => 'required',
            'date_range' => 'required',
            'start_date' => 'required_if:date_range,custom|date',
            'end_date'   => 'required_if:date_range,custom|date|after:start_date',
            'sort_by'    => 'required',
            'recipient'  => 'required|email',
            'schedule'   => 'required|in:once,daily,weekly,monthly',
        ];
    }

    public function transform()
    {
        return [
            'title'      => $this->get('title'),
            'recipient'  => $this->get('recipient'),
            'schedule'   => $this->get('schedule'),
            'sort_by'    => $this->get('sort_by'),
            'date_range' => $this->get('date_range'),
            'start_date' => $this->get('start_date') ? Carbon::parse($this->get('start_date')) : null,
            'end_date'   => $this->get('end_date') ? Carbon::parse($this->get('end_date')) : null,
        ];
    }
}
