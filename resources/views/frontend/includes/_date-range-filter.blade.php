<div class="col">
    <div class="form-group">
        <input type="date" name="from_date" value="{{ substr($from_date, 0, 10) }}" class="form-control">
        <label>From Date</label>
    </div>
</div>
<div class="col">
    <div class="form-group">
        <input type="date" name="to_date" max="{{ now()->toDateString() }}" value="{{ substr($to_date, 0, 10) }}" class="form-control">
        <label>To Date</label>
    </div>
</div>
