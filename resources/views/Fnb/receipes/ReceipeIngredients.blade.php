<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 id="RECIPE_PAGE_TITLE">Recipes Management</h2>
        <h4 class="fw-bold" style="padding-left:10px;" id="RECIPE_TITLE">{{ $item->fi_item_name }}</h4>

        <div>
            <button class="btn btn-outline-secondary me-2">
                <i class="bi bi-printer"></i>
            </button>
            <button class="btn btn-outline-secondary me-2">
                <i class="bi bi-download"></i>
            </button>
            <button class="btn btn-warning fw-bold" id="BTN_SAVE_RECIPE">
                Save
            </button>
        </div>
    </div>

    <!-- RECIPE SETTINGS -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label fw-bold">Batch Yield</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="BATCH_YIELD" value="8">
                        <span class="input-group-text">pcs</span>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Portion Size</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="PORTION_SIZE" value="1">
                        <span class="input-group-text">pcs</span>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Target Margin</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="TARGET_MARGIN" value="70">
                        <span class="input-group-text">%</span>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- BILL OF MATERIALS -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="fw-bold mb-0">Bill of Materials</h5>
        </div>

        <div class="card-body">

            <table class="table align-middle" id="INGREDIENTS_TABLE">
                <thead>
                    <tr>
                        <th>Ingredient</th>
                        <th style="width: 120px;">Qty</th>
                        <th style="width: 120px;">UoM</th>
                        <th style="width: 120px;">Waste %</th>
                        <th style="width: 150px;">Unit Cost</th>
                        <th style="width: 150px;">Line Cost</th>
                        <th>Notes</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody id="INGREDIENTS_BODY">

                </tbody>
            </table>

            <a href="{{ url('/fnb/receipes/addform?item_id='.$item->fi_id) }}" class="btn btn-light border fw-bold" id="BTN_ADD_INGREDIENTS">
                + Add Ingredient
            </a>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="p-3 rounded border bg-light">
                        <div class="fw-bold">Batch Cost</div>
                        <h4 class="fw-bold mt-2 mb-0" id="BATCH_COST">$0.00</h4>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-3 rounded border bg-light">
                        <div class="fw-bold">Portions</div>
                        <h4 class="fw-bold mt-2 mb-0" id="PORTION_COUNT">0</h4>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-3 rounded border bg-light">
                        <div class="fw-bold">Cost / Portion</div>
                        <h4 class="fw-bold mt-2 mb-0" id="PORTION_COST">$0.00</h4>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- SCALE PREVIEW -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="fw-bold mb-0">Scale Preview & Pricing</h5>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="form-label fw-bold">Scale Factor</label>
                    <div class="input-group">
                        <button class="btn btn-outline-secondary" id="SCALE_MINUS">-</button>
                        <input type="number" class="form-control text-center" id="SCALE_FACTOR" value="1">
                        <button class="btn btn-outline-secondary" id="SCALE_PLUS">+</button>
                    </div>
                    <small class="text-muted d-block mt-1">e.g., 0.5 half-batch, 2.0 double-batch</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Suggested Price</label>
                    <div class="border rounded p-3">
                        <h4 class="fw-bold mb-0" id="SUGGESTED_PRICE">$0.00</h4>
                        <small class="text-muted" id="TARGET_MARGIN_LABEL">at 70% margin</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Scaled Batch (preview)</label>
                    <div class="border rounded p-3">
                        <div id="SCALED_PREVIEW">Yield 0 pcs · Cost $0.00</div>
                    </div>
                </div>

            </div>

            <table class="table mt-4" id="SCALED_INGREDIENTS_TABLE">
                <thead>
                    <tr>
                        <th>Ingredient (scaled)</th>
                        <th style="width:120px;">Qty</th>
                        <th style="width:100px;">UoM</th>
                        <th style="width:150px;">Line Cost</th>
                    </tr>
                </thead>
                <tbody id="SCALED_INGREDIENTS_BODY">
                    <!-- AJAX scaled results -->
                </tbody>
            </table>

        </div>
    </div>

    <!-- PREPARATION STEPS -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="fw-bold mb-0">Preparation Steps</h5>
        </div>

        <div class="card-body">
            <div id="PREP_STEPS">
                <!-- AJAX loaded steps -->
            </div>
        </div>
    </div>

</div>
