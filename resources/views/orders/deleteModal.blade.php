@if($isDeleted > 0)
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
          <div class="modal-header border-0">
            <h5 class="modal-title w-100 text-success fw-bold">Success</h5>
          </div>
          <div class="modal-body">
            <p id="successMessage">Product deleted successfully!</p>
          </div>
          <div class="modal-footer border-0 justify-content-center">
            <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>
@else
    <div class="modal fade" id="failedModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
          <div class="modal-header border-0">
            <h5 class="modal-title w-100 text-danger fw-bold">Failed</h5>
          </div>
          <div class="modal-body">
            <p id="failedMessage">Failed to delete product.</p>
          </div>
          <div class="modal-footer border-0 justify-content-center">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>
@endif
