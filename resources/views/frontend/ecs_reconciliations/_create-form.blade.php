<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add New Reconciliation</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('frontend.ecs_reconciliations.store') }}" method="POST" onsubmit="return confirm('Are you sure your input values are accurate?')">
            @csrf

            <div class="form-group">
                <label for="for_date">For Date</label>
                <input type="date" name="for_date" id="for_date" class="form-control" required>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="ecs_sales_amount">ECS Sales Amount</label>
                        <input type="number" step="0.01" name="ecs_sales_amount" id="ecs_sales_amount" class="form-control" value="0" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="ibe_sales_amount">Crane Sales Amount</label>
                        <input type="number" step="0.01" name="ibe_sales_amount" id="ibe_sales_amount" class="form-control" value="0" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="amounts_difference">Difference in the Amounts</label>
                <input type="number" step="0.01" name="difference" id="amounts_difference" class="form-control" readonly>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const ecsInput = document.getElementById('ecs_sales_amount');
                    const ibeInput = document.getElementById('ibe_sales_amount');
                    const diffInput = document.getElementById('amounts_difference');

                    function updateDifference() {
                        const ecs = parseFloat(ecsInput.value) || 0;
                        const ibe = parseFloat(ibeInput.value) || 0;
                        diffInput.value = (ecs - ibe).toFixed(2);
                    }

                    ecsInput.addEventListener('input', updateDifference);
                    ibeInput.addEventListener('input', updateDifference);
                });
            </script>

            <div class="form-group">
                <label for="comment">Comment</label>
                <textarea class="form-control" name="comment" id="comment"></textarea>
            </div>

            <input type="hidden" name="agent_user_id" value="{{ $logged_in_user->id }}">

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
